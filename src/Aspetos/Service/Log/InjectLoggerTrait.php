<?php
/*
 * This file is part of Aspetos
 *
 * (c)2014 Ludwig Ruderstaller <lr@cwd.at>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Aspetos\Service\Log;

use JMS\DiExtraBundle\Annotation as DI;
use Psr\Log\LoggerInterface;

/**
 * This is a convenience trait to easily inject logger functionality into any class.
 * Use this inside your annotation-powered service definition to automagically get
 * access to an working logger instance.
 *
 * @package Aspetos\Service\Log
 * @author  Ludwig Ruderstaller <lr@cwd.at>
 */
trait InjectLoggerTrait
{
    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * Inject a logger instance
     *
     * @DI\InjectParams({
     *      "logger" = @DI\Inject("logger"),
     * })
     *
     * @param LoggerInterface $logger
     */
    public function setLogger(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * Get the injected logger service.
     *
     * @return LoggerInterface
     */
    protected function getLogger()
    {
        return $this->logger;
    }
}
