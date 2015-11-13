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

use Aspetos\Bundle\LegacyBundle\Model\Entity\Book;
use Aspetos\Bundle\LegacyBundle\Model\Entity\BookEntry;
use Aspetos\Bundle\LegacyBundle\Model\Entity\BookEntryType;
use Aspetos\Model\Entity\Candle;
use Aspetos\Model\Entity\Obituary;
use Aspetos\Model\Entity\Product;
use Aspetos\Model\Entity\ProductAvailability;
use Aspetos\Model\Entity\ProductCategory;
use Aspetos\Model\Entity\ProductHasCategory;
use Aspetos\Service\Exception\CemeteryAdministrationNotFoundException;
use Aspetos\Service\Legacy\BookEntryService;
use Aspetos\Service\Obituary\CandleService;
use Aspetos\Service\ObituaryService;
use Doctrine\ORM\EntityNotFoundException;
use Aspetos\Bundle\LegacyBundle\Model\Entity\Province;
use Aspetos\Bundle\LegacyBundle\Model\Entity\Cemetery as CemeteryLegacy;
use Aspetos\Model\Entity\Cemetery;
use Aspetos\Model\Entity\CemeteryAddress;
use Aspetos\Model\Entity\CemeteryAdministration;
use Aspetos\Service\Legacy\ObituaryService as ObituaryServiceLegacy;
use Aspetos\Service\Legacy\BookEntryService as BookEntryServiceLegacy;
use Aspetos\Service\CemeteryService;
use JMS\DiExtraBundle\Annotation as DI;
use libphonenumber\PhoneNumberUtil;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class CandleImporter
 *
 * @package Aspetos\Service\Legacy\Import
 * @author  Ludwig Ruderstaller <lr@cwd.at>
 *
 * @DI\Service("aspetos.service.legacy.import.bookentry", parent="aspetos.service.legacy.import.base")
 */
class BookEntryImporter extends BaseImporter
{
    /**
     * @var CemeteryServiceLegacy
     */
    protected $legacyObituaryService;

    /**
     * @var CandleService
     */
    protected $candleService;

    /**
     * @var BookEntryService
     */
    protected $legacyBookEntryService;

    /**
     * @var ObituaryService
     */
    protected $obituaryService;

    /**
     * @param ObituaryServiceLegacy  $obituaryServiceLegacy
     * @param BookEntryServiceLegacy $bookEntryServiceLegacy
     * @param CandleService          $candleService
     * @param PhoneNumberUtil        $phoneNumberUtil
     *
     * @DI\InjectParams({
     *     "obituaryServiceLegacy" = @DI\Inject("aspetos.service.legacy.obituary"),
     *     "bookEntryServiceLegacy" = @DI\Inject("aspetos.service.legacy.bookentry"),
     *     "candleService" = @DI\Inject("aspetos.service.obituary.candle"),
     *     "obituaryService" = @DI\Inject("aspetos.service.obituary"),
     *     "phoneNumberUtil"  = @DI\Inject("libphonenumber.phone_number_util")
     *
     * })
     */
    public function __construct(
        ObituaryServiceLegacy $obituaryServiceLegacy,
        BookEntryService $bookEntryServiceLegacy,
        CandleService $candleService,
        ObituaryService $obituaryService,
        PhoneNumberUtil $phoneNumberUtil
    )
    {
        $this->legacyObituaryService = $obituaryServiceLegacy;
        $this->candleService = $candleService;
        $this->obituaryService = $obituaryService;
        $this->legacyBookEntryService = $bookEntryServiceLegacy;

        parent::__construct($candleService->getEm(), $obituaryServiceLegacy->getEm(), $phoneNumberUtil);
    }

