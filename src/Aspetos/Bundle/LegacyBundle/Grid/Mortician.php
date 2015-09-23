<?php
/*
 * This file is part of Aspetos
 *
 * (c)2014 Ludwig Ruderstaller <lr@cwd.at>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Aspetos\Bundle\LegacyBundle\Grid;

use Ali\DatatableBundle\Util\Datatable;
use Cwd\GenericBundle\Grid\Grid;
use Doctrine\ORM\EntityManager;
use JMS\DiExtraBundle\Annotation as DI;

/**
 * Class Mortician Grid
 *
 * @package Aspetos\Bundle\LegacyBundle\Grid
 * @author  Ludwig Ruderstaller <lr@cwd.at>
 *
 * @DI\Service("aspetos.legacy.grid.mortician")
 */
class Mortician extends Grid
{
    /**
     * @param Datatable     $datatable
     * @param EntityManager $legacyEntityManager
     *
     * @DI\InjectParams({
     *  "datatable" = @DI\Inject("datatable", strict = false),
     *  "legacyEntityManager" = @DI\Inject("doctrine.orm.legacy_entity_manager")
     * })
     */
    public function __construct(Datatable $datatable,  EntityManager $legacyEntityManager)
    {
        $this->setDatatable($datatable);
        $this->getDatatable()->setEntityManager($legacyEntityManager);

        return $this->get();
    }

    /**
     * @return Datatable
     */
    public function get()
    {
        $instance = $this;

        $datatable = $this->getDatatable();

        $datatable
            ->setEntity('Legacy:User', 'x')
            ->addJoin('x.province', 'p')
            ->setWhere('x.userCategory = :type', array('type' => 'mortician'))
            ->setFields(
                array(
                    'UID' => 'x.uid as xid',
                    'Country' => 'x.domain',
                    'Name' => 'x.name',
                    'Zip'  => 'x.zip',
                    'Place' => 'x.place',
                    'Province' => 'p.name',
                    'Partner' => 'x.partnerWienerVerein',
                    'Last Login' => 'x.lastvisitDate',
                    'Created'   => 'x.registerDate',
                    'Block'     => 'x.block',
                    '_identifier_'  => 'x.uid'
                )
            )
            ->setOrder('x.uid', 'desc')
            ->setSearchFields(array(0,1,2,3,4,5,6,7,8,9))
            ->setRenderers(
                array(
                    10 => array(
                        'view' => 'CwdAdminMetronicBundle:Grid:_actions.html.twig',
                        'params' => array(
                            'view_route'     => 'aspetos_legacy_mortician_detail',
                            'edit_route'     => 'aspetos_legacy_mortician_edit',
                            //'delete_route'   => 'aspetos_legacy_mortician_delete',
                            //'undelete_route' => 'aspetos_legacy_mortician_undelete',
                        ),
                    ),
                )
            )

            ->setRenderer(
                function (&$data) use ($instance)
                {
                    foreach ($data as $key => $value) {
                        if ($value instanceof \Datetime) {
                            $data[$key] = $value->format('Y-m-d H:i:s');
                        }

                        if ($key == 1) {
                            $data[$key] = sprintf('<img alt="%s" src="/bundles/aspetoslegacy/images/flags/%s.png" />', $value, $value);
                        } elseif ($key == 9) {
                            $checked = $value == 1 ? 'checked' : '';
                            $data[$key] = '<span style="display:none">'.$value.'</span><input type="checkbox" class="make-switch" data-on-text="Ja" data-off-text="Nein" name="mark-winner" value="1" data-off-color="success" data-on-color="danger" '.$checked.' data-id="'.$data[0].'">';
                        } elseif ($key == 6) {
                            if ($value) {
                                $data[$key] = '<span style="display:none">'.$value.'</span><label class="fa fa-check-square-o"></label>';
                            } else {
                                $data[$key] = '<span style="display:none">'.$value.'</span><label class="fa fa-square-o"></label>';
                            }
                        }
                    }
                }
            )

            ->setHasAction(true)
            ->setSearch(true);

        return $datatable;
    }
}
