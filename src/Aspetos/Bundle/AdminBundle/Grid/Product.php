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
use Doctrine\ORM\Query\Expr\Join;
use JMS\DiExtraBundle\Annotation as DI;
use Symfony\Component\Translation\DataCollectorTranslator;

/**
 * Class Product Grid
 *
 * @package Aspetos\Bundle\AdminBundle\Grid\Product
 * @author  Ludwig Ruderstaller <lr@cwd.at>
 *
 * @DI\Service("aspetos.admin.grid.product")
 */
class Product extends Grid
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
            ->setEntity('Model:Product', 'x')
            ->setFields(
                array(
                    'ID'           => 'x.id as xid',
                    'Name'         => 'x.name',
                    '_identifier_' => 'x.id'
                )
            )
            ->setOrder('x.name', 'asc')
            ->setSearchFields(array(0,1))
            ->setRenderers(
                array(
                    2 => array(
                        'view' => 'CwdAdminMetronicBundle:Grid:_actions.html.twig',
                        'params' => array(
                            //'view_route'     => 'aspetos_admin_product_product_detail',
                            'edit_route'     => 'aspetos_admin_product_product_edit',
                            'delete_route'   => 'aspetos_admin_product_product_delete',
                            //'undelete_route' => 'aspetos_admin_product_product_undelete',
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
                            $data[$key] = ($value > 0) ? 'Yes' : '';
                        } elseif ($key == 6) {
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
            case 'blocked':
                $color = 'bg-red-thunderbird';
                break;
            case 'rejected':
                $color = 'bg-red-flamingo';
                break;
            case 'proposed':
                $color = 'bg-blue-sharp';
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
