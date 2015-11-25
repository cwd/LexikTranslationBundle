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
use JMS\DiExtraBundle\Annotation as DI;
use Symfony\Component\Translation\TranslatorInterface;

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
        $datatable = $this->getDatatable()
            ->setEntity('Model:BaseUser', 'x')
            ->setFields(
                array(
                    'ID' => 'x.id as xid',
                    'Firstname' => 'x.firstname',
                    'Lastname' => 'x.lastname',
                    'Email' => 'x.email',
                    'Type'  => 'x.id as xid2',
                    'Sate' => 'x.state',
                    'Created' => 'x.createdAt',
                    '_identifier_'  => 'x.id'
                )
            )
            ->setOrder('x.lastname', 'asc')
            ->setSearchFields(array(0,1,2,3,5))
            ->setRenderers(
                array(
                    4 => array(
                        'view' => 'AspetosAdminBundle:User:User/gridTypeColumn.html.twig',
                    ),
                    7 => array(
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

                        if ($key == 5) {
                            $data[$key] = $this->badgeByState($value);
                        }
                    }
                }
            )

            ->setHasAction(true)
            ->setSearch(true);

        return $datatable;
    }

    protected function badgeByState($value)
    {
        $label = $value;

        switch ($value) {
            case 'active':
                $color = 'bg-green-jungle';
                break;
            case 'inactive':
                $color = 'bg-red-thunderbird';
                break;
            case 'optin':
                $color = 'bg-red-flamingo';
                break;
            case 'new':
                $color = 'bg-yellow-lemon';
                break;
            default:
                $color = 'bg-grey-steel';
        }

        return sprintf('<span class="label %s"> %s </span>', $color, $this->translator->trans($label));
    }
}
