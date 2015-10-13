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

use Aspetos\Model\Entity\BaseUser;
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
     * @var BaseUser $createdBy
     *
     * @Gedmo\Blameable(on="create")
     * @ORM\ManyToOne(targetEntity="Aspetos\Model\Entity\BaseUser")
     * @ORM\JoinColumn(name="createdBy", referencedColumnName="id")
     */
    private $createdBy;

    /**
     * @var BaseUser $updatedBy
     *
     * @Gedmo\Blameable(on="update")
     * @ORM\ManyToOne(targetEntity="Aspetos\Model\Entity\BaseUser")
     * @ORM\JoinColumn(name="updatedBy", referencedColumnName="id")
     */
    private $updatedBy;

    /**
     * @return BaseUser
     */
    public function getCreatedBy()
    {
        return $this->createdBy;
    }

    /**
     * @param BaseUser $createdBy
     *
     * @return $this
     */
    public function setCreatedBy(BaseUser $createdBy)
    {
        $this->createdBy = $createdBy;

        return $this;
    }

    /**
     * @return BaseUser
     */
    public function getUpdatedBy()
    {
        return $this->updatedBy;
    }

    /**
     * @param BaseUser $updatedBy
     *
     * @return $this
     */
    public function setUpdatedBy(BaseUser $updatedBy)
    {
        $this->updatedBy = $updatedBy;

        return $this;
    }
}
