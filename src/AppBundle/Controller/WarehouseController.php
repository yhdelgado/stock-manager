<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Warehouse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class WarehouseController extends Controller
{
    /**
     * @Route("/warehouse/list", name="warehouse_list")
     */
    public function listAction(Request $request)
    {
        $repository = $this->getDoctrine()->getRepository(Warehouse::class);
        $warehouses = $repository->findAll();
        return $this->render('warehouse/list.html.twig', array('warehouses' => $warehouses));
    }

    /**
     * @Route("/warehouse/new", name="warehouse_new")
     */
    public function newAction(Request $request)
    {
        $warehouse = new Warehouse();
        $form = $this->createFormBuilder($warehouse)
            ->add('name', TextType::class, array('label' => 'Name', 'attr' => array('class' => 'form-control')))
            ->add('owner', TextType::class, array('label' => 'Owner', 'attr' => array('class' => 'form-control')))
            ->add('address', TextType::class, array('label' => 'Address', 'attr' => array('class' => 'form-control')))
            ->add('phone', TextType::class, array('label' => 'Phone', 'attr' => array('class' => 'form-control')))
            ->add('latitude', TextType::class, array('label' => 'Lat', 'attr' => array('class' => 'form-control')))
            ->add('longitude', TextType::class, array('label' => 'Long', 'attr' => array('class' => 'form-control')))
            ->add('enterprise', 'entity', array(
                'class' => 'AppBundle:Enterprise',
                'property' => 'name', 'label' => 'Enterprise', 'attr' => array('class' => 'form-control')
            ))
            ->add('save', SubmitType::class, array('label' => 'Create', 'attr' => array('class' => 'btn btn-primary mt-3')))
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $warehouse = $form->getData();
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($warehouse);
            $entityManager->flush();
            return $this->redirectToRoute('warehouse_list');
        }
        return $this->render('warehouse/new.html.twig', array('form' => $form->createView()));
    }

    /**
     * @Route("/warehouse/{id}", name="warehouse_show")
     */
    public function showAction($id)
    {
        $warehouse = $this->getDoctrine()->getRepository(Warehouse::class)->find($id);
        return $this->render('warehouse/show.html.twig', array('warehouse' => $warehouse));
    }

    /**
     * @Route("/warehouse/edit/{id}", name="warehouse_edit")
     * @Method({"GET","POST"})
     */
    public function editAction(Request $request, $id)
    {
        $warehouse = $this->getDoctrine()->getRepository(Warehouse::class)->find($id);
        $form = $this->createFormBuilder($warehouse)
            ->add('name', TextType::class, array('label' => 'Name', 'attr' => array('class' => 'form-control')))
            ->add('owner', TextType::class, array('label' => 'Owner', 'attr' => array('class' => 'form-control')))
            ->add('address', TextType::class, array('label' => 'Address', 'attr' => array('class' => 'form-control')))
            ->add('phone', TextType::class, array('label' => 'Phone', 'attr' => array('class' => 'form-control')))
            ->add('latitude', TextType::class, array('label' => 'Lat', 'attr' => array('class' => 'form-control')))
            ->add('longitude', TextType::class, array('label' => 'Long', 'attr' => array('class' => 'form-control')))
            ->add('enterprise', 'entity', array(
                'class' => 'AppBundle:Enterprise',
                'property' => 'name', 'label' => 'Enterprise', 'attr' => array('class' => 'form-control')
            ))
            ->add('save', SubmitType::class, array('label' => 'Update', 'attr' => array('class' => 'btn btn-primary mt-3')))
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();
            return $this->redirectToRoute('warehouse_list');
        }
        return $this->render('warehouse/edit.html.twig', array('form' => $form->createView()));
    }

    /**
     * @Route("/warehouse/delete/{id}", name="warehouse_delete")
     * @Method({"GET"})
     */
    public function deleteAction(Request $request, $id)
    {
        $warehouse = $this->getDoctrine()->getRepository(Warehouse::class)->find($id);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($warehouse);
        $entityManager->flush();
        $response = new Response();
        $response->send();
        return $this->redirectToRoute('warehouse_list');
    }
}
