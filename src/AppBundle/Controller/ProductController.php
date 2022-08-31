<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Invoice;
use AppBundle\Entity\Product;
use AppBundle\Entity\Warehouse;
use AppBundle\Entity\Category;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Doctrine\ORM\Query\ResultSetMapping;
use Doctrine\ORM\Query\ResultSetMappingBuilder;

class ProductController extends Controller
{
    /**
     * @Route("/product/list", name="product_list")
     */
    public function listAction(Request $request)
    {
        $repository = $this->getDoctrine()->getRepository(Product::class);
        $products = $repository->findAll();
        return $this->render('product/list.html.twig', array('products' => $products));
    }

    /**
     * @Route("/product/new", name="product_new")
     */
    public function newAction(Request $request)
    {
        $product = new Product();
        $form = $this->createFormBuilder($product)
            ->add('name', TextType::class, array('label' => 'Name', 'attr' => array('class' => 'form-control')))
            ->add('upc', TextType::class, array('label' => 'UPC', 'attr' => array('class' => 'form-control')))
            ->add('amount', TextType::class, array('label' => 'Amount', 'attr' => array('class' => 'form-control')))
            ->add('cost', TextType::class, array('label' => 'Cost', 'attr' => array('class' => 'form-control')))
            ->add('sell_price', TextType::class, array('label' => 'Sell price', 'attr' => array('class' => 'form-control')))
            ->add('weight_gr', TextType::class, array('label' => 'Weight gr', 'attr' => array('class' => 'form-control')))
            ->add('weight_oz', TextType::class, array('label' => 'Weight Oz', 'attr' => array('class' => 'form-control')))
            ->add('weight_lb', TextType::class, array('label' => 'Weight Lb', 'attr' => array('class' => 'form-control')))
            ->add('brand', TextType::class, array('label' => 'Brand', 'attr' => array('class' => 'form-control')))
            ->add('description_en', TextareaType::class, array('label' => 'Description en', 'attr' => array('class' => 'form-control')))
            ->add('description_es', TextareaType::class, array('label' => 'Description es', 'attr' => array('class' => 'form-control')))
            ->add('photo', FileType::class, array('label' => 'Photo', 'attr' => array('class' => 'form-control')))
            ->add('exp_date', DateTimeType::class, array('label' => 'Exp date', 'attr' => array('class' => 'form-control')))
            ->add('category', 'entity', array(
                'class' => 'AppBundle:Category',
                'property' => 'description', 'label' => 'Category', 'attr' => array('class' => 'form-control')
            ))
            ->add('invoice', 'entity', array(
                'class' => 'AppBundle:Invoice',
                'property' => 'id', 'label' => 'Invoice', 'attr' => array('class' => 'form-control')
            ))
            ->add('save', SubmitType::class, array('label' => 'Create', 'attr' => array('class' => 'btn btn-primary mt-3')))
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $product = $form->getData();
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($product);
            $entityManager->flush();
            return $this->redirectToRoute('product_list');
        }
        return $this->render('product/new.html.twig', array('form' => $form->createView()));
    }

    /**
     * @Route("/product/show/{id}", name="product_show")
     */
    public function showAction($id)
    {
        $product = $this->getDoctrine()->getRepository(Product::class)->find($id);
        return $this->render('product/show.html.twig', array('product' => $product));
    }

    /**
     * @Route("/product/edit/{id}", name="product_edit")
     * @Method({"GET","POST"})
     */
    public function editAction(Request $request, $id)
    {
        $product = $this->getDoctrine()->getRepository(Product::class)->find($id);
        $form = $this->createFormBuilder($product)
            ->add('name', TextType::class, array('label' => 'Name', 'attr' => array('class' => 'form-control')))
            ->add('upc', TextType::class, array('label' => 'UPC', 'attr' => array('class' => 'form-control')))
            ->add('amount', TextType::class, array('label' => 'Amount', 'attr' => array('class' => 'form-control')))
            ->add('cost', TextType::class, array('label' => 'Cost', 'attr' => array('class' => 'form-control')))
            ->add('sell_price', TextType::class, array('label' => 'Sell price', 'attr' => array('class' => 'form-control')))
            ->add('weight_gr', TextType::class, array('label' => 'Weight gr', 'attr' => array('class' => 'form-control')))
            ->add('weight_oz', TextType::class, array('label' => 'Weight Oz', 'attr' => array('class' => 'form-control')))
            ->add('weight_lb', TextType::class, array('label' => 'Weight Lb', 'attr' => array('class' => 'form-control')))
            ->add('brand', TextType::class, array('label' => 'Brand', 'attr' => array('class' => 'form-control')))
            ->add('description_en', TextareaType::class, array('label' => 'Description en', 'attr' => array('class' => 'form-control')))
            ->add('description_es', TextareaType::class, array('label' => 'Description es', 'attr' => array('class' => 'form-control')))
            ->add('photo', FileType::class, array('data_class' => null, 'label' => 'Photo', 'attr' => array('class' => 'form-control')))
            ->add('exp_date', DateTimeType::class, array('label' => 'Exp date', 'attr' => array('class' => 'form-control')))
            ->add('category', 'entity', array(
                'class' => 'AppBundle:Category',
                'property' => 'description', 'label' => 'Category', 'attr' => array('class' => 'form-control')
            ))
            ->add('invoice', 'entity', array(
                'class' => 'AppBundle:Invoice',
                'property' => 'id', 'label' => 'Invoice', 'attr' => array('class' => 'form-control')
            ))
            ->add('save', SubmitType::class, array('label' => 'Create', 'attr' => array('class' => 'btn btn-primary mt-3')))
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();
            return $this->redirectToRoute('product_list');
        }
        return $this->render('product/edit.html.twig', array('form' => $form->createView()));
    }

    /**
     * @Route("/product/delete/{id}", name="product_delete")
     * @Method({"GET"})
     */
    public function deleteAction(Request $request, $id)
    {
        $product = $this->getDoctrine()->getRepository(Product::class)->find($id);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($product);
        $entityManager->flush();
        $response = new Response();
        $response->send();
        return $this->redirectToRoute('product_list');
    }

    /**
     * @Route("/product/report", name="product_report")
     */
    public function reportAction(Request $request)
    {
        $form = $this->createFormBuilder()
            ->add('name', TextType::class, array('label' => 'Name', 'attr' => array('class' => 'form-control'), 'required' => false))
            ->add('category', 'entity', array(
                'class' => 'AppBundle:Category',
                'property' => 'description', 'label' => 'Category', 'attr' => array('class' => 'form-control'), 'required' => false
            ))
            ->add('warehouse', 'entity', array(
                'class' => 'AppBundle:Warehouse',
                'property' => 'name', 'label' => 'Warehouse', 'attr' => array('class' => 'form-control'), 'required' => false
            ))
            ->add('save', SubmitType::class, array('label' => 'Create', 'attr' => array('class' => 'btn btn-primary mt-3')))
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $rsm = new ResultSetMapping();
            $entityManager = $this->getDoctrine()->getManager();
            $rsm = new ResultSetMappingBuilder($entityManager);
            $rsm->addRootEntityFromClassMetadata('AppBundle\Entity\Product', 'p');
            $rsm->addJoinedEntityFromClassMetadata('AppBundle\Entity\Invoice', 'i', 'p', 'invoice', array('id' => 'invoice_id'));
            $rsm->addJoinedEntityFromClassMetadata('AppBundle\Entity\Warehouse', 'w', 'i', 'warehouse', array('id' => 'warehouse_id'));
            $rsm->addJoinedEntityFromClassMetadata('AppBundle\Entity\Category', 'c', 'p', 'category', array('id' => 'category_id'));
            $query = $entityManager->createNativeQuery("SELECT p.name, w.name as wname, p.amount, c.description
            FROM product p
            INNER JOIN invoice i on i.id = p.invoice_id
            INNER JOIN warehouse w on w.id=i.warehouse_id
            INNER JOIN category c on c.id=p.category_id
            WHERE (p.category_id=? and w.id=?)", $rsm);
            $query->setParameter(1, $data['category']);
            $query->setParameter(2, $data['warehouse']);
            $products = $query->getResult();
            return $this->render('report/products.html.twig', array('products' => $products, 'form' => $form->createView()));
        }
        $repository = $this->getDoctrine()->getRepository(Product::class);
        $products = $repository->findAll();
        return $this->render('report/products.html.twig', array('products' => $products, 'form' => $form->createView()));
    }
}
