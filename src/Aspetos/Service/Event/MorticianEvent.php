<?php
/*
 * This file is part of aspetos.
 *
 * (c)2015 Ludwig Ruderstaller <lr@cwd.at>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Aspetos\Service\Event;

use Aspetos\Model\Entity\Mortician;
use Symfony\Component\EventDispatcher\Event;

/**
 * Class MorticianEvent
 *
 * @package Neos\Service\Event
 * @author  Ludwig Ruderstaller <lr@cwd.at>
 */
class MorticianEvent extends Event
{
    /**
     * @var Mortician
     */
    protected $mortician;

    /**
     * @param Mortician $mortician
     */
    public function __construct(Mortician $mortician)
    {
        $this->mortician = $mortician;
    }

    /**
     * @return Mortician
     */
    public function getMortician()
    {
        return $this->mortician;
    }
}
