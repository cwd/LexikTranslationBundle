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
use Aspetos\Model\Entity\ProductCategory;
use Aspetos\Model\Repository\ProductRepository as EntityRepository;
use Aspetos\Service\Exception\ProductNotFoundException as NotFoundException;
use Doctrine\ORM\EntityManager;
use JMS\DiExtraBundle\Annotation as DI;
use Psr\Log\LoggerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;

/**
 * Class Aspetos Service Product
 *
 * @package Aspetos\Service
 * @author  Ludwig Ruderstaller <lr@cwd.at>
 *
 * @method Entity getNew()
 * @method Entity find($pid)
 * @method EntityRepository getRepository()
 * @method NotFoundException createNotFoundException($message = null, $code = null, $previous = null)
 *
 * @DI\Service("aspetos.service.product.product", parent="cwd.generic.service.generic")
 */
class ProductService extends BaseService
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
     * @param EntityManager   $entityManager
     * @param LoggerInterface $logger
     * @param TokenStorage    $tokenStorage
     *
     * @DI\InjectParams({
     * })
     */
    public function __construct(EntityManager $entityManager, LoggerInterface $logger, TokenStorage $tokenStorage)
    {
        parent::__construct($entityManager, $logger);
        $this->tokenStorage  = $tokenStorage;
    }

    /**
     * Set raw option values right before validation. This can be used to chain
     * options in inheritance setups.
     *
     * @return array
     */
    protected function setServiceOptions()
    {
        return array(
            'modelName'                 => 'Model:Product',
            'notFoundExceptionClass'    => 'Aspetos\Service\Exception\ProductNotFoundException',
        );
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
            $obj = $this->findOneByFilter($this->getModelName(), array('slug' => $slug));

            if ($obj === null) {
                $this->getLogger()->info('Row with slug {slug} not found', array('slug' => $slug));
                throw $this->createNotFoundException('Row with slug ' . $slug . ' not found');
            }
            $this->productsBySlug[$slug] = $obj;

            return $obj;
        } catch (\Exception $e) {
            throw $this->createNotFoundException($e->getMessage());
        }
    }

    /**
     * Find enabled product instance by primary ID.
     * Returns null if none is found.
     *
     * @param int $id
     *
     * @return Entity|null
     */
    public function findEnabledById($id)
    {
        return $this->findOneByFilter($this->getModelName(), array(
            'id' => $id,
            'state' => true,
        ));
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
        return $this->getRepository()->findByNestedCategories($category);
    }
}
