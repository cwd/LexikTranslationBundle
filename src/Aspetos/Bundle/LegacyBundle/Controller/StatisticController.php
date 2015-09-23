<?php
/*
 * This file is part of AspetosLegacyBundle
 *
 * (c)2014 Ludwig Ruderstaller <lr@cwd.at>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/
namespace Aspetos\Bundle\LegacyBundle\Controller;

use Aspetos\Bundle\LegacyBundle\Model\Entity\BookEntry;
use Aspetos\Bundle\LegacyBundle\Model\Entity\StatisticAggUserMortician;
use Aspetos\Service\Exception\BookEntryNotFoundException;
use Aspetos\Service\Legacy\BookEntryService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use JMS\SecurityExtraBundle\Annotation\PreAuthorize;
use JMS\SecurityExtraBundle\Annotation\Secure;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Cwd\GenericBundle\Controller\GenericController as CwdController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Constraints\Url;

/**
 * Class Statistic Controller
 *
 * @package AspetosLegacyBundle\Bundle\AdminBundle\Controller
 * @author  Ludwig Ruderstaller <lr@cwd.at>
 *
 * @PreAuthorize("hasRole('ROLE_ADMIN')")
 * @Route("/statistic")
 */
class StatisticController extends CwdController
{

    /**
     * @return array
     * @Template()
     * @Route("/")
     */
    public function indexAction()
    {
        return array();
    }

