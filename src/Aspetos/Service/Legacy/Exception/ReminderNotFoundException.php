<?php
/*
 * This file is part of Aspetos
 *
 * (c)2014 Ludwig Ruderstaller <lr@cwd.at>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Aspetos\Service\Legacy\Exception;
use Doctrine\ORM\EntityNotFoundException;

/**
 * Class RemindNotFoundException
 *
 * @package Aspetos\Legacy\Service\Exception
 * @author  Ludwig Ruderstaller <lr@cwd.at>
 */
class ReminderNotFoundException extends EntityNotFoundException
{

}
