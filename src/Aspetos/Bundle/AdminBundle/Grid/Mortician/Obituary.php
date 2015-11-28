<?php
/*
 * This file is part of Aspetos
 *
 * (c)2014 Ludwig Ruderstaller <lr@cwd.at>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Aspetos\Bundle\AdminBundle\Grid\Mortician;

use Ali\DatatableBundle\Util\Datatable;
use Aspetos\Model\Entity\Mortician;
use Cwd\GenericBundle\Grid\Grid;
use Doctrine\ORM\Query\Expr\Join;
use JMS\DiExtraBundle\Annotation as DI;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * Class Obituary Grid for Morticians
 *
 * @package Aspetos\Bundle\AdminBundle\Grid\Supplier
 * @author  Ludwig Ruderstaller <lr@cwd.at>
 *
 * @DI\Service("aspetos.admin.grid.mortician.obituary")
 */
class Obituary extends Grid
{
    /**
     * @var TranslatorInterface
     */
    protected $translator;

    /**
     * @var Mortician
     */
    protected $mortician;

    /**
     * @param Datatable           $datatable
     * @param TranslatorInterface $translator
     *
     * @DI\InjectParams({
     *  "datatable" = @DI\Inject("datatable", strict = false),
     *  "translator" = @DI\Inject("translator", strict = false)
     * })
     */
    public function __construct(Datatable $datatable, TranslatorInterface $translator)
    {
        $this->setDatatable($datatable);
        $this->translator = $translator;
    }

    /**
     * @return Datatable
     */
    public function get()
    {
        $instance = $this;

        $datatable = $this->getDatatable()
            ->setEntity('Model:Obituary', 'x')
            ->setFields(
                array(
                    'ID'           => 'x.id as xid',
                    'Country'      => 'x.country',
                    'Firstname'    => 'x.firstname',
                    'Lastname'     => 'x.lastname',
                    'Gender'       => 'x.gender',
                    'Deathdate'    => 'x.dayOfDeath',
                    'District'     => 'd.name',
                    'Status'       => 'x.hide',
                    'Candles'      => 'x.id as cid',
                    'Condolences'  => 'x.id as coid',
                    '_identifier_' => 'x.id'
                )
            )
            ->addJoin('x.district', 'd', Join::LEFT_JOIN)
            ->setWhere('x.mortician = :mortician', array('mortician' => $this->getMortician()))
            ->setGroupBy('x.id')
            ->setOrder('x.dayOfDeath', 'desc')
            ->setSearchFields(array(0, 1, 2, 3, 4, 5, 6, 7))
            ->setRenderers(
                array(
                    1 => array(
                        'view' => 'AspetosAdminBundle:Grid:flag.html.twig'
                    ),
                    8 => array(
                        'view' => 'AspetosAdminBundle:Mortician/Obituary:gridActionCountCandle.html.twig'
                    ),
                    9 => array(
                        'view' => 'AspetosAdminBundle:Mortician/Obituary:gridActionCountCondolence.html.twig'
                    ),
                    10 => array(
                        'view' => 'AspetosAdminBundle:Mortician/Obituary:actions.html.twig',
                        'params' => array(
                            'candle_route'     => 'aspetos_admin_mortician_candle_list',
                            'condolence_route' => 'aspetos_admin_mortician_condolence_list',
                            'edit_route'       => 'aspetos_admin_mortician_obituary_edit',
                            //'delete_route'   => 'aspetos_admin_supplier_supplier_delete',
                            //'undelete_route' => 'aspetos_admin_supplier_supplier_undelete',
                        ),
                    ),
                )
            )

            ->setRenderer(
                function (&$data) use ($instance) {

                    foreach ($data as $key => $value) {
                        if ($key == 5) {
                            $data[$key] = $value->format('d.m.Y');
                        } elseif ($key == 7) {
                            $color = 'red-thunderbird';
                            $label = 'hidden';

                            if (!$value) {
                                $color = 'bg-green-jungle';
                                $label = 'active';
                            } else {
                                $color = 'bg-red-thunderbird';
                                $label = 'hidden';
                            }
                            $data[$key] = sprintf('<span class="label %s"> %s </span>', $color, $this->translator->trans($label));
                        } elseif ($value instanceof \Datetime) {
                            $data[$key] = $value->format('d.m.Y H:i:s');
                        }
                    }
                }
            )

            ->setHasAction(true)
            ->setSearch(true);

        return $datatable;
    }

    /**
     * @return Mortician
     */
    public function getMortician()
    {
        return $this->mortician;
    }

    /**
     * @param Mortician $mortician
     *
     * @return $this
     */
    public function setMortician($mortician)
    {
        $this->mortician = $mortician;

        return $this;
    }
}
