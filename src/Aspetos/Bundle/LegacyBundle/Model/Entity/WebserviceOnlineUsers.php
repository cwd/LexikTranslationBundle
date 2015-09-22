<?php
namespace Aspetos\Bundle\LegacyBundle\Model\Entity;
use Doctrine\ORM\Mapping AS ORM;

/**
 * @ORM\Entity
 * @ORM\Table(
 *     name="es_webservice_onlineUsers",
 *     indexes={@ORM\Index(name="date", columns={"date"})},
 *     uniqueConstraints={@ORM\UniqueConstraint(name="ip_domain", columns={"ip","domain"})}
 * )
 */
class WebserviceOnlineUsers
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer", length=10)
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $mid;

    /**
     * @ORM\Column(type="enum", nullable=false, options={"default":"'at'"})
     */
    private $domain;

    /**
     * @ORM\Column(type="string", length=15, nullable=true, options={"default":"NULL"})
     */
    private $ip;

    /**
     * @ORM\Column(type="date", nullable=true, options={"default":"NULL"})
     */
    private $date;
}
