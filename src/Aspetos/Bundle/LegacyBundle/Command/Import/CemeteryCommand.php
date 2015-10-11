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
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class CemeteryCommand
 *
 * @package Aspetos\Bundle\Legacy\Command
 * @author  Ludwig Ruderstaller <lr@cwd.at>
 */
class CemeteryCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('aspetos:legacy:import:cemetery')
            ->setDescription('Imports Cemetery from Legacy Database')
        ;
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var \Aspetos\Service\Legacy\Import\CemeteryImporter $importer */
        $importer = $this->getContainer()->get('aspetos.service.legacy.import.cemetery');
        $importer->setOutput($output);

        $importer->run();
    }
}

