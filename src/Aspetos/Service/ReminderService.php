<?php
/*
 * This file is part of Aspetos
 *
 * (c)2014 Ludwig Ruderstaller <lr@cwd.at>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Aspetos\Service;

use Aspetos\Model\Entity\Obituary;
use Aspetos\Model\Entity\Reminder;
use Aspetos\Model\Entity\ReminderHistory;
use Cwd\GenericBundle\LegacyHelper\BaseIntEncoder;
use Doctrine\ORM\EntityManager;
use JMS\DiExtraBundle\Annotation as DI;
use Cwd\GenericBundle\Service\Generic;
use Aspetos\Model\Entity\Reminder as Entity;
use Aspetos\Service\Exception\ReminderNotFoundException as NotFoundException;
use Psr\Log\LoggerInterface;
use Symfony\Component\Routing\Router;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * Class Aspetos Service Reminder
 *
 * @package Aspetos\Service
 * @author  Ludwig Ruderstaller <lr@cwd.at>
 *
 * @DI\Service("aspetos.service.reminder", parent="cwd.generic.service.generic")
 */
class ReminderService extends Generic
{
    /**
     * @var MessagingService
     */
    protected $messagingService;

    /**
     * @var TranslatorInterface
     */
    protected $translator;

    /**
     * @var Router
     */
    protected $router;

    /**
     * @param EntityManager       $entityManager
     * @param LoggerInterface     $logger
     * @param MessagingService    $messagingService
     * @param TranslatorInterface $translator
     * @param Router              $router
     *
     * @DI\InjectParams({
     *     "messagingService" = @DI\Inject("aspetos.service.messaging"),
     *     "translator" = @DI\Inject("translator", strict = false),
     *     "router" = @DI\Inject("router")
     * })
     */
    public function __construct(EntityManager $entityManager, LoggerInterface $logger,
                                MessagingService $messagingService, TranslatorInterface $translator,
                                Router $router)
    {
        $this->messagingService = $messagingService;
        $this->translator       = $translator;
        $this->router           = $router;
        parent::__construct($entityManager, $logger);
    }

    /**
     * Find Object by ID
     *
     * @param int $pid
     *
     * @return Entity
     * @throws NotFoundException
     */
    public function find($pid)
    {
        try {
            $obj = parent::findById('Model:Reminder', intval($pid));

            if ($obj === null) {
                $this->getLogger()->info('Row with ID {id} not found', array('id' => $pid));
                throw new NotFoundException('Row with ID ' . $pid . ' not found');
            }

            return $obj;
        } catch (\Exception $e) {
            throw new NotFoundException();
        }
    }

    /**
     * @param string $email
     * @param string $state
     *
     * @return array
     * @throws NotFoundException
     */
    public function findByEmail($email, $state = 'active')
    {
        try {
            $obj = parent::findOneByFilter('Model:Reminder', array('email' => $email, 'state' => $state));

            if ($obj === null) {
                $this->getLogger()->info('Row with  "{id}" in state "{state}" not found', array('email' => $email, 'state' => $state));
                throw new NotFoundException('Row with email ' . $email . ' in '.$state.' not found');
            }

            return $obj;
        } catch (\Exception $e) {
            throw new NotFoundException();
        }
    }

    /**
     * @param Obituary  $obituary
     * @param string    $email
     * @param \Datetime $remindDate
     * @param string    $type
     * @param bool|true $optin
     *
     * @throws \Cwd\GenericBundle\Exception\PersistanceException
     */
    public function addReminder($obituary, $email, \Datetime $remindDate, $type = 'anniversary', $optin = true)
    {
        try {
            $reminder = $this->findByObituaryAndEmail($obituary, $email, $type);
            $reminder->setRemindAt($remindDate);
        } catch (NotFoundException $e) {
            $reminder = new Reminder();
            $reminder->setEmail($email)
                ->setObituary($obituary)
                ->setType($type)
                ->setRemindAt($remindDate);

            $this->persist($reminder);
            $this->flush();
        }

        if ($optin && !$this->isEmailVerified($email)) {
            $reminder->sendoptin();
        } else {
            if ($reminder->getState() != 'bounced') {
                $reminder->setState('active');
            }
        }

        $this->flush();
    }

    /**
     * @param Obituary $obituary
     * @param string   $email
     * @param string   $type
     *
     * @return array
     * @throws NotFoundException
     */
    public function findByObituaryAndEmail(Obituary $obituary, $email, $type = 'anniversary')
    {
        try {
            $object = $this->findOneByFilter('Model:Reminder', array('obituary' => $obituary, 'email' => $email, 'type' => $type));
            if ($object === null) {
                throw new \Exception('Reminder not found');
            }

            return $object;
        } catch (\Exception $e) {
            throw new NotFoundException($e);
        }
    }

    /**
     * @param Entity $reminder
     */
    public function sendOptin(Reminder $reminder)
    {
        $type = $reminder->getType();
        $url  = $this->router->generate('aspetos_frontend_user_activate', array('token' => BaseIntEncoder::encode($reminder->getId() * 1234567890)), Router::ABSOLUTE_URL);
        $content = $this->translator->trans('reminder.optin.'.$type, array(
                '%obituary_firstname%' => $reminder->getObituary()->getFirstname(),
                '%obituary_lastname%' => $reminder->getObituary()->getLastname(),
                '%obituary_deathdate%' => $reminder->getObituary()->getDayOfDeath()->format('d.m.Y'),
                '%link%' => sprintf('<a href="%s">%s</a>', $url, $url)
            )
        );

        $result = $this->messagingService->sendMessage(
            $reminder->getEmail(),
            null,
            'Bestätigung erforderlich',
            'aspetos-copy-01',
            array(
                'html_content' => nl2br($content)
            )
        );

        if (isset($result[0])) {
            $status = $result[0]['status'];
            $this->addHistory($reminder, $status, json_encode($result[0]));
        }

        $reminder->optined();
        $this->flush();
    }

    /**
     * @param $email
     *
     * @return bool
     */
    protected function isEmailVerified($email)
    {
        try {
            $reminder = $this->findByEmail($email, 'active');

            return true;
        } catch (NotFoundException $e) {
            return false;
        }

        return false;
    }

    /**
     * @param Entity $reminder
     * @param string $result
     * @param array  $data
     *
     * @return Entity
     * @throws \Cwd\GenericBundle\Exception\PersistanceException
     */
    public function addHistory(Reminder $reminder, $result, $data = array())
    {
        $history = new ReminderHistory();
        $history->setReminder($reminder)
                ->setResult($result)
                ->setDetail(json_encode($data));
        $this->persist($history);

        $reminder->addReminderHistory($history);

        return $reminder;
    }

    /**
     * @return Entity
     */
    public function getNew()
    {
        return new Entity();
    }
}
