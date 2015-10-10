<?php
/*
 * This file is part of aspetos.
 *
 * (c)2015 Ludwig Ruderstaller <lr@cwd.at>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Aspetos\Service\Legacy\Import;

use JMS\DiExtraBundle\Annotation as DI;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class BaseImporter
 *
 * @package Aspetos\Legacy\Service\Import
 * @author  Ludwig Ruderstaller <lr@cwd.at>
 *
 * @DI\Service("aspetos.service.legacy.import.base")
 */
abstract class BaseImporter
{
    /**
     * @var OutputInterface
     */
    protected $output;

    /**
     * @param OutputInterface $output
     *
     * @return $this
     */
    public function setOutput(OutputInterface $output)
    {
        $this->output = $output;

        return $this;
    }

    /**
     *
     * @return OutputInterface
     */
    public function getOutput()
    {
        return $this->output;
    }

    /**
     * @param string $message
     * @param int    $level
     * @param bool   $newline
     */
    protected function writeln($message, $level=1, $newline = true)
    {
        if (is_null($this->output)) {
            return;
        }

        if ($this->output->getVerbosity() >= $level) {
            $this->output->write($message, $newline);
        }

        return;
    }
}
