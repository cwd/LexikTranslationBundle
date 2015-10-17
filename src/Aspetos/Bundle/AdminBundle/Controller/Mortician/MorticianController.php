<?php
/*
 * This file is part of AspetosAdminBundle
 *
 * (c)2014 Ludwig Ruderstaller <lr@cwd.at>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/
namespace Aspetos\Bundle\AdminBundle\Controller\Mortician;

use Aspetos\Bundle\AdminBundle\Controller\BaseController;
use Cwd\MediaBundle\Model\Entity\Media;
use Aspetos\Model\Entity\Mortician;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use JMS\SecurityExtraBundle\Annotation\PreAuthorize;
use JMS\SecurityExtraBundle\Annotation\Secure;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class MorticianController
 *
 * @package AspetosAdminBundle\Bundle\AdminBundle\Controller
 * @author  Ludwig Ruderstaller <lr@cwd.at>
 *
 * @PreAuthorize("hasRole('ROLE_MORTICIAN')")
 * @Route("/mortician")
 */
class MorticianController extends BaseController
{
    /**
     * Set raw option values right before validation. This can be used to chain
     * options in inheritance setups.
     *
     * @return array
     */
    protected function setOptions()
    {
        $options = array(
            'entityService' => 'aspetos.service.mortician',
            'entityFormType' => 'aspetos_admin_form_mortician_mortician',
            'gridService' => 'aspetos.admin.grid.mortician',
            'icon' => 'fa fa-car',
            'redirectRoute' => 'aspetos_admin_mortician_mortician_list',
            'title' => 'Mortician',
        );

        return array_merge(parent::setOptions(), $options);
    }

    /**
     * @Security("is_granted('mortician.view', crudObject)")
     * @Route("/detail/{id}/tab/{type}")
     *
     * @ParamConverter("crudObject", class="Model:Mortician")
     *
     * @param Mortician $crudObject
     * @param Request $request
     *
     * @return Response
     */
    public function tabAction(Mortician $crudObject, Request $request)
    {
        $type = $request->get('type');

        switch ($type) {
            case 'profile':
                $template = 'tab_profile.html.twig';
                break;
            case 'user':
                $template = 'tab_user.html.twig';
                break;
            default:
                $template = '404';
        }

        return $this->render('AspetosAdminBundle:Mortician/Mortician:' . $template, array('crudObject' => $crudObject));
    }

    /**
     * @Security("is_granted('mortician.view', crudObject)")
     * @Route("/detail/{id}")
     * @Template()
     * @ParamConverter("crudObject", class="Model:Mortician")
     *
     * @param Mortician $crudObject
     *
     * @return array
     */
    public function detailAction(Mortician $crudObject)
    {
        return array(
            'crudObject' => $crudObject,
            'icon' => $this->getOption('icon'),
            'title' => $this->getOption('title')
        );
    }

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
        $object = new Mortician();

        return $this->formHandler($object, $request, true);
    }

    /**
     * Edit action
     *
     * @ParamConverter("crudObject", class="Model:Mortician")
     * @Route("/edit/{id}")
     *
     * @param Mortician $crudObject
     * @param Request   $request
     *
     * @return RedirectResponse|Response
     */
    public function editAction(Mortician $crudObject, Request $request)
    {
        return $this->formHandler($crudObject, $request, false);
    }

    /**
     * @Route("/delete/{id}")
     * @ParamConverter("crudObject", class="Model:Mortician")
     * @Method({"GET", "DELETE"})
     *
     * @param Mortician $crudObject
     * @param Request   $request
     *
     * @return RedirectResponse
     */
    public function deleteAction(Mortician $crudObject, Request $request)
    {
        return $this->deleteHandler($crudObject, $request);
    }

    /**
     * @Secure("ROLE_ADMIN")
     * @Route("/list")
     * @Route("/")
     * @Template()
     *
     * @return array
     */
    public function listAction()
    {
        return parent::listAction();
    }

    /**
     * Grid action
     *
     * @Secure("ROLE_ADMIN")
     * @Route("/grid")
     * @return Response
     */
    public function gridAction()
    {
        return parent::gridAction();
    }

    /**
     * @param Form $form
     * @param Mortician $crudObject
     */
    protected function prePersist(Form $form, $crudObject)
    {
        dump($crudObject);
        dump($crudObject->getLogo());
        dump($crudObject->getAvatar());
        $crudObject->setLogo($this->saveImage($crudObject->getLogo()));
        $crudObject->setAvatar($this->saveImage($crudObject->getAvatar()));
        dump($crudObject->getLogo());
        dump($crudObject->getAvatar());
        die();
    }

    /**
     * @param Media $media
     * @return Media
     * @throws \Cwd\MediaBundle\MediaException
     */
    protected function saveImage(Media $media)
    {
        $image = null;
        $file = $media->getFilename();

        $mediaService = $this->get('cwd.media.service');

        if ($file instanceof UploadedFile) {
            $location = tempnam('/tmp', 'aspetos');
            $file->move(dirname($location), basename($location));

            $image = $mediaService->create($location, true);
            unlink($location);
        } else if ($media->getId() != null) {
            $image = $mediaService->find($media->getId());
            dump($mediaService->find($media->getId()));
        }

        return $image;
    }
}