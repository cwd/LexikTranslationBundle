<?php
namespace Cwd\MediaBundle\Model\Entity;
use Doctrine\ORM\Mapping AS ORM;

/**
 * @ORM\MappedSuperclass
 */
class Media
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=200, nullable=false)
     */
    private $mediatype;

    /**
     * @ORM\Column(type="string", length=64, nullable=false)
     */
    private $filehash;

    /**
     * @ORM\Column(type="string", length=250, nullable=true)
     */
    private $filename;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $deletedAt;
}
