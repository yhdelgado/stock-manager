<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Purchase;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class PurchaseController extends Controller
{
    /**
     * @Route("/purchase/list", name="purchase_list")
     */
    public function listAction(Request $request)
    {
        $repository = $this->getDoctrine()->getRepository(purchase::class);
        $purchases = $repository->findAll();
        return $this->render('purchase/list.html.twig', array('purchases' => $purchases));
    }

    /**
     * @Route("/purchase/new", name="purchase_new")
     */
    public function newAction(Request $request)
    {
        $purchase = new Purchase();
        $form = $this->createFormBuilder($purchase)
            ->add('date', DateTimeType::class, array('label' => 'Date', 'attr' => array('class' => 'form-control')))
            ->add('warehouse', 'entity', array(
                'class' => 'AppBundle:Warehouse',
                'property' => 'name', 'label' => 'Warehouse', 'attr' => array('class' => 'form-control')
            ))
            ->add('save', SubmitType::class, array('label' => 'Create', 'attr' => array('class' => 'btn btn-primary mt-3')))
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $purchase = $form->getData();
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($purchase);
            $entityManager->flush();
            return $this->redirectToRoute('purchase_list');
        }
        return $this->render('purchase/new.html.twig', array('form' => $form->createView()));
    }

    /**
     * @Route("/purchase/{id}", name="purchase_show")
     */
    public function showAction($id)
    {
        $purchase = $this->getDoctrine()->getRepository(Purchase::class)->find($id);
        return $this->render('purchase/show.html.twig', array('purchase' => $purchase));
    }

    /**
     * @Route("/purchase/edit/{id}", name="purchase_edit")
     * @Method({"GET","POST"})
     */
    public function editAction(Request $request, $id)
    {
        $purchase = $this->getDoctrine()->getRepository(Purchase::class)->find($id);
        $form = $this->createFormBuilder($purchase)
            ->add('date', DateTimeType::class, array('label' => 'Date', 'attr' => array('class' => 'form-control')))
            ->add('warehouse', 'entity', array(
                'class' => 'AppBundle:Warehouse',
                'property' => 'name', 'label' => 'Warehouse', 'attr' => array('class' => 'form-control')
            ))
            ->add('save', SubmitType::class, array('label' => 'Create', 'attr' => array('class' => 'btn btn-primary mt-3')))
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();
            return $this->redirectToRoute('purchase_list');
        }
        return $this->render('purchase/edit.html.twig', array('form' => $form->createView()));
    }

    /**
     * @Route("/purchase/delete/{id}", name="purchase_delete")
     * @Method({"GET"})
     */
    public function deleteAction(Request $request, $id)
    {
        $purchase = $this->getDoctrine()->getRepository(Purchase::class)->find($id);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($purchase);
        $entityManager->flush();
        $response = new Response();
        $response->send();
        return $this->redirectToRoute('purchase_list');
    }
}
