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
use Doctrine\ORM\EntityManager;
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
     * @var EntityManager
     */
    protected $entityManager;

    /**
     * @var EntityManager
     */
    protected $legacyEntityManager;

    /**
     * @param EntityManager $entityManger
     * @param EntityManager $legacyEntityManager
     */
    public function __construct(EntityManager $entityManger, EntityManager $legacyEntityManager)
    {
        $this->setEntityManager($entityManger);
        $this->setLegacyEntityManager($legacyEntityManager);
    }


    /**
     * @param Province $province
     *
     * @return Region
     * @throws \Cwd\GenericBundle\Exception\PersistanceException
     */
    protected function findRegionByProvince(Province $province)
    {
        $rep = $this->morticianService->getEm()->getRepository('Model:Region');
        try {
            $region = $rep->findOneBy(array('name' => $province->getName()));
            if ($region === null) {
                throw new EntityNotFoundException();
            }
        } catch (EntityNotFoundException $e) {
            $region = new Region();
            $region->setName($province->getName())
                ->setCountry(($province->getCountryId() == 40) ? 'AT' : 'DE');

            $this->morticianService->persist($region);
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
