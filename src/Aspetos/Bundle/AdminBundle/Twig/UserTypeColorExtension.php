<?php
/*
 * This file is part of aspetos.
 *
 * (c)2015 Ludwig Ruderstaller <lr@cwd.at>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Aspetos\Bundle\AdminBundle\Twig;

/**
 * Class UserTypeColorExtension
 *
 * @package Aspetos\Bundle\AdminBundle\Twig
 * @author  Ludwig Ruderstaller <lr@cwd.at>
 */
class UserTypeColorExtension extends \Twig_Extension
{


    /**
     * @return array
     */
    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('userTypeBadge', array($this, 'getBadge'), array('is_safe' => array('html'))),
        );
    }

    /**
     * @return array
     */
    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('userTypeColor', array($this, 'getColor'))
        );
    }

    /**
     * @param string $type
     *
     * @return string
     */
    public function getBadge($type)
    {
        return sprintf('<span class="label %s">%s</span>', $this->getColor($type), ucfirst($type));
    }

    /**
     * @param string $type
     *
     * @return string
     */
    public function getColor($type)
    {
        switch ($type) {
            case 'admin':
                $color = 'bg-red-thunderbird';
                break;
            case 'supplier':
                $color = 'bg-green-seagreen';
                break;
            case 'mortician':
                $color = 'bg-purple-studio';
                break;
            case 'costumer':
                $color = 'bg-blue-steel';
                break;
            default:
                $color = 'bg-yellow-gold';
        }

        return $color;
    }

    /**
     *
     * @return string
     */
    public function getName()
    {
        return 'aspetos_admin_twig_user_type_color';
    }
}

