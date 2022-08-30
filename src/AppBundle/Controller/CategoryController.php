<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Category;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class CategoryController extends Controller
{
    /**
     * @Route("/category/list", name="category_list")
     */
    public function listAction(Request $request)
    {
        $repository = $this->getDoctrine()->getRepository(Category::class);
        $categories = $repository->findAll();
        return $this->render('category/list.html.twig', array('categories' => $categories));
    }

    /**
     * @Route("/category/new", name="category_new")
     */
    public function newAction(Request $request)
    {
        $category = new Category();
        $form = $this->createFormBuilder($category)
            ->add('description', TextType::class, array('label' => 'Name', 'attr' => array('class' => 'form-control')))
            ->add('save', SubmitType::class, array('label' => 'Create', 'attr' => array('class' => 'btn btn-primary mt-3')))
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $category = $form->getData();
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($category);
            $entityManager->flush();
            return $this->redirectToRoute('category_list');
        }
        return $this->render('category/new.html.twig', array('form' => $form->createView()));
    }

    /**
     * @Route("/category/{id}", name="category_show")
     */
    public function showAction($id)
    {
        $category = $this->getDoctrine()->getRepository(Category::class)->find($id);
        return $this->render('category/show.html.twig', array('category' => $category));
    }

    /**
     * @Route("/category/edit/{id}", name="category_edit")
     * @Method({"GET","POST"})
     */
    public function editAction(Request $request, $id)
    {
        $category = $this->getDoctrine()->getRepository(category::class)->find($id);
        $form = $this->createFormBuilder($category)
            ->add('description', TextType::class, array('label' => 'Name', 'attr' => array('class' => 'form-control')))
            ->add('save', SubmitType::class, array('label' => 'Create', 'attr' => array('class' => 'btn btn-primary mt-3')))
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();
            return $this->redirectToRoute('category_list');
        }
        return $this->render('category/edit.html.twig', array('form' => $form->createView()));
    }

    /**
     * @Route("/category/delete/{id}", name="category_delete")
     * @Method({"GET"})
     */
    public function deleteAction(Request $request, $id)
    {
        $category = $this->getDoctrine()->getRepository(Category::class)->find($id);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($category);
        $entityManager->flush();
        $response = new Response();
        $response->send();
        return $this->redirectToRoute('category_list');
    }
}
