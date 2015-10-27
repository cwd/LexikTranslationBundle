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
 * Class SupplierImportCommand
 *
 * @package Aspetos\Bundle\LegacyBundle\Command
 * @author  Ludwig Ruderstaller <lr@cwd.at>
 */
class SupplierCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('aspetos:legacy:import:supplier')
            ->setDescription('Imports Suppliers from Legacy Database')
            ->addOption(
                'image',
                null,
                InputOption::VALUE_NONE,
                'Import Images'
            )
            /*
            ->addOption(
                'grant-type',
                null,
                InputOption::VALUE_REQUIRED | InputOption::VALUE_IS_ARRAY,
                'Sets allowed grant type for client. Use this option multiple times to set multiple grant types..',
                null
            )
            ->setHelp(
                <<<EOT
                    The <info>%command.name%</info>command creates a new client.

<info>php %command.full_name% [--redirect-uri=...] [--grant-type=...] name</info>

EOT
            **/
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
        $importer = $this->getContainer()->get('aspetos.service.legacy.import.supplier');
        $importer->setOutput($output);

        $importer->run($input);
    }
}

