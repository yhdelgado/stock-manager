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
use \Doctrine\Common\Util\Debug;
use \Knp\Snappy\Pdf;
use \Knp\Bundle\SnappyBundle\Snappy\Response\PdfResponse;


class ProductController extends Controller
{
    /**
     * @return string
     */
    private function generateUniqueFileName()
    {
        // md5() reduces the similarity of the file names generated by
        // uniqid(), which is based on timestamps
        return md5(uniqid());
    }

    private function computeSellPrice($cost, $category, $amount)
    {
        $sellPrice = 0;
        if ($category == "carnes") {
            $sellPrice = ($cost + ($cost * 0.8) * $amount) + 10;
        } elseif ($category == "electrodomésticos") {
            $sellPrice = ($cost + ($cost * 0.9) * $amount) + 10;
        } elseif ($category == "aseo") {
            $sellPrice = ($cost + ($cost * 0.3) * $amount) + 10;
        } else {
            $sellPrice = ($cost + ($cost * 0.2) * $amount) + 10;
        }
        return $sellPrice;
    }

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
            ->add('productName', TextType::class, array('label' => 'Name', 'attr' => array('class' => 'form-control')))
            ->add('upc', TextType::class, array('label' => 'UPC', 'attr' => array('class' => 'form-control')))
            ->add('amount', TextType::class, array('label' => 'Amount', 'attr' => array('class' => 'form-control')))
            ->add('cost', TextType::class, array('label' => 'Cost', 'attr' => array('class' => 'form-control')))
            ->add('weightGr', TextType::class, array('label' => 'Weight gr', 'attr' => array('class' => 'form-control')))
            ->add('weightOz', TextType::class, array('label' => 'Weight Oz', 'attr' => array('class' => 'form-control')))
            ->add('weightLb', TextType::class, array('label' => 'Weight Lb', 'attr' => array('class' => 'form-control')))
            ->add('brand', TextType::class, array('label' => 'Brand', 'attr' => array('class' => 'form-control')))
            ->add('descriptionEn', TextareaType::class, array('label' => 'Description en', 'attr' => array('class' => 'form-control')))
            ->add('descriptionEs', TextareaType::class, array('label' => 'Description es', 'attr' => array('class' => 'form-control')))
            ->add('photo', FileType::class, array('label' => 'Photo', 'attr' => array('class' => 'form-control')))
            ->add('expDate', DateTimeType::class, array('label' => 'Exp date', 'attr' => array('class' => 'form-control')))
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
            $photo = $product->getPhoto();
            $fileName = $this->generateUniqueFileName() . '.' . $photo->guessExtension();
            try {
                $photo->move(
                    $this->getParameter('photo_directory'),
                    $fileName
                );
            } catch (FileException $e) {

            }
            $product->setPhoto($fileName);
            $sellPrice = $this->computeSellPrice(strtolower($product->getCost()), strtolower($product->getCategory()->getDescription()), $product->getAmount());
            $product->setSellPrice($sellPrice);
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
        $photo_dir = "/photo/" . $product->getPhoto();
        return $this->render('product/show.html.twig', array('product' => $product, 'photo' => $photo_dir));
    }

    /**
     * @Route("/product/edit/{id}", name="product_edit")
     * @Method({"GET","POST"})
     */
    public function editAction(Request $request, $id)
    {
        $product = $this->getDoctrine()->getRepository(Product::class)->find($id);
        $form = $this->createFormBuilder($product)
            ->add('productName', TextType::class, array('label' => 'Name', 'attr' => array('class' => 'form-control')))
            ->add('upc', TextType::class, array('label' => 'UPC', 'attr' => array('class' => 'form-control')))
            ->add('amount', TextType::class, array('label' => 'Amount', 'attr' => array('class' => 'form-control')))
            ->add('cost', TextType::class, array('label' => 'Cost', 'attr' => array('class' => 'form-control')))
            ->add('sellPrice', TextType::class, array('label' => 'Sell price', 'attr' => array('class' => 'form-control')))
            ->add('weightGr', TextType::class, array('label' => 'Weight gr', 'attr' => array('class' => 'form-control')))
            ->add('weightOz', TextType::class, array('label' => 'Weight Oz', 'attr' => array('class' => 'form-control')))
            ->add('weightLb', TextType::class, array('label' => 'Weight Lb', 'attr' => array('class' => 'form-control')))
            ->add('brand', TextType::class, array('label' => 'Brand', 'attr' => array('class' => 'form-control')))
            ->add('descriptionEn', TextareaType::class, array('label' => 'Description en', 'attr' => array('class' => 'form-control')))
            ->add('descriptionEs', TextareaType::class, array('label' => 'Description es', 'attr' => array('class' => 'form-control')))
            ->add('photo', FileType::class, array('data_class' => null, 'label' => 'Photo', 'attr' => array('class' => 'form-control')))
            ->add('expDate', DateTimeType::class, array('label' => 'Exp date', 'attr' => array('class' => 'form-control')))
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

    private function getProductsByWarehouse($warehouse)
    {
        $repository = $this->getDoctrine()->getRepository(Warehouse::class);
        $warehouse = $repository->find($warehouse);
        $invoices = $warehouse->getInvoices();
        $products = array();
        foreach ($invoices as $invoice) {
            $product_list = $invoice->getProducts();
            foreach ($product_list as $product) {
                array_push($products, $product);
            }
        }
        return $products;
    }

    private function getProductsByCategory($category)
    {
        $repository = $this->getDoctrine()->getRepository(Product::class);
        return $repository->findBy(array('category' => $category));
    }


    /**
     * @Route("/product/report", name="product_report")
     */
    public function reportAction(Request $request)
    {
        $form = $this->createFormBuilder()
            ->add('category', 'entity', array(
                'class' => 'AppBundle:Category',
                'property' => 'description', 'label' => 'Category', 'attr' => array('class' => 'form-control'), 'required' => false
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
            $products = array();
            if ($data['warehouse'] != null) {
                $products_ware = $this->getProductsByWarehouse($data['warehouse']);
                $products = array_merge($products, $products_ware);
            }
            if ($data['category'] != null) {
                $products_cat = $this->getProductsByCategory($data['category']);
                foreach ($products_cat as $product) {
                    if (!in_array($product, $products)) {
                        array_push($products, $product);
                    }

                }
            }
            return $this->render('report/products.html.twig', array('products' => $products, 'form' => $form->createView()));
        }
        $repository = $this->getDoctrine()->getRepository(Product::class);
        $products = $repository->findAll();
        return $this->render('report/products.html.twig', array('products' => $products, 'form' => $form->createView()));
    }

    /**
     * @Route("/product/report/pdf", name="product_report_pdf")
     */
    public function pdfAction()
    {
        $repository = $this->getDoctrine()->getRepository(Product::class);
        $products = $repository->findAll();
        $html = $this->renderView('report/pdf.html.twig', array(
            'products' => $products
        ));
        $filename = sprintf('test-%s.pdf', date('Y-m-d'));

        return new Response(
            $this->get('knp_snappy.pdf')->getOutputFromHtml($html),
            200,
            [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => sprintf('attachment; filename="%s"', $filename),
            ]);
    }
}
