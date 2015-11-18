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

use JMS\DiExtraBundle\Annotation as DI;
use Hip\MandrillBundle\Dispatcher;
use Hip\MandrillBundle\Message;

/**
 * Class Aspetos Service Permission
 *
 * @package Aspetos\Service
 * @author  Ludwig Ruderstaller <lr@cwd.at>
 *
 * @DI\Service("aspetos.service.messaging")
 */
class MessagingService
{
    /**
     * @var Dispatcher
     */
    protected $mandrill;

    /**
     * @param Dispatcher $mandrill
     *
     * @DI\InjectParams({
     *     "mandrill" = @DI\Inject("hip_mandrill.dispatcher")
     * })
     */
    public function __construct(Dispatcher $mandrill)
    {
        $this->mandrill = $mandrill;
    }

    /**
     * @param string      $toEmail
     * @param string      $toName
     * @param string      $subject
     * @param null|string $template
     * @param array       $options
     *
     * @return array|bool
     */
    public function sendMessage($toEmail, $toName, $subject = null, $template = null, $options = array())
    {
        $message = new Message();
        $message->setMergeLanguage('handlebars')
                ->setSubject($subject)
                ->addTo($toEmail, $toName)
                ->addMergeVars($toEmail, $options)
                ->setTrackClicks(false);

        return $this->getMandrill()->send($message, $template, array(), true);
    }

    /**
     * @return Dispatcher
     */
    protected function getMandrill()
    {
        return $this->mandrill;
    }
}
