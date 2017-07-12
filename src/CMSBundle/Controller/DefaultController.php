<?php

namespace CMSBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class DefaultController extends Controller
{
    /**
     * @Route("/")
     */
    public function indexAction()
    {
        if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')){
            return $this->redirectToRoute('page_index');
        }
        return $this->render('CMSBundle:Default:index.html.twig');
    }

    /**
     * @Route("/{lien}")
     */
    public function redirectAction($lien)
    {
        return $this->redirect($this->generateUrl('page_show_lien',['lien'=>$lien]));
    }
}
