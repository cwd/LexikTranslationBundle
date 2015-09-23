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
use Doctrine\DBAL\Query\QueryBuilder;
use Doctrine\ORM\EntityManager;
use JMS\DiExtraBundle\Annotation as DI;

/**
 * Class BookEntry Grid
 *
 * @package Aspetos\Bundle\LegacyBundle\Grid
 * @author  Ludwig Ruderstaller <lr@cwd.at>
 *
 * @DI\Service("aspetos.legacy.grid.condolence")
 */
class Condolence extends Grid
{
    /**
     * @var int
     */
    public $bookId;

    /**
     * @var int
     */
    public $uid;

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
    }

    /**
     * @return Datatable
     */
    public function get()
    {
        $instance = $this;

        $datatable = $this->getDatatable()
            ->setEntity('Legacy:BookEntry', 'x')
            ->setFields(
                array(
                    'ID' => 'x.entryId as xid',
                    'Name' => 'x.name',
                    'Body' => 'x.body',
                    'Created' => 'x.datetime',
                    'Email'   => 'x.email',
                    'ActivateEmail' => 'x.activateEmail',
                    'Hide'    => 'x.hide',
                    '_identifier_'  => 'x.entryId'
                )
            )
            ->setOrder('x.datetime', 'desc')
            ->setSearchFields(array(0,1,2,3,4,5,6))
            ->setRenderers(
                array(
                    7 => array(
                        'view' => 'AspetosLegacyBundle:Candle:_actions.html.twig',
                        'params' => array(
                            'uid' => $this->uid,
                            //'view_route'     => 'aspetos_legacy_bookentry_detail',
                            'edit_route'     => 'aspetos_legacy_condolence_edit',
                            //'delete_route'   => 'aspetos_legacy_bookentry_delete',
                            //'undelete_route' => 'aspetos_legacy_bookentry_undelete',
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


                        if ($key == 6) {
                            $checked = $value == 1 ? 'checked' : '';
                            $data[$key] = '<span style="display:none">'.$value.'</span><input type="checkbox" class="make-switch" data-on-text="Ja" data-off-text="Nein" name="mark-winner" value="1" data-off-color="success" data-on-color="danger" '.$checked.' data-id="'.$data[0].'">';
                        }
                    }
                }
            )

            ->setHasAction(true)
            ->setSearch(true);

        return $datatable;
    }
}
