<?php
/*
 * This file is part of Aspetos
 *
 * (c)2014 Ludwig Ruderstaller <lr@cwd.at>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Aspetos\Bundle\AdminBundle\Grid\Product;

use Ali\DatatableBundle\Util\Datatable;
use Cwd\GenericBundle\Grid\Grid;
use Doctrine\ORM\Query\Expr\Join;
use JMS\DiExtraBundle\Annotation as DI;
use Symfony\Component\Translation\DataCollectorTranslator;

/**
 * Class Product Category Grid
 *
 * @package Aspetos\Bundle\AdminBundle\Grid\Product
 * @author  Ludwig Ruderstaller <lr@cwd.at>
 *
 * @DI\Service("aspetos.admin.grid.product.category")
 */
class Category extends Grid
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
        $datatable = $this->getDatatable()
            ->setEntity('Model:ProductCategory', 'x')
            ->setFields(
                array(
                    'ID'           => 'x.id as xid',
                    'Image'        => 'm.id',
                    'Level'        => 'x.lvl',
                    'Order'        => 'x.lft',
                    'Name'         => 'x.name',
                    'Parent'       => 'p.name',
                    'State'        => 'x.state',
                    '_identifier_' => 'x.id'
                )
            )
            ->addJoin('x.image', 'm', Join::LEFT_JOIN)
            ->addJoin('x.parent', 'p', Join::LEFT_JOIN)
            ->setOrder('x.lft', 'ASC')
            ->setRenderers(
                array(
                    1 => array(
                        'view' => 'AspetosAdminBundle:Grid:image.html.twig',
                        'params' => array(
                            'field' => 'mainImage',
                            'width' => 100
                        )
                    ),
                    7 => array(
                        'view' => 'CwdAdminMetronicBundle:Grid:_actions.html.twig',
                        'params' => array(
                            //'view_route'     => 'aspetos_admin_product_category_detail',
                            'edit_route'     => 'aspetos_admin_product_category_edit',
                            'delete_route'   => 'aspetos_admin_product_category_delete',
                            //'undelete_route' => 'aspetos_admin_product_category_undelete',
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

                        if ($key == 6) {
                            $data[$key] = $this->badgeByBool($value);
                        }
                    }
                }
            )

            ->setHasAction(true)
            ->setSearch(true);

        return $datatable;
    }

    protected function badgeByBool($value)
    {
        $label = 'No';
        $color = 'bg-red-thunderbird';


        if ($value) {
            $color = 'bg-green-jungle';
            $label = 'Yes';
        }

        return sprintf('<span class="label %s"> %s </span>', $color, $this->translator->trans($label));
    }
}
