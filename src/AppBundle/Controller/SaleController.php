<?php


namespace AppBundle\Controller;


use AppBundle\Entity\Product;
use AppBundle\Entity\Sale;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

class SaleController extends Controller
{
    private function computeFinalCost($products){
        $finalCost=0;
        foreach ($products as $product){
            $finalCost+=$product->getSellPrice();
        }
        return $finalCost;
    }
    /**
     * @Route("/sale/list", name="sale_list")
     */
    public function listAction(Request $request)
    {
        $repository = $this->getDoctrine()->getRepository(Sale::class);
        $sales = $repository->findAll();
        return $this->render('sale/list.html.twig', array('sales' => $sales));
    }

    /**
     * @Route("/sale/new", name="sale_new")
     */
    public function newAction(Request $request)
    {
        $sale = new Sale();
        $form = $this->createFormBuilder($sale)
            ->add('date', DateTimeType::class, array('label' => 'Date', 'attr' => array('class' => 'form-control')))
            ->add('client', TextType::class, array('label' => 'Client name', 'attr' => array('class' => 'form-control')))
            ->add('warehouse', 'entity', array(
                'class' => 'AppBundle:Warehouse',
                'property' => 'warehouse_name', 'label' => 'Warehouse', 'attr' => array('class' => 'form-control')
            ))
            ->add('save', SubmitType::class, array('label' => 'Create', 'attr' => array('class' => 'btn btn-primary mt-3')))
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $sale = $form->getData();
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($sale);
            $entityManager->flush();
            return $this->redirectToRoute('sale_list');
        }
        return $this->render('sale/new.html.twig', array('form' => $form->createView()));
    }

    /**
     * @Route("/sale/show/{id}", name="sale_show")
     */
    public function showAction($id)
    {
        $sale = $this->getDoctrine()->getRepository(Sale::class)->find($id);
        $products=$this->getDoctrine()->getRepository(Product::class)->findBy(array('invoice'=>$sale));
        $finalCost=$this->computeFinalCost($products);
        $sale->setFinalCost($finalCost);
        return $this->render('sale/show.html.twig', array('sale' => $sale, 'products'=>$products));
    }

    /**
     * @Route("/sale/edit/{id}", name="sale_edit")
     * @Method({"GET","POST"})
     */
    public function editAction(Request $request, $id)
    {
        $sale = $this->getDoctrine()->getRepository(Sale::class)->find($id);
        $form = $this->createFormBuilder($sale)
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
            return $this->redirectToRoute('sale_list');
        }
        return $this->render('sale/edit.html.twig', array('form' => $form->createView()));
    }

    /**
     * @Route("/sale/delete/{id}", name="sale_delete")
     * @Method({"GET"})
     */
    public function deleteAction(Request $request, $id)
    {
        $sale = $this->getDoctrine()->getRepository(Sale::class)->find($id);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($sale);
        $entityManager->flush();
        $response = new Response();
        $response->send();
        return $this->redirectToRoute('sale_list');
    }
}