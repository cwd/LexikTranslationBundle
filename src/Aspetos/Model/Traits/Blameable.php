<?php
/*
 * This file is part of Aspetos.
 *
 * (c)2014 Ludwig Ruderstaller <lr@cwd.at>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Aspetos\Model\Traits;

use Aspetos\Model\Entity\User;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class Blameable
 *
 * @package Aspetos\Model\Traits
 * @author  Ludwig Ruderstaller <lr@cwd.at>
 */
trait Blameable
{
    /**
     * @var User $createdBy
     *
     * @Gedmo\Blameable(on="create")
     * @ORM\ManyToOne(targetEntity="Aspetos\Model\Entity\User")
     * @ORM\JoinColumn(name="createdBy", referencedColumnName="id")
     */
    private $createdBy;

    /**
     * @var User $updatedBy
     *
     * @Gedmo\Blameable(on="update")
     * @ORM\ManyToOne(targetEntity="Aspetos\Model\Entity\User")
     * @ORM\JoinColumn(name="updatedBy", referencedColumnName="id")
     */
    private $updatedBy;

    /**
     * @return User
     */
    public function getCreatedBy()
    {
        return $this->createdBy;
    }

    /**
     * @param User $createdBy
     *
     * @return $this
     */
    public function setCreatedBy(User $createdBy)
    {
        $this->createdBy = $createdBy;

        return $this;
    }

    /**
     * @return User
     */
    public function getUpdatedBy()
    {
        return $this->updatedBy;
    }

    /**
     * @param User $updatedBy
     *
     * @return $this
     */
    public function setUpdatedBy(User $updatedBy)
    {
        $this->updatedBy = $updatedBy;

        return $this;
    }
}
