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
use Aspetos\Model\Entity\Mortician as MorticianEntity;
use Aspetos\Model\Entity\Obituary as ObituaryEntity;
use Cwd\GenericBundle\Grid\Grid;
use Doctrine\ORM\Query\Expr\Join;
use JMS\DiExtraBundle\Annotation as DI;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * Class Condolence Grid for Morticians
 *
 * @package Aspetos\Bundle\AdminBundle\Grid\Supplier
 * @author  Ludwig Ruderstaller <lr@cwd.at>
 *
 * @DI\Service("aspetos.admin.grid.condolence")
 */
class Condolence extends Grid
{
    /**
     * @var TranslatorInterface
     */
    protected $translator;

    /**
     * @var Mortician
     */
    protected $mortician;

    /**
     * @var Obituary
     */
    protected $obituary = null;

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
                    'Firstname'    => 'o.firstname',
                    'Lastname'     => 'o.lastname',
                    'Deathdate'    => 'o.dayOfDeath',
                    'From'         => 'x.fromName',
                    'Content'      => 'x.content',
                    'Public'       => 'x.public',
                    'Status'       => 'x.state',
                    'CreatedAt'    => 'x.createdAt',
                    '_identifier_' => 'x.id'
                )
            )
            ->addJoin('x.obituary', 'o', Join::LEFT_JOIN)
            ->setGroupBy('x.id')
            ->setOrder('x.createdAt', 'desc')
            ->setSearchFields(array(0, 1, 2, 3, 4, 5, 6, 7, 8))
            ->setRenderers(
                array(
                    9 => array(
                        'view' => 'AspetosAdminBundle:Condolence:actions.html.twig',
                        'params' => array(
                            'edit_route'       => 'aspetos_admin_condolence_edit',
                            'delete_route'     => 'aspetos_admin_condolence_delete',
                            'block_route'      => 'aspetos_admin_condolence_block',
                            'unblock_route'    => 'aspetos_admin_condolence_unblock',
                        ),
                    ),
                )
            )

            ->setRenderer(
                function (&$data) use ($instance) {

                    foreach ($data as $key => $value) {
                        if ($key == 3) {
                            $data[$key] = $value->format('d.m.Y');
                        } elseif ($key == 6) {
                            $data[$key] = sprintf('<span class="label %s"> %s </span>', $this->getColorByState($value), $this->translator->trans(($value === true) ? 'Yes' : 'No'));
                        } elseif ($key == 7) {
                            $data[$key] = sprintf('<span class="label %s"> %s </span>', $this->getColorByState($value), $this->translator->trans($value));
                        } elseif ($value instanceof \Datetime) {
                            $data[$key] = $value->format('d.m.Y H:i:s');
                        }
                    }
                }
            )

            ->setHasAction(true)
            ->setSearch(true);

        $dqb = $datatable->getQueryBuilder()->getDoctrineQueryBuilder();

        if ($this->getMortician() !== null) {
            $dqb->andWhere('o.mortician = :mortician')
                ->setParameter('mortician', $this->getMortician());
        }

        if ($this->getObituary() !== null) {
            $dqb->andWhere('x.obituary = :obituary')
                ->setParameter('obituary', $this->getObituary());
        }

        return $datatable;
    }

    /**
     * @return MorticianEntity
     */
    public function getMortician()
    {
        return $this->mortician;
    }

    /**
     * @param MorticianEntity $mortician
     *
     * @return $this
     */
    public function setMortician($mortician)
    {
        $this->mortician = $mortician;

        return $this;
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
    public function setObituary($obituary)
    {
        $this->obituary = $obituary;

        return $this;
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

    protected function getColorByBool($value)
    {
        if ($value) {
            $color = 'bg-green-jungle';
        } else {
            $color = 'bg-red-thunderbird';
        }

        return $color;
    }
}
