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
     *
     * @return Product[]
     */
    public function findByNestedCategories(ProductCategory $category)
    {
        $q = $this->createQueryBuilder('p')
            ->select('p', 'phc', 'c')
            ->leftJoin('p.productHasCategory', 'phc')
            ->leftJoin('phc.productCategory', 'c')
            ->where('c.lft >= :lft')
            ->andWhere('c.rgt <= :rgt')
            ->orderBy('phc.sort', 'ASC')
            ->orderBy('p.name', 'ASC')
            ->setParameter('lft', $category->getLft())
            ->setParameter('rgt', $category->getRgt())
            ->getQuery()
            ->useQueryCache(true)
            ->useResultCache(true);

        return $q->getResult();
    }
}
