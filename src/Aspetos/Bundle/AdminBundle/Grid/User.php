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
 * Class User Grid
 *
 * @package Aspetos\Bundle\AdminBundle\Grid
 * @author  Ludwig Ruderstaller <lr@cwd.at>
 *
 * @DI\Service("aspetos.admin.grid.user")
 */
class User extends Grid
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
        $datatable = $this->getDatatable()
            ->setEntity('Model:BaseUser', 'x')
            ->setFields(
                array(
                    'ID' => 'x.id as xid',
                    'Firstname' => 'x.firstname',
                    'Lastname' => 'x.lastname',
                    'Email' => 'x.email',
                    'Type'  => 'x.type',
                    'Created' => 'x.createdAt',
                    '_identifier_'  => 'x.id'
                )
            )
            ->setOrder('x.lastname', 'asc')
            ->setSearchFields(array(0,1,2,3))
            ->setRenderers(
                array(
                    6 => array(
                        'view' => 'AspetosAdminBundle:User:User/actions.html.twig',
                        'params' => array(
                            'view_route'     => 'aspetos_admin_user_%s_detail',
                            'edit_route'     => 'aspetos_admin_user_%s_edit',
                            'delete_route'   => 'aspetos_admin_user_%s_delete',
                            //'undelete_route' => 'aspetos_admin_user_undelete',
                        ),
                    ),
                )
            )

            ->setRenderer(
                function (&$data) {

                    foreach ($data as $key => $value) {
                        if ($value instanceof \Datetime) {
                            $data[$key] = $value->format('d.m.Y H:i:s');
                        }

                        if ($key == 4) {
                            $data[$key] = $this->badgeByName($value);
                        }
                    }
                }
            )

            ->setHasAction(true)
            ->setSearch(true);

        return $datatable;
    }

    protected function badgeByName($value)
    {
        switch ($value) {
        case 'admin':
            $color = 'bg-red-thunderbird';
            break;
        case 'supplier':
            $color = 'bg-green-seagreen';
            break;
        case 'mortician':
            $color = 'bg-purple-studio';
            break;
        case 'costumer':
            $color = 'bg-blue-steel';
            break;
        default:
            $color = 'bg-yellow-gold';
        }

        return sprintf('<span class="label %s"> %s </span>', $color, ucfirst($value));

    }
}
