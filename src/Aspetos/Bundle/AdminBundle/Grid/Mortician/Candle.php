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
use Aspetos\Model\Entity\Obituary as ObituaryEntity;
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
 * @DI\Service("aspetos.admin.grid.mortician.obituary.candle")
 */
class Candle extends Grid
{
    /**
     * @var TranslatorInterface
     */
    protected $translator;

    /**
     * @var Obituary
     */
    protected $obituary;

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
            ->setEntity('Model:Candle', 'x')
            ->setFields(
                array(
                    'ID'           => 'x.id as xid',
                    'Firstname'    => 'u.firstname',
                    'Lastname'     => 'u.lastname',
                    'Content'      => 'x.content',
                    'Expires'      => 'x.expiresAt',
                    'Product'      => 'p.name',
                    'Created'      => 'x.createdAt',
                    'State'        => 'x.state',
                    '_identifier_' => 'x.id'
                )
            )
            ->addJoin('x.orderItem', 'oi', Join::LEFT_JOIN)
            ->addJoin('oi.order', 'o', Join::LEFT_JOIN)
            ->addJoin('o.customer', 'c', Join::LEFT_JOIN)
            ->addJoin('c.baseUser', 'u', Join::LEFT_JOIN)
            ->addJoin('x.product', 'p', Join::LEFT_JOIN)
            ->setWhere('x.obituary = :obituary', array('obituary' => $this->getObituary()))
            ->setGroupBy('x.id')
            ->setOrder('x.createdAt', 'desc')
            ->setSearchFields(array(0, 1, 2, 3, 4, 5, 6, 7))
            ->setRenderers(
                array(
                    8 => array(
                        'view' => 'AspetosAdminBundle:Mortician/Candle:actions.html.twig',
                        'params' => array(
                            'edit_route'       => 'aspetos_admin_mortician_candle_edit',
                            'delete_route'     => 'aspetos_admin_mortician_candle_delete',
                            //'undelete_route' => 'aspetos_admin_supplier_supplier_undelete',
                        ),
                    ),
                )
            )

            ->setRenderer(
                function (&$data) use ($instance) {

                    foreach ($data as $key => $value) {
                        if ($key == 7) {
                            $color = $this->getColorByState($value);
                            $data[$key] = sprintf('<span class="label %s"> %s </span>', $color, $this->translator->trans($value));
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

    protected function getColorByState($value)
    {
        switch ($value) {
            case 'active':
                $color = 'bg-green-jungle';
                break;
            case 'inactive':
                $color = 'bg-red-thunderbird';
                break;
            case 'paymentAwaiting':
                $color = 'blue-madison';
                break;
            default:
                $color = 'default';
        }

        return $color;
    }

    /**
     * @return ObituaryEntity
     */
    public function getObituary()
    {
        return $this->obituary;
    }

    /**
     * @param ObituaryEntity $obituary
     *
     * @return $this
     */
    public function setObituary(ObituaryEntity $obituary)
    {
        $this->obituary = $obituary;

        return $this;
    }
}
