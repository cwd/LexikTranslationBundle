<?php
/*
 * This file is part of Aspetos
 *
 * (c)2014 Ludwig Ruderstaller <lr@cwd.at>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Aspetos\Bundle\AdminBundle\Grid;

use Ali\DatatableBundle\Util\Datatable;
use Cwd\GenericBundle\Grid\Grid;
use Doctrine\ORM\EntityManager;
use JMS\DiExtraBundle\Annotation as DI;

/**
 * Class Cemetery Grid
 *
 * @package Aspetos\Bundle\AdminBundle\Grid
 * @author  Ludwig Ruderstaller <lr@cwd.at>
 *
 * @DI\Service("aspetos.admin.grid.cemetery")
 */
class Cemetery extends Grid
{

    /**
     * @param Datatable $datatable
     *
     * @DI\InjectParams({
     *  "datatable" = @DI\Inject("datatable", strict = false)
     * })
     */
    public function __construct(Datatable $datatable)
    {
        $this->setDatatable($datatable);

        return $this->get();
    }

    /**
     * @return Datatable
     */
    public function get()
    {
        $instance = $this;

        $datatable = $this->getDatatable()
            ->setEntity('Model:Cemetery', 'x')
            ->setFields(
                array(
                    'ID' => 'x.id as xid',
                    'Name' => 'x.name',
                    'Slug' => 'x.slug',
                    'Owner' => 'x.ownerName',
                    '_identifier_'  => 'x.id'
                )
            )
            ->setOrder('x.name', 'asc')
            ->setSearchFields(array(0,1,2,3))
            ->setRenderers(
                array(
                    4 => array(
                        'view' => 'CwdAdminMetronicBundle:Grid:_actions.html.twig',
                        'params' => array(
                            'view_route'     => 'aspetos_admin_cemetery_detail',
                            'edit_route'     => 'aspetos_admin_cemetery_edit',
                            'delete_route'   => 'aspetos_admin_cemetery_delete',
                            //'undelete_route' => 'aspetos_admin_cemetery_undelete',
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

            ->setHasAction(true)
            ->setSearch(true);

        return $datatable;
    }
}