    /**
     * @param Request $request
     * @Route("/export")
     * @return Response
     */
    public function exportAction(Request $request)
    {
        $month = $request->get('month', date('n'));
        $year  = $request->get('year', date('Y'));

        if ($month == 1 && $year <= date('Y')) {
            $previousMonth = 12;
            $previousYear  = $year-1;
        } else {
            $previousMonth = $month-1;
            $previousYear  = $year;
        }

        if ($month == 12 && $year <= date('Y')) {
            $nextMonth = 1;
            $nextYear  = $year+1;
        } else {
            $nextMonth = $month+1;
            $nextYear  = $year;
        }

        $data = $this->get('aspetos.service.legacy.statistic')->getTop($request->get('order'), 'DESC', 0, 100, $year, 'month'.$month);

        $file = $this->getExcelFile($data);
        ob_start();
        $file->save('php://output');
        $excel = ob_get_contents();
        ob_end_clean();

        $response = new Response();
        $response->headers->set('Cache-Control', 'private');
        $response->headers->set('Content-type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $response->headers->set('Content-Disposition', 'attachment; filename="top-mortician.xlsx";');
        //$response->headers->set('Content-length');
        $response->sendHeaders();
        $response->setContent($excel);

        return $response;
    }

    /**
     * @param $data
     *
     * @return \PHPExcel_Writer_IWriter
     * @throws \PHPExcel_Exception
     * @throws \PHPExcel_Reader_Exception
     */
    protected function getExcelFile($data)
    {
        $excel = new \PHPExcel();

        $excel->setActiveSheetIndex(0);
        $sheet = $excel->getActiveSheet();

        $style = new \PHPExcel_Style();
        $style->applyFromArray(array(
            'fill' => array(
                'type' => \PHPExcel_Style_Fill::FILL_SOLID,
                'color' => array('argb' => 'FFCCCCCC')
                ),
            'font'  => array(
                'bold'  => true
            )
        ));
        $sheet->duplicateStyle($style, 'A1:F1');
        $sheet->getColumnDimension('A')->setWidth(60);
        $sheet->getColumnDimension('B')->setWidth(20);
        $sheet->getColumnDimension('C')->setWidth(20);
        $sheet->getColumnDimension('D')->setWidth(20);
        $sheet->getColumnDimension('E')->setWidth(20);
        $sheet->getColumnDimension('F')->setWidth(20);



        $row = 1;
        $sheet->setCellValueByColumnAndRow(0, $row, 'Mortician');
        $sheet->setCellValueByColumnAndRow(1, $row, 'Parten');
        $sheet->setCellValueByColumnAndRow(2, $row, 'Gratis Kerzen');
        $sheet->setCellValueByColumnAndRow(3, $row, 'Bezahlte Kerzen');
        $sheet->setCellValueByColumnAndRow(4, $row, 'Kondulenzen');
        $sheet->setCellValueByColumnAndRow(5, $row, 'Ansichten');
        $row++;


        foreach ($data as $stat) {
            /** @var StatisticAggUserMortician $stat */
            $sheet->setCellValueByColumnAndRow(0, $row, $stat->getUser()->getName());
            $sheet->setCellValueByColumnAndRow(1, $row, $stat->getQuantityDeadUser());
            $sheet->setCellValueByColumnAndRow(2, $row, $stat->getQuantityCandle());
            $sheet->setCellValueByColumnAndRow(3, $row, $stat->getQuantityCandleOrder());
            $sheet->setCellValueByColumnAndRow(4, $row, $stat->getQuantityCondolence());
            $sheet->setCellValueByColumnAndRow(5, $row, $stat->getQuantityViewDetail());
            $row++;
        }

        $writer =  \PHPExcel_IOFactory::createWriter($excel, 'Excel2007');

        return $writer;
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @Route("/gettop")
     */
    public function getTopAction(Request $request)
    {
        $month = $request->get('month', date('n'));
        $year  = $request->get('year', date('Y'));

        $currentMonth = $month;
        $currentYear  = $year;

        if ($month == 1 && $year <= date('Y')) {
            $previousMonth = 12;
            $previousYear  = $year-1;
        } else {
            $previousMonth = $month-1;
            $previousYear  = $year;
        }

        if ($month == 12 && $year <= date('Y')) {
            $nextMonth = 1;
            $nextYear  = $year+1;
        } else {
            $nextMonth = $month+1;
            $nextYear  = $year;
        }

        $data = $this->get('aspetos.service.legacy.statistic')->getTop($request->get('order'), 'DESC', 0, 20, $year, 'month'.$month);
        $result = $this->renderView('AspetosLegacyBundle:Statistic:getTop.html.twig', array('top' => $data));

        setlocale(LC_ALL, 'de_AT@euro', 'de_AT.utf8', 'de_DE.utf8', 'de_DE', 'de');

        return new JsonResponse(array(
            'data'          => $result,
            'currentMonth'  => $currentMonth,
            'currentYear'   => $currentYear,
            'current'       => strftime('%B %Y', strtotime($currentYear.'-'.$currentMonth.'-1')),
            'nextMonth'     => $nextMonth,
            'nextYear'      => $nextYear,
            'previousMonth' => $previousMonth,
            'previousYear'  => $previousYear
        ));
    }

    /**
     * @param Request $request
     * @Route("/data/{type}/{country}")
     *
     * @return JsonResponse
     */
    public function getDataAction(Request $request)
    {
        $group = $request->get('groupby', 'month');
        $nameMap = array(
            'quantityCandle' => array('name' => 'free candles', 'color' => '#45B6AF'),
            'quantityCandleOrder' => array('name' => 'payed candles', 'color' => '#c23f44'),
            'quantityCondolence' => array('name' => 'condolences', 'color' => '#3598dc'),
            'quantityViewDetail' => array('name' => 'views', 'color' => '#7a518c')
        );

        $countryMap = array(
            'at' => 'Austria',
            'de' => 'Germany',
            'all' => 'Total'
        );

        $label = $countryMap[$request->get('country')];
        $color = $this->getCountryColor($request->get('country'));

        switch ($request->get('type')) {
            case 'obituary':
                $data = $this->get('aspetos.service.legacy.obituary')->getStatistic($request->get('country'), $group);
                break;
            case 'mortician':
                $data = $this->get('aspetos.service.legacy.mortician')->getStatistic($request->get('country'), $group);
                break;
            default:
                $data = $this->get('aspetos.service.legacy.statistic')->getData($request->get('type'), $request->get('country'));
        }

        $return = array('label' => $this->get('translator')->trans($label), 'color' => $color);
        foreach ($data as $value) {
            switch ($group) {
                case 'day':
                    $date = new \DateTime($value['signup']);
                    break;
                case 'quarter':
                    $date = new \DateTime($value['year'].'-'.$value['month'].'-01');
                    break;
                default:
                case 'month':
                    $date = new \DateTime($value['year'].'-'.$value['month'].'-01');
                    break;
            }
            $return['data'][]=array($date->getTimestamp()*1000, $value['count']);
        }

        return new JsonResponse($return);
    }

    protected function getCountryColor($country)
    {
        $colors = array('at' => '#d12610', 'de' => '#37b7f3');

        if (isset($colors[$country])) {
            return $colors[$country];
        }

        return '#52e136';
    }

    /**
     * @param string $service
     *
     * @return BookEntryService
     */
    protected function getService($service = 'legacy.bookentry')
    {
        return $this->get('aspetos.service.'.$service);
    }
}
