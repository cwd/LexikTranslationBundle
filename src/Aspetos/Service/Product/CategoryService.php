<?php
/*
 * This file is part of Aspetos
 *
 * (c)2014 Ludwig Ruderstaller <lr@cwd.at>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Aspetos\Service\Product;

use Aspetos\Model\Entity\ProductCategory as Entity;
use Aspetos\Service\Exception\ProductCategoryNotFoundException as NotFoundException;
use Cwd\GenericBundle\Service\Generic;
use Doctrine\ORM\EntityManager;
use JMS\DiExtraBundle\Annotation as DI;
use Psr\Log\LoggerInterface;

/**
 * Class Aspetos Service ProductCategory
 *
 * @package Aspetos\Service\Product
 * @author  Ludwig Ruderstaller <lr@cwd.at>
 *
 * @DI\Service("aspetos.service.product.category", parent="cwd.generic.service.generic")
 */
class CategoryService extends Generic
{
    /**
     * @param EntityManager   $entityManager
     * @param LoggerInterface $logger
     *
     * @DI\InjectParams({
     * })
     */
    public function __construct(EntityManager $entityManager, LoggerInterface $logger)
    {
        parent::__construct($entityManager, $logger);
    }

    /**
     * Find Object by ID
     *
     * @param int $pid
     *
     * @return Entity
     * @throws NotFoundException
     */
    public function find($pid)
    {
        try {
            $obj = parent::findById('Model:ProductCategory', intval($pid));

            if ($obj === null) {
                $this->getLogger()->info('Row with ID {id} not found', array('id' => $pid));
                throw new NotFoundException('Row with ID ' . $pid . ' not found');
            }

            return $obj;
        } catch (\Exception $e) {
            throw new NotFoundException();
        }
    }

    /**
     * Find Object by slug
     *
     * @param string $slug
     *
     * @return Entity
     * @throws NotFoundException
     */
    public function findOneBySlug($slug)
    {
        try {
            $obj = $this->findOneByFilter('Model:ProductCategory', array('slug' => $slug));

            if ($obj === null) {
                $this->getLogger()->info('Row with slug {slug} not found', array('slug' => $slug));
                throw new NotFoundException('Row with slug ' . $slug . ' not found');
            }

            return $obj;
        } catch (\Exception $e) {
            throw new NotFoundException();
        }
    }

    /**
     * @return Entity
     */
    public function getNew()
    {
        return new Entity();
    }
}
