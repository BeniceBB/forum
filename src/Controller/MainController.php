<?php

namespace App\Controller;

use App\Entity\Blog;
use App\Entity\Author;
use App\Form\Type\BlogFormType;
use App\Form\Type\AuthorFormType;
use App\Repository\BlogRepository;
use App\Repository\AuthorRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\JsonResponse;

class MainController extends AbstractController
{
    /**
     * @Route("/")
     *
     * @param BlogRepository $blogRepository
     * @param AuthorRepository $authorRepository
     *
     * @return Response
     */
    public function index(BlogRepository $blogRepository, AuthorRepository $authorRepository)
    {
        return $this->render('list.html.twig', ['blogs' => $blogRepository->findAll(), 'authors' => $authorRepository->findAll()]);
    }

    /**
     * @Route("/create")
     *
     * @param Request $request
     *
     * @return Response
     */
    public function createBlog(Request $request, EntityManagerInterface $entityManager)
    {
        $form = $this->createForm(BlogFormType::class, new Blog());
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $blog = $form->getData();
            $entityManager->persist($blog);
            $entityManager->flush();
            $this->addFlash('success', 'Post was created!');
            return $this->redirectToRoute('app_main_index');
        }


        return $this->render('create.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/delete/{id}", name="app_blog_delete")
     *
     * @param Blog                   $blog
     * @param EntityManagerInterface $em
     *
     * @return RedirectResponse
     */
    public function deleteBlog(Blog $blog, EntityManagerInterface $em): RedirectResponse
    {
        $em->remove($blog);
        $em->flush();
        $this->addFlash('success', 'Post is deleted!');

        return $this->redirectToRoute('app_main_index');
    }

    /**
     * @Route("/view/{id}")
     *
     * @ParamConverter("blog", class="App:Blog")
     *
     * @return Response
     */
    public function editBlog(Blog $blog, Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger)
    {
        $form = $this->createForm(BlogFormType::class, $blog);

//        $form->handleRequest($request);
//        if ($form->isSubmitted() && $form->isValid()) {
//            $blog      = $form->getData();
//
//            $entityManager->persist($blog);
//            $entityManager->flush();
//            $this->addFlash('success', 'Post was edited!');
//        }

        return $this->render('view.html.twig', [
            'form' => $form->createView(),
            'title' => $blog->getTitle(),
            'body' => $blog->getBody(),
            'author' => $blog->getAuthorId(),
        ]);
    }

    // Authors:

    /**
     * @Route("/createAuthor")
     *
     * @param Request $request
     *
     * @return Response
     */
    public function createAuthor(Request $request, EntityManagerInterface $entityManager)
    {
        $form = $this->createForm(AuthorFormType::class, new Author());
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $author = $form->getData();
            $entityManager->persist($author);
            $entityManager->flush();
            $this->addFlash('success', 'Author was created!');
            return $this->redirectToRoute('app_main_index');
        }


        return $this->render('createAuthor.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/viewAuthor/{id}")
     *
     * @ParamConverter("author", class="App:Author")
     *
     * @return Response
     */
    public function editAuthor(Author $author, Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger)
    {
        $form = $this->createForm(AuthorFormType::class, $author);
//
//        $form->handleRequest($request);
//        if ($form->isSubmitted() && $form->isValid()) {
//            $author      = $form->getData();
//
//            $entityManager->persist($author);
//            $entityManager->flush();
//            $this->addFlash('success', 'Author was edited!');
//        }

        return $this->render('viewAuthor.html.twig', [
            'form' => $form->createView(),
            'name' => $author->getName(),
            'id' => $author->getId(),
        ]);
    }
}