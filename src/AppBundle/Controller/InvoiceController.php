<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Invoice;
use AppBundle\Entity\Product;
use AppBundle\Entity\Sale;
use AppBundle\Entity\Warehouse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class InvoiceController extends Controller
{
    private function computeFinalCost($products)
    {
        $finalCost = 0;
        foreach ($products as $product) {
            $finalCost += $product->getSellPrice();
        }
        return $finalCost;
    }

    function isInstanceOf($object, array $classnames)
    {
        foreach ($classnames as $classname) {
            if ($object instanceof $classname) {
                return true;
            }
        }
        return false;
    }

    /**
     * @Route("/invoice/list", name="invoice_list")
     */
    public function listAction(Request $request)
    {
        $invoices_list = array();
        $repository = $this->getDoctrine()->getRepository(Invoice::class);
        $invoices = $repository->findAll();
        foreach ($invoices as $invoice) {
            if (!$this->isInstanceOf($invoice, array('AppBundle\Entity\Sale'))) {
                array_push($invoices_list, $invoice);
            }
        }
        return $this->render('invoice/list.html.twig', array('invoices' => $invoices_list));
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
                'property' => 'warehouse_name', 'label' => 'Warehouse', 'attr' => array('class' => 'form-control')
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
     * @Route("/invoice/show/{id}", name="invoice_show")
     */
    public function showAction($id)
    {
        $invoice = $this->getDoctrine()->getRepository(Invoice::class)->find($id);
        $products = $this->getDoctrine()->getRepository(Product::class)->findBy(array('invoice' => $invoice));
        $finalCost = $this->computeFinalCost($products);
        $invoice->setFinalCost($finalCost);
        return $this->render('invoice/show.html.twig', array('invoice' => $invoice, 'products' => $products));
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
                'property' => 'warehouse_name', 'label' => 'Warehouse', 'attr' => array('class' => 'form-control')
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

    /**
     * @Route("/invoice/report", name="invoice_report")
     */
    public function reportAction(Request $request)
    {
        $form = $this->createFormBuilder()
            ->add('type', ChoiceType::class, array(
                'choices' => [1 => 'Invoice', 2 => 'Sale'],
                'label' => 'Type', 'attr' => array('class' => 'form-control'), 'required' => false
            ))
            ->add('warehouse', 'entity', array(
                'class' => 'AppBundle:Warehouse',
                'property' => 'warehouse_name', 'label' => 'Warehouse', 'attr' => array('class' => 'form-control'), 'required' => false
            ))
            ->add('save', SubmitType::class, array('label' => 'Search', 'attr' => array('class' => 'btn btn-primary mt-3')))
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $invoices = array();
            if ($data['warehouse'] != null) {
                $repository = $this->getDoctrine()->getRepository(Warehouse::class);
                $warehouse = $repository->find($data['warehouse']);
                $invoices = $warehouse->getInvoices();
            }
            $invoices_list = array();
            if ($data['type'] != null) {
                if ($data['type'] == 1) {
                    foreach ($invoices as $invoice) {
                        if (!$this->isInstanceOf($invoice, array('AppBundle\Entity\Sale'))) {
                            array_push($invoices_list, $invoice);
                        }
                    }
                    return $this->render('report/invoices.html.twig', array('invoices' => $invoices_list, 'form' => $form->createView()));
                }
                if ($data['type'] == 2) {
                    foreach ($invoices as $invoice) {
                        if ($this->isInstanceOf($invoice, array('AppBundle\Entity\Sale'))) {
                            array_push($invoices_list, $invoice);
                        }
                    }
                    return $this->render('report/invoices.html.twig', array('invoices' => $invoices_list, 'form' => $form->createView()));
                }
            }
            return $this->render('report/invoices.html.twig', array('invoices' => $invoices, 'form' => $form->createView()));
        }
        $repository = $this->getDoctrine()->getRepository(Invoice::class);
        $invoices = $repository->findAll();
        return $this->render('report/invoices.html.twig', array('invoices' => $invoices, 'form' => $form->createView()));
    }
}
