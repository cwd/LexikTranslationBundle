<?php
/*
 * This file is part of aspetos.
 *
 * (c)2015 Ludwig Ruderstaller <lr@cwd.at>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Aspetos\Service;

/**
 * Interface HandlerInterface
 * @package Aspetos\Service
 */
interface HandlerInterface
{
    /**
     * @param misc $object
     *
     * @return mixed
     */
    public function create($object);

    /**
     * @param misc $object
     *
     * @return mixed
     */
    public function edit($object);

    /**
     * @param misc $object
     *
     * @return mixed
     */
    public function remove($object);
}
