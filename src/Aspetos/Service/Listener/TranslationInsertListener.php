<?php
/*
 * This file is part of aspetos.
 *
 * (c)2015 Ludwig Ruderstaller <lr@cwd.at>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Aspetos\Service\Listener;

use Lexik\Bundle\TranslationBundle\Manager\TransUnitManagerInterface;
use Lexik\Bundle\TranslationBundle\Storage\StorageInterface;
use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\Translation\DataCollectorTranslator;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * Class TranslationInsertListener
 *
 * @package Aspetos\Service\Listener
 * @author  Ludwig Ruderstaller <lr@cwd.at>
 */
class TranslationInsertListener
{

    /**
     * @var TransUnitManagerInterface
     */
    protected $transUnitManager;

    /**
     * @var TranslatorInterface
     */
    protected $translator;

    /**
     * @var StorageInterface
     */
    protected $storage;

    protected $debug = false;

    /**
     * TranslationInsertListener constructor.
     *
     * @param TransUnitManagerInterface $transUnitManager
     * @param TranslatorInterface       $translator
     * @param StorageInterface          $translationStorage
     */
    public function __construct(TransUnitManagerInterface $transUnitManager, TranslatorInterface $translator, StorageInterface $translationStorage, $debug)
    {
        $this->transUnitManager = $transUnitManager;
        $this->translator       = $translator;
        $this->storage          = $translationStorage;
        $this->debug            = $debug;
    }

    /**
     * @param Event $event
     */
    public function onTerminate(Event $event)
    {
        if ($this->translator === null || !$this->debug) {
            return;
        }

        if (defined('TESTENV') && TESTENV) {
            return;
        }

        $messages = $this->translator->getCollectedMessages();

        foreach ($messages as $message) {
            if ($message['state'] === DataCollectorTranslator::MESSAGE_MISSING && !is_numeric($message['id'])) {
                try {
                    $row = $this->storage->getTransUnitByKeyAndDomain($message['id'], $message['domain']);
                    if ($row === null) {
                        throw new \Exception('not found');
                    }
                    continue;
                } catch (\Exception $e) {
                    try {
                        $transUnit = $this->transUnitManager->create($message['id'], $message['domain'], false);
                        $this->storage->flush();
                        $this->transUnitManager->addTranslation($transUnit, 'en', $message['translation']);
                    } catch (\Exception $e) {
                    }
                }
            }
        }

        $this->storage->flush();
    }
}
