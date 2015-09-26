<?php
/*
 * This file is part of aspetos.
 *
 * (c)2015 Ludwig Ruderstaller <lr@cwd.at>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Aspetos\Bundle\AdminBundle\Controller;

use Cwd\GenericBundle\Controller\GenericController as CwdController;
use Cwd\GenericBundle\Exception\BaseException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class BaseController
 *
 * @package Aspetos\Bundle\AdminBundle\Controller
 * @author  Ludwig Ruderstaller <lr@cwd.at>
 */
abstract class BaseController extends CwdController
{
    /**
     * @param misc    $object
     * @param Request $request
     *
     * @Method({"GET", "DELETE"})
     * @return RedirectResponse
     */
    public function deleteAction($object, Request $request, $handler = null, $redirectRoute = null)
    {
        try {
            $this->get($handler)->remove($object);
            $this->flashSuccess('Data successfully removed');
        } catch (BaseException $e) {
            $this->flashError('Object with this ID not found ('.$request->get('id').')');
        } catch (\Exception $e) {
            $this->flashError('Unexpected Error: '.$e->getMessage());
        }

        if ($redirectRoute !== null) {
            return $this->redirect($this->generateUrl($redirectRoute));
        }
    }
}
