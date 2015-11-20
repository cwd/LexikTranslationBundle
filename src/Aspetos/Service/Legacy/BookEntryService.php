<?php
/*
 * This file is part of Aspetos
 *
 * (c)2014 Ludwig Ruderstaller <lr@cwd.at>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Aspetos\Service\Legacy;

use Aspetos\Bundle\LegacyBundle\Model\Entity\User as ObituaryEntity;
use Doctrine\ORM\EntityManager;
use JMS\DiExtraBundle\Annotation as DI;
use Cwd\GenericBundle\Service\Generic;
use Aspetos\Bundle\LegacyBundle\Model\Entity\BookEntry as Entity;
use Aspetos\Service\Legacy\Exception\BookEntryNotFoundException as NotFoundException;
use Monolog\Logger;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;

/**
 * Class Aspetos Service BookEntry
 *
 * @package Aspetos\Service
 * @author  Ludwig Ruderstaller <lr@cwd.at>
 *
 * @DI\Service("aspetos.service.legacy.bookentry", parent="cwd.generic.service.generic")
 */
class BookEntryService extends Generic
{
    /**
     * @var TokenStorage
     */
    protected $tokenStorage;

    /**
     * @param EntityManager $legacyEntityManager
     * @param Logger        $logger
     * @param TokenStorage  $tokenStorage
     *
     * @DI\InjectParams({
     * })
     */
    public function __construct(EntityManager $legacyEntityManager, Logger $logger, TokenStorage $tokenStorage)
    {
        parent::__construct($legacyEntityManager, $logger);
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
            $obj = parent::findById('Legacy:BookEntry', intval($pid));

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
     * @param int    $uid
     * @param string $type
     *
     * @return int|null
     */
    public function findBookForObituary($uid, $type = 'candle')
    {
        $object = $this->getEm()->getRepository('Legacy:Book')->findOneBy(array('type' => $type, 'uid' => $uid));

        if ($object === null) {
            return null;
        }

        return $object->getBookId();
    }

    /**
     * @param int $uid
     *
     * @return array
     */
    public function findBooksForObituary($uid)
    {
        $objects = $this->getEm()->getRepository('Legacy:Book')->findBy(array('uid' => $uid));
        $return = array();

        foreach ($objects as $object) {
            $return[] = $object->getBookId();
        }

        return $return;
    }

    /**
     * @return Entity
     */
    public function getNew()
    {
        return new Entity();
    }
}
