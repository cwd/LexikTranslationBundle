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

use Aspetos\Bundle\LegacyBundle\Model\Entity\Province;
use Aspetos\Model\Entity\District;
use Aspetos\Model\Entity\Region;
use Doctrine\ORM\EntityManager;
use JMS\DiExtraBundle\Annotation as DI;
use libphonenumber\NumberParseException;
use libphonenumber\PhoneNumberUtil;
use Symfony\Component\Console\Output\OutputInterface;
use Doctrine\ORM\EntityNotFoundException;
use Symfony\Component\Stopwatch\Stopwatch;

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
     * @var EntityManager
     */
    protected $entityManager;

    /**
     * @var EntityManager
     */
    protected $legacyEntityManager;

    /**
     * @var Stopwatch
     */
    protected $stopwatch;

    /**
     * @var PhoneNumberUtil
     */
    protected $phoneNumberUtil;

    /**
     * @param EntityManager   $entityManger
     * @param EntityManager   $legacyEntityManager
     * @param PhoneNumberUtil $phoneNumberUtil
     */
    public function __construct(EntityManager $entityManger, EntityManager $legacyEntityManager, PhoneNumberUtil $phoneNumberUtil)
    {
        $this->setEntityManager($entityManger);
        $this->setLegacyEntityManager($legacyEntityManager);
        $this->setPhoneNumberUtil($phoneNumberUtil);
    }

    /**
     * @param Province $province
     *
     * @return Region
     * @throws \Cwd\GenericBundle\Exception\PersistanceException
     */
    protected function findRegionByProvince(Province $province)
    {
        $rep = $this->entityManager->getRepository('Model:Region');
        try {
            $region = $rep->findOneBy(array('name' => $province->getName()));
            if ($region === null) {
                throw new EntityNotFoundException();
            }
        } catch (EntityNotFoundException $e) {
            $region = new Region();
            $region->setName($province->getName())
                ->setCountry(($province->getCountryId() == 40) ? 'AT' : 'DE');

            $this->entityManager->persist($region);
        }

        return $region;
    }

    /**
     * @param $value
     *
     * @return null|string
     */
    protected function getValueOrNull($value)
    {
        return (trim($value) == '') ? null : trim($value);
    }

    /**
     * @param $value
     *
     * @return null|string
     */
    protected function getValueOrEmpty($value)
    {
        return (trim($value) == '') ? '' : trim($value);
    }

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
     * @return mixed
     */
    public function getEntityManager()
    {
        return $this->entityManager;
    }

    /**
     * @param EntityManager $entityManager
     *
     * @return $this
     */
    public function setEntityManager($entityManager)
    {
        $this->entityManager = $entityManager;

        return $this;
    }

    /**
     * @return EntityManager
     */
    public function getLegacyEntityManager()
    {
        return $this->legacyEntityManager;
    }

    /**
     * @param EntityManager $legacyEntityManager
     *
     * @return $this
     */
    public function setLegacyEntityManager($legacyEntityManager)
    {
        $this->legacyEntityManager = $legacyEntityManager;

        return $this;
    }

    /**
     * @param PhoneNumberUtil $phoneNumberUtil
     *
     * @return $this
     */
    public function setPhoneNumberUtil(PhoneNumberUtil $phoneNumberUtil)
    {
        $this->phoneNumberUtil = $phoneNumberUtil;

        return $this;
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

    /**
     * starts the stopwatch and outputs the current time
     */
    protected function startImport()
    {
        $this->writeln('<comment>Starting import - '.date('Y-m-d H:i:s').'</comment>', OutputInterface::VERBOSITY_NORMAL);

        if ($this->output->getVerbosity() >= OutputInterface::VERBOSITY_VERBOSE) {
            $this->stopwatch = new Stopwatch();
            $this->stopwatch->start('import');
        }
    }

    /**
     * stops the stopwatch and outputs the current time and the duration
     */
    protected function stopImport()
    {
        $txtDuration = '';
        if ($this->output->getVerbosity() >= OutputInterface::VERBOSITY_VERBOSE) {
            $event = $this->stopwatch->stop('import');
            $duration = round($event->getDuration() / 1000);
            $unit = 'seconds';
            if ($duration > 60) {
                $duration = round($duration / 60, 2);
                $unit = 'minutes';
            }
            $txtDuration = sprintf('(%s %s)', $duration, $unit);
        }

        $output = sprintf('<comment>Ended import - %s %s</comment>', date('Y-m-d H:i:s'), $txtDuration);
        $this->writeln($output, OutputInterface::VERBOSITY_NORMAL);
    }

    /**
     * @param String $name district name
     *
     * @return District
     */
    protected function findDistrictByName($name)
    {
        $rep = $this->entityManager->getRepository('Model:District');
        $district = $rep->findOneBy(array('name' => $name));

        return $district;
    }

    /**
     * @param string      $input
     * @param null|string $domain
     * @param null|string $uid
     *
     * @return \libphonenumber\PhoneNumber|null
     */
    protected function phoneNumberParser($input, $domain = null, $uid = null)
    {
        if (trim($input) == '') {
            return null;
        }

        try {
            return $this->phoneNumberUtil->parse($input, strtoupper($domain));
        } catch (NumberParseException $e) {
            $this->writeln(sprintf('<error>UID %s: Invalid PhoneNumber: %s</error>', $uid, $input), OutputInterface::VERBOSITY_VERBOSE);
        }

        return null;
    }
}
