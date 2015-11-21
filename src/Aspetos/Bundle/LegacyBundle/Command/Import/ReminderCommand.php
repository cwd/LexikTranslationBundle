<?php
/*
 * This file is part of aspetos.
 *
 * (c)2015 Ludwig Ruderstaller <lr@cwd.at>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Aspetos\Bundle\LegacyBundle\Command\Import;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class ReminderImportCommand
 *
 * @package Aspetos\Bundle\LegacyBundle\Command
 * @author  Ludwig Ruderstaller <lr@cwd.at>
 */
class ReminderCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('aspetos:legacy:import:reminder')
            ->setDescription('Imports reminders from Legacy Database')
            ->addOption(
                'offset',
                null,
                InputOption::VALUE_OPTIONAL,
                'Loop offset',
                0
            );
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $importer = $this->getContainer()->get('aspetos.service.legacy.import.reminder');
        $importer->setOutput($output);

        $importer->run($input);
    }
}

