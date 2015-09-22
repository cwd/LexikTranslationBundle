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
use Symfony\Component\Console\Command\Command;
use Sensio\Bundle\GeneratorBundle\Command\Validators;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Sensio\Bundle\GeneratorBundle\Command\Helper\QuestionHelper;
use Sensio\Bundle\GeneratorBundle\Generator\ControllerGenerator;
use Symfony\Component\HttpKernel\Bundle\BundleInterface;
use Aspetos\Model\Entity\User;

/**
 * Class UpdateDomainStatusCommand
 *
 * @package Dpanel\Bundle\AdminBundle\Command
 * @author  Ludwig Ruderstaller <lr@cwd.at>
 */
class AddAdminCommand extends Command
{
    /**
     * @var OutputInterface
     */
    protected $output = null;

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
        $this->output = $output;

        $em = $this->getApplication()->getKernel()->getContainer()->get('em');

        $admin = new Admin();
        $admin->setPassword($input->getOption('password'))
            ->setEmail($input->getOption('email'))
            ->setFirstname($input->getOption('firstname'))
            ->setLastname($input->getOption('lastname'))
            ->setEnabled(true);

        $em->persist($admin);
        $em->flush();

        $role = $em->getRepository('Model:Role')->find(1);

        $admin->addUserRole($role);
        $em->flush();

        $this->writeln('Update done', OutputInterface::VERBOSITY_QUIET, true);
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
