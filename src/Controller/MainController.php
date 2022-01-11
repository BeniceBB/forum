<?php

namespace App\Controller;

use App\Entity\Blog;
use App\Entity\Author;
use App\Entity\User;
use App\Form\Type\BlogFormType;
use App\Form\Type\UserFormType;
use App\Repository\BlogRepository;
use App\Repository\UserRepository;
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
use Symfony\Component\Form\FormBuilderInterface;

class MainController extends AbstractController
{
    /**
     * @Route("/")
     *
     * @param BlogRepository $blogRepository
     * @param UserRepository $userRepository
     *
     * @return Response
     */
    public function index(BlogRepository $blogRepository, userRepository $userRepository)
    {
//        dump($blogRepository->findAll()[0]->getUser());
//        exit;
        return $this->render('list.html.twig', ['blogs' => $blogRepository->findAll()]);
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
    public function viewBlog(Blog $blog, Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger)
    {
//        $form = $this->createForm(BlogFormType::class, $blog);

//        $form->handleRequest($request);
//        if ($form->isSubmitted() && $form->isValid()) {
//            $blog      = $form->getData();
//
//            $entityManager->persist($blog);
//            $entityManager->flush();
//            $this->addFlash('success', 'Post was edited!');
//        }

        return $this->render('view.html.twig', [
//            'form' => $form->createView(),
            'title' => $blog->getTitle(),
            'body' => $blog->getBody(),
            'user' => $blog->getUser(),
        ]);
    }

    // Users:

//    /**
//     * @Route("/createAuthor")
//     *
//     * @param Request $request
//     *
//     * @return Response
//     */
//    public function createAuthor(Request $request, EntityManagerInterface $entityManager)
//    {
//        $form = $this->createForm(AuthorFormType::class, new Author());
//        $form->handleRequest($request);
//        if ($form->isSubmitted() && $form->isValid()) {
//            $author = $form->getData();
//            $entityManager->persist($author);
//            $entityManager->flush();
//            $this->addFlash('success', 'Author was created!');
//            return $this->redirectToRoute('app_main_index');
//        }
//
//
//        return $this->render('createAuthor.html.twig', [
//            'form' => $form->createView()
//        ]);
//    }

    /**
     * @Route("/viewUser/{id}")
     *
     * @ParamConverter("user", class="App:User")
     *
     * @return Response
     */
    public function viewUser(User $user, Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger)
    {
        $form = $this->createForm(UserFormType::class, $user);
//
//        $form->handleRequest($request);
//        if ($form->isSubmitted() && $form->isValid()) {
//            $author      = $form->getData();
//
//            $entityManager->persist($author);
//            $entityManager->flush();
//            $this->addFlash('success', 'Author was edited!');
//        }

        return $this->render('viewUser.html.twig', [
            'form' => $form->createView(),
            'username' => $user->getUsername(),
            'id' => $user->getId(),
        ]);
    }
}