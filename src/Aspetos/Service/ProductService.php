<?php
/*
 * This file is part of Aspetos
 *
 * (c)2014 Ludwig Ruderstaller <lr@cwd.at>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Aspetos\Service;

use Aspetos\Model\Entity\Product as Entity;
use Aspetos\Service\Exception\ProductNotFoundException as NotFoundException;
use Cwd\GenericBundle\Service\Generic;
use Doctrine\ORM\EntityManager;
use JMS\DiExtraBundle\Annotation as DI;
use Monolog\Logger;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Aspetos\Model\Entity\ProductCategory;

/**
 * Class Aspetos Service Product
 *
 * @package Aspetos\Service
 * @author  Ludwig Ruderstaller <lr@cwd.at>
 *
 * @DI\Service("aspetos.service.product.product", parent="cwd.generic.service.generic")
 */
class ProductService extends Generic
{
    /**
     * @var TokenStorage
     */
    protected $tokenStorage;

    /**
     * @var Entity[]
     */
    protected $productsBySlug = array();

    /**
     * @param EntityManager $entityManager
     * @param Logger        $logger
     * @param TokenStorage  $tokenStorage
     *
     * @DI\InjectParams({
     * })
     */
    public function __construct(EntityManager $entityManager, Logger $logger, TokenStorage $tokenStorage)
    {
        parent::__construct($entityManager, $logger);
        $this->tokenStorage  = $tokenStorage;
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
            $obj = parent::findById('Model:Product', intval($pid));

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
        if (isset($this->productsBySlug[$slug])) {
            return $this->productsBySlug[$slug];
        }

        try {
            $obj = $this->findOneByFilter('Model:Product', array('slug' => $slug));

            if ($obj === null) {
                $this->getLogger()->info('Row with slug {slug} not found', array('slug' => $slug));
                throw new NotFoundException('Row with slug ' . $slug . ' not found');
            }
            $this->productsBySlug[$slug] = $obj;

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

    /**
     * Find all products for the given category and its child categories.
     *
     * @param ProductCategory $category
     *
     * @return Entity[]
     */
    public function findByNestedCategories(ProductCategory $category)
    {
        return $this->getEm()->getRepository('Model:Product')->findByNestedCategories($category);
    }
}
