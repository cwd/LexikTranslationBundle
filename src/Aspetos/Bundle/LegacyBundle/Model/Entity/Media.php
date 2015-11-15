<?php
namespace Aspetos\Bundle\LegacyBundle\Model\Entity;
use Doctrine\ORM\Mapping AS ORM;

/**
 * @ORM\Entity(readOnly=true, repositoryClass="Aspetos\Bundle\LegacyBundle\Model\Repository\MediaRepository")
 * @ORM\Table(name="es_media", indexes={@ORM\Index(name="gid", columns={"gid"})})
 *
 */
class Media
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer", length=10)
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $mid;

    /**
     * @ORM\Column(type="enum", nullable=true, options={"default":"NULL"})
     */
    private $mtype;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    private $filename;

    /**
     * @ORM\Column(type="integer", length=1, nullable=false, options={"default":"'0'"})
     */
    private $hide;

    /**
     * @ORM\Column(type="integer", length=11, nullable=true, options={"default":"NULL"})
     */
    private $sort;

    /**
     * @ORM\Column(type="enum", nullable=false, options={"default":"'fresh'"})
     */
    private $status;

    /**
     * @ORM\Column(type="string", nullable=true, options={"default":"NULL"})
     */
    private $description;

    /**
     * @ORM\Column(type="decimal", length=10, nullable=false, scale=2, options={"default":"'1.00'"})
     */
    private $priceFactor;

    /**
     * @ORM\Column(type="enum", nullable=false, options={"default":"'http-form'"})
     */
    private $uploaded_by;

    /**
     * @ORM\Column(type="blob", nullable=true, options={"default":"NULL"})
     */
    private $transcoding_error;

    /**
     * @ORM\Column(type="timestamp", nullable=false, options={"default":"CURRENT_TIMESTAMP"})
     */
    private $upload_timestamp;

    /**
     * @ORM\Column(type="decimal", length=4, nullable=false, scale=3)
     */
    private $rating_view;

    /**
     * @ORM\Column(type="integer", length=10, nullable=false)
     */
    private $rating_count_view;

    /**
     * @ORM\Column(type="integer", length=11, nullable=false)
     */
    private $views_view;
}
