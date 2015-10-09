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

use Aspetos\Bundle\LegacyBundle\Model\Entity\User;
use Aspetos\Model\Entity\Mortician;
use Aspetos\Service\Legacy\MorticianService as MorticianServiceLegacy;
use Aspetos\Service\MorticianService;
use Doctrine\ORM\EntityManager;
use JMS\DiExtraBundle\Annotation as DI;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class MorticianImporter
 *
 * @package Aspetos\Service\Legacy\Import
 * @author  Ludwig Ruderstaller <lr@cwd.at>
 *
 * @DI\Service("aspetos.service.legacy.import.mortician", parent="aspetos.service.legacy.import.base")
 */
class MorticianImporter extends BaseImporter
{
    /**
     * @var MorticianServiceLegacy
     */
    protected $legacyMorticianService;

    /**
     * @var MorticianService
     */
    protected $morticianService;

    /**
     * @param MorticianServiceLegacy $morticianServiceLegacy
     * @param MorticianService       $morticianService
     *
     * @DI\InjectParams({
     *     "morticianServiceLegacy" = @DI\Inject("aspetos.service.legacy.mortician"),
     *     "morticianService" = @DI\Inject("aspetos.service.mortician")
     * })
     */
    public function __construct(MorticianServiceLegacy $morticianServiceLegacy, MorticianService $morticianService)
    {
        $this->legacyMorticianService = $morticianServiceLegacy;
        $this->morticianService = $morticianService;
    }

    /**
     * Import!
     */
    public function run()
    {
        $this->writeln('<comment>Starting import - '.date('Y-m-d H:i:s').'</comment>', OutputInterface::VERBOSITY_NORMAL);

        $morticians = $this->legacyMorticianService->findAll(2);

        $this->writeln(sprintf('<info>%s</info> Morticians to import', count($morticians)), OutputInterface::VERBOSITY_NORMAL);

        foreach ($morticians as $mortician) {
            $this->createMortician($mortician);
        }
    }

    protected function createMortician(User $mortician)
    {
        $mortObject = new Mortician();
        $mortObject->setName($mortician->getName())
                   ->setCommercialRegNumber($mortician->getCommercialRegNumber())
                   ->setVat($mortician->getVatNumber())
                   ->setDescription($mortician->getDescription());
    }
}
