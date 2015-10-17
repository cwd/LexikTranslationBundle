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
use Symfony\Component\Translation\DataCollectorTranslator;

/**
 * Class Mortician Grid
 *
 * @package Aspetos\Bundle\AdminBundle\Grid
 * @author  Ludwig Ruderstaller <lr@cwd.at>
 *
 * @DI\Service("aspetos.admin.grid.mortician")
 */
class Mortician extends Grid
{
    /**
     * @var DataCollectorTranslator
     */
    protected $translator;

    /**
     * @param Datatable               $datatable
     * @param DataCollectorTranslator $translator
     *
     * @DI\InjectParams({
     *  "datatable" = @DI\Inject("datatable", strict = false),
     *  "translator" = @DI\Inject("translator", strict = false)
     * })
     */
    public function __construct(Datatable $datatable, DataCollectorTranslator $translator)
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
            ->setEntity('Model:Mortician', 'x')
            ->setFields(
                array(
                    'ID'            => 'x.id as xid',
                    'Name'          => 'x.name',
                    'Contact Name'   => 'x.contactName',
                    'Email'        => 'x.email',
                    'Country'       => 'x.country',
                    'State'         => 'x.state',
                    '_identifier_'  => 'x.id'
                )
            )
            ->setOrder('x.name', 'asc')
            ->setSearchFields(array(0,1,2,3,4,5))
            ->setRenderers(
                array(
                    4 => array(
                        'view' => 'AspetosAdminBundle:Mortician/Mortician:flag.html.twig'
                    ),
                    6 => array(
                        'view' => 'CwdAdminMetronicBundle:Grid:_actions.html.twig',
                        'params' => array(
                            'view_route'     => 'aspetos_admin_mortician_mortician_detail',
                            'edit_route'     => 'aspetos_admin_mortician_mortician_edit',
                            'delete_route'   => 'aspetos_admin_mortician_mortician_delete',
                            //'undelete_route' => 'aspetos_admin_mortician_mortician_undelete',
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
        if ($value) {
            $color = 'bg-green-seagreen';
            $label = 'active';
        } else {
            $color = 'bg-red-thunderbird';
            $label = 'inactive';
        }

        return sprintf('<span class="label %s"> %s </span>', $color, $this->translator->trans($label));

    }
}