    /**
     * @param InputInterface $input
     */
    public function runCandles(InputInterface $input)
    {
        if ($input->getOption('types')) {
            $this->importEntryTypes();
        }

        $obituaries = $this->legacyObituaryService->findAll(1000000, $input->getOption('offset', 0), true);
        $count = count($obituaries);
        $loopcounter = 0;
        $totalCandles = 0;

        foreach ($obituaries as $obituary) {
            ++$loopcounter;
            $book = $this->legacyBookEntryService->findBookForObituary($obituary['uid'], 'candle');
            $obituary = $this->obituaryService->findByUid($obituary['uid']);
            if ($obituary == null) {
                continue;
            }
            $entries = $this->findEntriesByBook($book, $obituary);

            $c = 0;

            /** @var BookEntry $entry */
            foreach ($entries as $entry) {
                ++$c;
                if ($entry->getType() == null) {
                    continue;
                }
                $product  = $this->getEntityManager()->getRepository('Model:Product')->findOneBy(array('origId' => $entry->getType()->getTypeId()));

                $candle = $this->findEntryOrNew($entry, $obituary);
                $candle->setContent($entry->getName())
                       ->setExpiresAt($entry->getExpireDate())
                       ->setProduct($product)
                       ->setState(!$entry->getHide());
            }
            $totalCandles += $c;

            $this->writeln('<comment>'.$loopcounter.'</comment>/'.$count.' Added <info>'.$c.'</info> to Obituary '.$obituary->getId() .'('.$obituary->getOrigId().')', OutputInterface::VERBOSITY_NORMAL);

            unset($entries);
            if (($loopcounter % 200) == 0) {
                $this->getEntityManager()->flush();
                $this->getEntityManager()->clear();
                gc_collect_cycles();
                $this->writeln('<comment>Total Candles: '.$totalCandles.'</comment>', OutputInterface::VERBOSITY_NORMAL);
            }

        }

        $this->getEntityManager()->flush();
        $this->stopImport();
    }

    protected function findEntryOrNew(BookEntry $entry, Obituary $obituary)
    {
        try {
            $candle = $this->candleService->findOneByFilter('Model:Candle', array('origId' => $entry->getEntryId()));
            if ($candle === null) {
                throw new \Exception('not found');
            }
        } catch (\Exception $e) {
            $candle = new Candle();
            $candle->setOrigId($entry->getEntryId())
                   ->setObituary($obituary);
            $this->getEntityManager()->persist($candle);
        }

        return $candle;
    }

    protected function importEntryTypes()
    {
        $productCategory = $this->getEntityManager()->getReference('Model:ProductCategory', 54);
        $types = $this->getLegacyEntityManager()->getRepository('Legacy:BookEntryType')->findBy(array(), array('typeId' => 'ASC'), 1000);

        /** @var BookEntryType $type */
        foreach ($types as $type) {
            $product = $this->findProductOrNew($type, $productCategory);
            $product->setName($type->getTitle())
                    ->setDescription($type->getDescription())
                    ->setState(!$type->getHide())
                    ->setLifeTime($type->getMaxLifetime());
        }

        $this->getEntityManager()->flush();
    }

    /**
     * @param BookEntryType   $type
     * @param ProductCategory $productCategory
     *
     * @return Product
     */
    protected function findProductOrNew(BookEntryType $type, ProductCategory $productCategory)
    {
        try {
            $lType = $this->getEntityManager()->getRepository('Model:ProductCategory')->findOneBy(array('origId' => $type->getTypeId()));
            if ($lType == null) {
                throw new \Exception('not found');
            }
        } catch (\Exception $e) {
            $product = new Product();
            $product->setOrigId($type->getTypeId())
                    ->setSellPrice(0)
                    ->setName($type->getTitle())
                    ->setState(!$type->getHide());
            $this->getEntityManager()->persist($product);

            $phc = new ProductHasCategory();
            $phc->setProductCategory($productCategory)
                ->setProduct($product);
            $this->getEntityManager()->persist($productCategory);
            $product->addProductHasCategory($phc);

            $avail = new ProductAvailability();
            $avail->setCountry(strtoupper($type->getDomain()))
                  ->setState(!$type->getHide())
                  ->setProduct($product);
            $this->getEntityManager()->persist($avail);
            $product->addProductAvailability($avail);

            $this->getEntityManager()->flush();
        }

        return $product;
    }

    /**
     * @param Book $book
     *
     * @return array
     */
    protected function findEntriesByBook($book)
    {
        return $this->getLegacyEntityManager()->getRepository('Legacy:BookEntry')->findBy(array('bookId' => $book));
    }

}
