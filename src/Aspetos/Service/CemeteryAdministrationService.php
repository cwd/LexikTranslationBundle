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

use Aspetos\Model\Entity\CemeteryAdministration as Entity;
use Aspetos\Model\Repository\CemeteryAdministrationRepository as EntityRepository;
use Aspetos\Service\Exception\CemeteryAdministrationNotFoundException as NotFoundException;
use Doctrine\ORM\EntityManager;
use JMS\DiExtraBundle\Annotation as DI;
use Psr\Log\LoggerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;

/**
 * Class Aspetos Service CemeteryAdministration
 *
 * @package Aspetos\Service
 * @author  Ludwig Ruderstaller <lr@cwd.at>
 *
 * @method Entity getNew()
 * @method Entity find($pid)
 * @method EntityRepository getRepository()
 * @method NotFoundException createNotFoundException($message = null, $code = null, $previous = null)
 *
 * @DI\Service("aspetos.service.cemetery.administration", parent="cwd.generic.service.generic")
 */
class CemeteryAdministrationService extends BaseService
{
    /**
     * @var TokenStorage
     */
    protected $tokenStorage;

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
            'modelName'                 => 'Model:CemeteryAdministration',
            'notFoundExceptionClass'    => 'Aspetos\Service\Exception\CemeteryAdministrationNotFoundException',
        );
    }

    /**
     * @param string $name
     * @param string $street
     * @param string $city
     * @param int    $zipcode
     * @return Entity
     */
    public function findOneByNameAndAddress($name, $street, $city, $zipcode)
    {
        try {
            $filter = array(
                'name'      => $name,
                'street'    => $street,
                'city'      => $city,
                'zipcode'   => $zipcode
            );
            $obj =  $this->findOneByFilter($this->getModelName(), $filter);

            if ($obj === null) {
                $this->getLogger()->info('Row with name {name} not found', array('name' => $name));
                throw $this->createNotFoundException('Row with name ' . $name . ' not found');
            }

            return $obj;
        } catch (\Exception $e) {
            throw $this->createNotFoundException($e->getMessage());
        }
    }
}
