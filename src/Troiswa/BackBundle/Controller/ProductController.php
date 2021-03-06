<?php

namespace Troiswa\BackBundle\Controller;

use MyProject\Proxies\__CG__\stdClass;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Troiswa\BackBundle\Entity\Comment;
use Troiswa\BackBundle\Entity\Product;
use Troiswa\BackBundle\Form\CommentType;
use Troiswa\BackBundle\Form\ProductType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Troiswa\BackBundle\Listener\BaseListener;
use Troiswa\BackBundle\Listener\MessagePostEvent;


/**
 * Product controller.
 *
 */
class ProductController extends Controller
{

    /**
     * Lists all Product entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('TroiswaBackBundle:Product')->findAllProductWithCategory();

        return $this->render('TroiswaBackBundle:Product:index.html.twig', array(
            'entities' => $entities,
        ));
    }
    /**
     * Creates a new Product entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity = new Product();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            // without cascade persist
            //$entity->getImage()->upload();

            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('troiswa_back_product_show', array('idprod' => $entity->getId())));
        }

        return $this->render('TroiswaBackBundle:Product:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a Product entity.
     *
     * @param Product $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Product $entity)
    {
        $form = $this->createForm(new ProductType($this->getDoctrine()->getManager()), $entity, array(
            'action' => $this->generateUrl('troiswa_back_product_create'),
            'method' => 'POST',

        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Product entity.
     *
     */
    public function newAction()
    {
        $entity = new Product();
        $form   = $this->createCreateForm($entity);

        return $this->render('TroiswaBackBundle:Product:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }


    // Lorsqu'on a qu'un champ a mappé
    // @ParamConverter("post", class="SensioBlogBundle:Post", options={"id" = "post_id"})
    // Lorsqu'on a plusieurs champs a mappé
    // @ParamConverter("post", options={"mapping": {"date": "date", "slug": "slug"}})
    /**
     * Finds and displays a Product entity.
     * @ParamConverter("entity", options={"mapping": {"idprod": "id"}})
     */
    public function showAction(Product $entity, Request $request)
    {
        $em = $this->getDoctrine()->getManager();


        /*
         $entity = $em->getRepository('TroiswaBackBundle:Product')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Product entity.');
        }
        */

        // On crée l'évènement avec ses 2 arguments
        $event = new MessagePostEvent($entity->getId(), $this->getUser());

        // On déclenche l'évènement
        $this
            ->get('event_dispatcher')
            ->dispatch(BaseListener::messagePost, $event)
        ;


        $deleteForm = $this->createDeleteForm($entity->getId());

        $comment = new Comment();
        $formComment = $this->createAndValidateComment($comment);
        $formComment->handleRequest($request);
        if ($formComment->isValid())
        {
            $em->persist($comment);
            $em->flush();

            $this->get('session')->getFlashBag()->add('success', 'Votre commentaire a bien été ajouté');

            return $this->redirectToRoute('troiswa_back_product_show', ['idprod' => $entity->getId()]);
        }

        $comments = $em->getRepository('TroiswaBackBundle:Comment')->findByUser($this->getUser());

        $comments_by_id = [];
        foreach($comments as $comment)
        {
            $comments_by_id[$comment->getId()] = $comment;
        }

        foreach($comments as $key => $com)
        {
            if ($com->getParent() != NULL)
            {
                $comments_by_id[$com->getParent()->getId()]->children[] = $com; //$comments_by_id[$com->getParent()->getId()] fait référence à $comment dans le foreach du dessus
                unset($comments[$key]);
            }
        }

        return $this->render('TroiswaBackBundle:Product:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
            'comment_form' => $formComment->createView(),
            'comments' => $comments
        ));
    }

    private function createAndValidateComment($comment)
    {
        $user = $this->getUser();
        $comment->setUser($user);
        $formComment = $this->createForm(new CommentType(), $comment)
                            ->add('submit', 'submit', ['label' => 'Ajouter un commentaire']);

        return $formComment;
    }

    /**
     * Displays a form to edit an existing Product entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('TroiswaBackBundle:Product')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Product entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('TroiswaBackBundle:Product:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a Product entity.
    *
    * @param Product $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Product $entity)
    {
        $form = $this->createForm(new ProductType($this->getDoctrine()->getManager()), $entity, array(
            'action' => $this->generateUrl('troiswa_back_product_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing Product entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('TroiswaBackBundle:Product')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Product entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('troiswa_back_product_edit', array('id' => $id)));
        }

        return $this->render('TroiswaBackBundle:Product:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    /**
     * Deletes a Product entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {

            $em = $this->getDoctrine()->getManager();
            /* WARNING */
            /*
             Image and product are linked
             2 solutions pour la suppression :
                - faire une jointure entre product et image pour éviter le proxy sur image
                - faire un @preRemove() dans l'entité image
             */
            $entity = $em->getRepository('TroiswaBackBundle:Product')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Product entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('troiswa_back_product'));
    }

    /**
     * Creates a form to delete a Product entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('troiswa_back_product_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }

    /**
     * @ParamConverter("comment", options={"mapping": {"idcom": "id"}})
     * @ParamConverter("product", options={"mapping": {"idprod": "id"}})
     */
    public function commentdeleteAction(Comment $comment, Product $product)
    {
        $em = $this->getDoctrine()->getManager();

        $em->remove($comment);
        $em->flush();

        $this->get('session')->getFlashBag()->add('success', 'Votre commentaire a bien été supprimé');

        return $this->redirectToRoute('troiswa_back_product_show', ['idprod' => $product->getId()]);
    }
}
