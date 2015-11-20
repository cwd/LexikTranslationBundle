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
use Aspetos\Model\Repository\ProductCategoryRepository as EntityRepository;
use Aspetos\Service\BaseService;
use Aspetos\Service\Exception\ProductCategoryNotFoundException as NotFoundException;
use Doctrine\ORM\EntityManager;
use JMS\DiExtraBundle\Annotation as DI;
use Psr\Log\LoggerInterface;

/**
 * Class Aspetos Service ProductCategory
 *
 * @package Aspetos\Service\Product
 * @author  Ludwig Ruderstaller <lr@cwd.at>
 *
 * @method Entity getNew()
 * @method Entity find($pid)
 * @method EntityRepository getRepository()
 * @method NotFoundException createNotFoundException($message = null, $code = null, $previous = null)
 *
 * @DI\Service("aspetos.service.product.category", parent="cwd.generic.service.generic")
 */
class CategoryService extends BaseService
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
     * Set raw option values right before validation. This can be used to chain
     * options in inheritance setups.
     *
     * @return array
     */
    protected function setServiceOptions()
    {
        return array(
            'modelName'                 => 'Model:ProductCategory',
            'notFoundExceptionClass'    => 'Aspetos\Service\Exception\ProductCategoryNotFoundException',
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
        try {
            $obj = $this->findOneByFilter($this->getModelName(), array('slug' => $slug));

            if ($obj === null) {
                $this->getLogger()->info('Row with slug {slug} not found', array('slug' => $slug));
                throw $this->createNotFoundException('Row with slug ' . $slug . ' not found');
            }

            return $obj;
        } catch (\Exception $e) {
            throw $this->createNotFoundException();
        }
    }

    /**
     * Get an array containing all category nodes. Unfortunately there is no way at the moment to
     * fully fetch and hydrate an Entity tree.
     * @see https://github.com/Atlantic18/DoctrineExtensions/blob/master/doc/tree.md#retrieving-the-whole-tree-as-an-array
     *
     * @return array
     */
    public function getTreeAsArray()
    {
        return $this->getRepository()->childrenHierarchy();
    }
}
