<?php

namespace CMSBundle\Controller;

use CMSBundle\Entity\Page;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Page controller.
 *
 * @Route("page")
 */
class PageController extends Controller
{
    /**
     * Lists all page entities.
     *
     * @Route("/", name="page_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $pages = $em->getRepository('CMSBundle:Page')->findBy(['user'=>$this->getUser()]);

        return $this->render('page/index.html.twig', array(
            'pages' => $pages,
        ));
    }

    /**
     * Creates a new page entity.
     *
     * @Route("/new", name="page_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $page = new Page();
        $page->setUser($this->getUser());
        $form = $this->createForm('CMSBundle\Form\PageType', $page);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($page);
            $em->flush();

            return $this->redirectToRoute('page_show', array('id' => $page->getId()));
        }

        return $this->render('page/new.html.twig', array(
            'page' => $page,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a page entity.
     *
     * @Route("/{id}/p/", name="page_show_lien_protected", requirements={"id": "\d+"})
     * @Method({"GET","POST"})
     */
    public function showProtectedAction(Request $request, Page $page)
    {
        $redirection = $this->redirectIfNotPublic($page);
        if(!empty($redirection)) {
            return $redirection;
        }
        // Si la page n'est pas protégée, on redirige vers la vue adequat
        if(!$page->getTopPrivate()) {
            return $this->redirectToRoute('page_show_lien',['lien'=>$page->getLien()]);
        }

        $password = $page->getPassword();

        // On crée le formulaire de demande de password
        $form = $this->createFormBuilder($page)
            ->setAction($this->generateUrl('page_show_lien_protected', ['id' => $page->getId()]))
            ->add("password",null,['required'=>true,'data'=>null])
            ->setMethod('POSt')
            ->getForm();
        $form->handleRequest($request);

        $topAffichePage = false;
        // Si le form est bon
        if ($form->isSubmitted() && $form->isValid()) {
            if($password != $page->getPassword()) {
                $form->addError(new FormError("Mauvais password"));
            } else {
                $topAffichePage =true;
            }
        }

        return $this->render('page/show_protected.html.twig', [
            'topAffichePage' => $topAffichePage,
            'form'=> $form->createView(),
            'page'=>$page,
        ]);
    }

    /**
     * Finds and displays a page entity.
     *
     * @Route("/{id}", name="page_show", requirements={"id": "\d+"})
     * @Method("GET")
     */
    public function showAction(Page $page)
    {
        $redirection = $this->redirectIfNotPublic($page);
        if(!empty($redirection)) {
            return $redirection;
        }
        if($page->getTopPrivate()){
            return $this->redirectToRoute('page_show_lien_protected',['id' => $page->getId()]);
        }

        $deleteForm = $this->createDeleteForm($page);

        return $this->render('page/show.html.twig', array(
            'page' => $page,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Finds and displays a page entity.
     *
     * @Route("/{lien}", name="page_show_lien")
     * @Method({"GET"})
     */
    public function showfromtokenAction(Page $page)
    {
        $redirection = $this->redirectIfNotPublic($page);
        if(!empty($redirection)) {
            return $redirection;
        }
        if($page->getTopPrivate()){
            return $this->redirectToRoute('page_show_lien_protected',['id'=>$page->getId()]);
        }
        return $this->render('page/show.html.twig', array(
            'page' => $page
        ));
    }

    /**
     * Displays a form to edit an existing page entity.
     *
     * @Route("/{id}/edit", name="page_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Page $page)
    {
        $deleteForm = $this->createDeleteForm($page);
        $editForm = $this->createForm('CMSBundle\Form\PageType', $page);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('page_edit', array('id' => $page->getId()));
        }

        return $this->render('page/edit.html.twig', array(
            'page' => $page,
            'form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a page entity.
     *
     * @Route("/{id}", name="page_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Page $page)
    {
        $form = $this->createDeleteForm($page);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($page);
            $em->flush();
        }

        return $this->redirectToRoute('page_index');
    }

    /**
     * Creates a form to delete a page entity.
     *
     * @param Page $page The page entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Page $page)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('page_delete', array('id' => $page->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }

    /**
     * Redirige vers l'accueil si la page n'est pas public (et qu'elle ne nous appartiens pas)
     * @param Page $Page
     */
    private function redirectIfNotPublic(Page $Page) {
        if(!$Page->getTopPublic() && $Page->getUser() != $this->getUser()) {
            return $this->redirectToRoute('homepage');
        }
    }
}
