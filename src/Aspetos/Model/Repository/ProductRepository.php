<?php
/*
 * This file is part of aspetos
 *
 * (c)2015 Ludwig Ruderstaller <lr@cwd.at>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Aspetos\Model\Repository;

use Cwd\GenericBundle\Doctrine\EntityRepository;
use Aspetos\Model\Entity\ProductCategory;
use Aspetos\Model\Entity\Product;

/**
 * User Repository
 *
 * @author Ludwig Ruderstaller <lr@cwd.at>
 * @SuppressWarnings("ShortVariable")
 */
class ProductRepository extends EntityRepository
{
    /**
     * Find all Products for the given category and its child categories.
     *
     * @param ProductCategory $category
     * @param bool            $state
     *
     * @return Product[]
     */
    public function findByNestedCategories(ProductCategory $category, $state = null)
    {
        $q = $this->createQueryBuilder('p')
            ->select('p', 'phc', 'c')
            ->leftJoin('p.productHasCategory', 'phc')
            ->leftJoin('phc.productCategory', 'c')
            ->where('c.lft >= :lft')
            ->andWhere('c.rgt <= :rgt')
            ->orderBy('phc.sort', 'ASC')
            ->addOrderBy('p.name', 'ASC')
            ->setParameter('lft', $category->getLft())
            ->setParameter('rgt', $category->getRgt());

        if ($state !== null) {
            $q->andWhere('state = :state')
              ->setParameter('state', $state);
        }

        $query = $q->getQuery()
          ->useQueryCache(true)
          ->useResultCache(true);

        return $query->getResult();
    }

    /**
     * Find all Products for the given category, without child categories.
     *
     * @param ProductCategory $category
     *
     * @return Product[]
     */
    public function findBySingleCategory(ProductCategory $category)
    {
        $q = $this->createQueryBuilder('p')
            ->select('p', 'phc', 'c')
            ->leftJoin('p.productHasCategory', 'phc')
            ->leftJoin('phc.productCategory', 'c')
            ->where('c.id = :cid')
            ->orderBy('phc.sort', 'ASC')
            ->addOrderBy('p.name', 'ASC')
            ->setParameter('cid', $category->getId())
            ->getQuery()
            ->useQueryCache(true)
            ->useResultCache(true);

        return $q->getResult();
    }

    /**
     * Find most popular non-free products.
     *
     * @param int $limit
     * @return Product[]
     */
    public function findPopular($limit = 10)
    {
        $q = $this
            ->createQueryBuilder('product')
            ->addSelect('SUM(orderItem.amount) as totalAmount')
            ->leftJoin('Model:OrderItem orderItem WITH orderItem.product = product', '')
            ->where('product.sellPrice > 0')
            ->orderBy('totalAmount', 'DESC')
            ->addOrderBy('product.sellPrice', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->useQueryCache(true)
            ->useResultCache(true);
        ;

        // remove totalAmount select value that we do not actually need
        $products = array();
        foreach ($q->execute() as $data) {
            if (null !== $data[0]) {
                $products[] = $data[0];
            }
        }

        return $products;
    }
}
