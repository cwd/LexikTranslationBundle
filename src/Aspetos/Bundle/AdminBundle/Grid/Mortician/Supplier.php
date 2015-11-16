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
use Cwd\GenericBundle\Grid\Grid;
use Doctrine\ORM\Query\Expr\Join;
use JMS\DiExtraBundle\Annotation as DI;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * Class Supplier Grid for Morticians
 *
 * @package Aspetos\Bundle\AdminBundle\Grid\Supplier
 * @author  Ludwig Ruderstaller <lr@cwd.at>
 *
 * @DI\Service("aspetos.admin.grid.mortician.supplier")
 */
class Supplier extends Grid
{
    /**
     * @var TranslatorInterface
     */
    protected $translator;

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

        return $this->get();
    }

    /**
     * @return Datatable
     */
    public function get()
    {
        $instance = $this;

        $datatable = $this->getDatatable()
            ->setEntity('Model:Supplier', 'x')
            ->setFields(
                array(
                    'ID'           => 'x.id as xid',
                    'Country'      => 'x.country',
                    'Name'         => 'x.name',
                    'Region'       => 'r.name',
                    'City'         => 'a.city',
                    '_identifier_' => 'x.id'
                )
            )
            ->addJoin('x.address', 'a', Join::LEFT_JOIN)
            ->addJoin('a.region', 'r', Join::LEFT_JOIN)
            ->addJoin('x.parentSupplier', 'pm', Join::LEFT_JOIN)
            ->setWhere('x.state = :state', array('state' => 'active'))
            ->setOrder('x.name', 'asc')
            ->setSearchFields(array(0,1,2,3,4,5))
            ->setRenderers(
                array(
                    1 => array(
                        'view' => 'AspetosAdminBundle:Grid:flag.html.twig'
                    ),
                    5 => array(
                        'view' => 'CwdAdminMetronicBundle:Grid:_actions.html.twig',
                        'params' => array(
                            //'view_route'     => 'aspetos_admin_supplier_supplier_detail',
                            //'edit_route'     => 'aspetos_admin_supplier_supplier_edit',
                            //'delete_route'   => 'aspetos_admin_supplier_supplier_delete',
                            //'undelete_route' => 'aspetos_admin_supplier_supplier_undelete',
                        ),
                    ),
                )
            )

            ->setRenderer(
                function (&$data) use ($instance) {

                    foreach ($data as $key => $value) {
                        if ($value instanceof \Datetime) {
                            $data[$key] = $value->format('d.m.Y H:i:s');
                        }
                    }
                }
            )

            ->setHasAction(false)
            ->setSearch(true);

        return $datatable;
    }
}
