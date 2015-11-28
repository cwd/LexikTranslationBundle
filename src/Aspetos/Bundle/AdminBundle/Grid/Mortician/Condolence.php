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
 * @DI\Service("aspetos.admin.grid.mortician.obituary.condolence")
 */
class Condolence extends Grid
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
            ->setEntity('Model:Condolence', 'x')
            ->setFields(
                array(
                    'ID'           => 'x.id as xid',
                    'From'         => 'x.fromName',
                    'Content'      => 'x.content',
                    'Public'       => 'x.public',
                    'Created'      => 'x.createdAt',
                    'By'           => 'u.username',
                    'State'        => 'x.state',
                    '_identifier_' => 'x.id'
                )
            )
            ->addJoin('x.createdBy', 'u', Join::LEFT_JOIN)
            ->setWhere('x.obituary = :obituary', array('obituary' => $this->getObituary()))
            ->setGroupBy('x.id')
            ->setOrder('x.createdAt', 'desc')
            ->setSearchFields(array(0, 1, 2, 3, 4, 5, 6))
            ->setRenderers(
                array(
                    7 => array(
                        'view' => 'AspetosAdminBundle:Mortician/Condolence:actions.html.twig',
                        'params' => array(
                            'edit_route'       => 'aspetos_admin_mortician_condolence_edit',
                            'delete_route'     => 'aspetos_admin_mortician_condolence_delete',
                            'block_route'      => 'aspetos_admin_mortician_condolence_block',
                            'unblock_route'    => 'aspetos_admin_mortician_condolence_unblock',
                            //'undelete_route' => 'aspetos_admin_supplier_supplier_undelete',
                        ),
                    ),
                )
            )

            ->setRenderer(
                function (&$data) use ($instance) {

                    foreach ($data as $key => $value) {
                        if ($key == 3) {
                            $color = 'bg-red-thunderbird';
                            $label = 'No';

                            if ($value) {
                                $color = 'bg-green-jungle';
                                $label = 'Yes';
                            }
                            $data[$key] = sprintf('<span class="label %s"> %s </span>', $color, $this->translator->trans($label));
                        } elseif ($key == 6) {
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
