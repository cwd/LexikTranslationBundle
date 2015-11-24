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

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Cwd\GenericBundle\Grid\Grid;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class CrudController
 *
 * @package Aspetos\Bundle\AdminBundle\Controller
 * @author  Ludwig Ruderstaller <lr@cwd.at>
 */
class CrudController extends BaseController
{
    /**
     * @Route("/create")
     * @Method({"GET", "POST"})
     *
     * @param Request $request
     *
     * @return RedirectResponse|Response
     */
    public function createAction(Request $request)
    {
        $object = $this->getNewEntity();

        return $this->formHandler($object, $request, true);
    }

    /**
     * @Route("/list")
     * @Route("/")
     * @Template()
     *
     * @return array
     */
    public function listAction()
    {
        $this->getGrid();

        return array('icon' => $this->getOption('icon'));
    }

    /**
     * @return array
     */
    public function indexAction()
    {
        return array();
    }

    /**
     * Grid action
     *
     * @Route("/grid")
     * @return Response
     */
    public function gridAction()
    {
        return $this->getGrid()->execute();
    }
}
