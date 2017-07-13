<?php

namespace CMSBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\BrowserKit\Response;

class DefaultController extends Controller
{
    /**
     * @Route("/t/{lien}", name="redirect_show_lien", requirements={"lien": "[^/]+"})
     */
    public function redirectAction($lien)
    {
        return $this->redirect($this->generateUrl('page_show_lien',['lien'=>$lien]));
    }

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

}
