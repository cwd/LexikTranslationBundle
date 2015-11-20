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
use Aspetos\Model\Entity\Condolence;
use Aspetos\Model\Entity\Obituary;
use Aspetos\Model\Entity\Product;
use Aspetos\Model\Entity\ProductAvailability;
use Aspetos\Model\Entity\ProductCategory;
use Aspetos\Model\Entity\ProductHasCategory;
use Aspetos\Model\Entity\Reminder;
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
use Doctrine\ORM\Query;
use Hype\MailchimpBundle\Mailchimp\MailChimp;
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
 * @DI\Service("aspetos.service.legacy.import.reminder", parent="aspetos.service.legacy.import.base")
 */
class ReminderImporter extends BaseImporter
{
    /**
     * @var ObituaryServiceLegacy
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
     * @var Mailchimp
     */
    protected $mailchimp;

    /**
     * @param ObituaryServiceLegacy  $obituaryServiceLegacy
     * @param BookEntryServiceLegacy $bookEntryServiceLegacy
     * @param CandleService          $candleService
     * @param ObituaryService        $obituaryService
     * @param PhoneNumberUtil        $phoneNumberUtil
     * @param Mailchimp              $mailchimp
     *
     * @DI\InjectParams({
     *     "obituaryServiceLegacy" = @DI\Inject("aspetos.service.legacy.obituary"),
     *     "bookEntryServiceLegacy" = @DI\Inject("aspetos.service.legacy.bookentry"),
     *     "candleService" = @DI\Inject("aspetos.service.obituary.candle"),
     *     "obituaryService" = @DI\Inject("aspetos.service.obituary"),
     *     "phoneNumberUtil"  = @DI\Inject("libphonenumber.phone_number_util"),
     *     "mailchimp" = @DI\Inject("hype_mailchimp")
     *
     * })
     */
    public function __construct(
        ObituaryServiceLegacy $obituaryServiceLegacy,
        BookEntryService $bookEntryServiceLegacy,
        CandleService $candleService,
        ObituaryService $obituaryService,
        PhoneNumberUtil $phoneNumberUtil,
        MailChimp $mailchimp
    )
    {
        $this->legacyObituaryService = $obituaryServiceLegacy;
        $this->candleService = $candleService;
        $this->obituaryService = $obituaryService;
        $this->legacyBookEntryService = $bookEntryServiceLegacy;
        $this->mailchimp = $mailchimp;

        parent::__construct($candleService->getEm(), $obituaryServiceLegacy->getEm(), $phoneNumberUtil);
    }


    /**
     * @param InputInterface $input
     */
    public function run(InputInterface $input)
    {
        $obituaries = $this->legacyObituaryService->findAll(1000000, $input->getOption('offset', 0), true);
        $count = count($obituaries);
        $loopcounter = 0;
        $totalCandles = 0;

        foreach ($obituaries as $obituary) {
            ++$loopcounter;
            $books = $this->legacyBookEntryService->findBooksForObituary($obituary['uid'], 'condolence');
            if (count($books) == 0) {
                continue;
            }

            $obituary = $this->obituaryService->findByUid($obituary['uid']);

            if ($obituary == null) {
                continue;
            }
            $entries = $this->findByBooks($books, $obituary);
            $c = 0;

            /** @var BookEntry $entry */
            foreach ($entries as $entry) {
                ++$c;
                if ($entry['anniversaryReminder'] == 1 && filter_var($entry['email'], FILTER_VALIDATE_EMAIL)) {
                    $anniversary = $obituary->getDayOfDeath();
                    if ($anniversary instanceof \DateTime) {
                        $month = $anniversary->format('n');
                        $day = $anniversary->format('d');
                        if ($month >= 11) {
                            $year = 2015;
                        } else {
                            $year = 2016;
                        }

                        $remindAt = new \DateTime(sprintf('%s-%s-%s', $year, $month, $day));

                        $reminder = $this->findReminderOrNew($entry['email'], $obituary);
                        $reminder->setEmail($entry['email'])
                                 ->setOrigId($entry['entryId'])
                                 ->setState('active')
                                 ->setType('anniversary')
                                 ->setRemindAt($remindAt);
                        $this->getEntityManager()->flush();
                    }
                }

                if ($entry['infoservice'] == 1 && filter_var($entry['email'], FILTER_VALIDATE_EMAIL)) {
                    /*
                    $this->mailchimp->getList()
                        ->addMerge_vars(array(
                            'groupings' => array(
                                array(
                                    'id' => 1013,
                                    'groups' => array('user')
                                ),
                                array(
                                    'id' => 1017,
                                    'groups' => array(strtoupper($obituary->getCountry()))
                                )
                            )
                        ))
                        ->subscribe($entry['email'], 'html', false, true, true, false);
                    */
                }
            }
            $totalCandles += $c;

            $this->writeln('<comment>'.$loopcounter.'</comment>/'.$count.' Added <info>'.$c.'</info> to Obituary '.$obituary->getId() .' ('.$obituary->getOrigId().')', OutputInterface::VERBOSITY_NORMAL);

            unset($entries);
            unset($obituary);
            unset($book);
            if (($loopcounter % 30) == 0) {
                $this->getEntityManager()->clear();
                gc_collect_cycles();
                $this->writeln('<comment>Total Reminders: '.$totalCandles.'</comment>', OutputInterface::VERBOSITY_NORMAL);
            }

        }

        //$this->getEntityManager()->flush();
    }

    /**
     * @param          $entry
     * @param Obituary $obituary
     *
     * @return Reminder
     */
    protected function findReminderOrNew($entry, Obituary $obituary)
    {
        try {
            $candle = $this->candleService->findOneByFilter('Model:Reminder', array('email' => $entry, 'obituary' => $obituary));
            if ($candle === null) {
                throw new \Exception('not found');
            }
        } catch (\Exception $e) {
            $candle = new Reminder();
            $candle->setObituary($obituary);
            $this->getEntityManager()->persist($candle);
        }

        return $candle;
    }

    /**
     *
     * @param array $book
     *
     * @return array
     */
    protected function findByBooks($books)
    {
        $qb = $this->getLegacyEntityManager()->getRepository('Legacy:BookEntry')->createQueryBuilder('e');
        $qb->select('e', 't')
           ->join('e.type', 't', Query\Expr\Join::LEFT_JOIN)
           ->where('e.bookId IN (:book)')
           ->andWhere('e.hide = 0')
           ->setParameter('book', $books);

        return $qb->getQuery()->getResult(Query::HYDRATE_ARRAY);
        //return $this->getLegacyEntityManager()->getRepository('Legacy:BookEntry')->findBy(array('bookId' => $book));
    }
}
