<?php

namespace Avanzu\AdminThemeBundle\Controller;

use Avanzu\AdminThemeBundle\Form\FormDemoModelType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class DefaultController
 *
 * @package Avanzu\AdminThemeBundle\Controller
 */
class DefaultController extends AbstractController
{
    /**
     * @Template()
     */
    public function indexAction(): Response
    {
        return $this->render('@AvanzuAdminTheme/Default/index.html.twig');
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function dashboardAction(): Response
    {
        return $this->render('@AvanzuAdminTheme/Default/index.html.twig');
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function uiGeneralAction(): Response
    {
        return $this->render('@AvanzuAdminTheme/Default/index.html.twig');
    }

    public function uiIconsAction(): Response
    {
        return $this->render('@AvanzuAdminTheme/Default/index.html.twig');
    }

    public function formAction(): Response
    {
        $form = $this->createForm(FormDemoModelType::class);
        return $this->render('@AvanzuAdminTheme/Default/form.html.twig', [
                'form' => $form->createView(),
            ]);
    }

    public function loginAction(): Response
    {
        return $this->render('@AvanzuAdminTheme/Default/login.html.twig');
    }
}
