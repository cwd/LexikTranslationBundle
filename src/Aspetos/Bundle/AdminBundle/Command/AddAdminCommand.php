<?php
/*
 * This file is part of aspetos.
 *
 * (c)2015 Ludwig Ruderstaller <lr@cwd.at>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Aspetos\Bundle\AdminBundle\Command;

use Aspetos\Model\Entity\Admin;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class UpdateDomainStatusCommand
 *
 * @package Aspetos\Bundle\AdminBundle\Command
 * @author  Ludwig Ruderstaller <lr@cwd.at>
 */
class AddAdminCommand extends ContainerAwareCommand
{
    /**
     * Configure the command
     */
    protected function configure()
    {
        $this->setName('aspetos:add-admin')
            ->setDescription('Create admin')
            ->setDefinition(array(
                new InputOption('email', '', InputOption::VALUE_REQUIRED),
                new InputOption('password', '', InputOption::VALUE_REQUIRED),
                new InputOption('firstname', '', InputOption::VALUE_REQUIRED),
                new InputOption('lastname', '', InputOption::VALUE_REQUIRED)
            ));
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return int|null|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $em = $this->getContainer()->get('em');

        $admin = new Admin();
        $admin
            ->setPlainPassword($input->getOption('password'))
            ->setEmail($input->getOption('email'))
            ->setFirstname($input->getOption('firstname'))
            ->setLastname($input->getOption('lastname'))
            ->setEnabled(true)
        ;

        $group = $em->getRepository('Model:Group')->find(1);

        $admin->addGroup($group);
        $em->flush();

        $this->getContainer()->get('logger')->info('Update done');
    }
}
