<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Invoice;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class InvoiceController extends Controller
{
    /**
     * @Route("/invoice/list", name="invoice_list")
     */
    public function listAction(Request $request)
    {
        $repository = $this->getDoctrine()->getRepository(Invoice::class);
        $invoices = $repository->findAll();
        return $this->render('invoice/list.html.twig', array('invoices' => $invoices));
    }

    /**
     * @Route("/invoice/new", name="invoice_new")
     */
    public function newAction(Request $request)
    {
        $invoice = new Invoice();
        $form = $this->createFormBuilder($invoice)
            ->add('date', DateTimeType::class, array('label' => 'Date', 'attr' => array('class' => 'form-control')))
            ->add('warehouse', 'entity', array(
                'class' => 'AppBundle:Warehouse',
                'property' => 'name', 'label' => 'Warehouse', 'attr' => array('class' => 'form-control')
            ))
            ->add('save', SubmitType::class, array('label' => 'Create', 'attr' => array('class' => 'btn btn-primary mt-3')))
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $invoice = $form->getData();
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($invoice);
            $entityManager->flush();
            return $this->redirectToRoute('invoice_list');
        }
        return $this->render('invoice/new.html.twig', array('form' => $form->createView()));
    }

    /**
     * @Route("/invoice/{id}", name="invoice_show")
     */
    public function showAction($id)
    {
        $invoice = $this->getDoctrine()->getRepository(Invoice::class)->find($id);
        return $this->render('invoice/show.html.twig', array('invoice' => $invoice));
    }

    /**
     * @Route("/invoice/edit/{id}", name="invoice_edit")
     * @Method({"GET","POST"})
     */
    public function editAction(Request $request, $id)
    {
        $invoice = $this->getDoctrine()->getRepository(Invoice::class)->find($id);
        $form = $this->createFormBuilder($invoice)
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
            return $this->redirectToRoute('invoice_list');
        }
        return $this->render('invoice/edit.html.twig', array('form' => $form->createView()));
    }

    /**
     * @Route("/invoice/delete/{id}", name="invoice_delete")
     * @Method({"GET"})
     */
    public function deleteAction(Request $request, $id)
    {
        $invoice = $this->getDoctrine()->getRepository(Invoice::class)->find($id);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($invoice);
        $entityManager->flush();
        $response = new Response();
        $response->send();
        return $this->redirectToRoute('invoice_list');
    }
}
