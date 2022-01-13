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
        $user = $this->getUser();

        if (!$this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY')) {
            $blog = new Blog();
            $form = $this->createForm(BlogFormType::class, $blog);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $blog->setUser($user);
                $entityManager->persist($blog);
                $entityManager->flush();
                $this->addFlash('success', 'Post was created!');
                return $this->redirectToRoute('app_main_index');
            }

            return $this->render('create.html.twig', [
                'form' => $form->createView(),
            ]);
        } else {
            return $this->redirectToRoute('app_main_index');
        }
    }

    /**
     * @Route("/delete/{id}", name="app_blog_delete")
     *
     * @param Blog $blog
     * @param EntityManagerInterface $em
     *
     * @return RedirectResponse
     */
    public function deleteBlog(Blog $blog, EntityManagerInterface $em): RedirectResponse
    {
        if (!$this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY')) {
            $em->remove($blog);
            $em->flush();
            $this->addFlash('success', 'Post is deleted!');
        }
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
        return $this->render('view.html.twig', [
            'title' => $blog->getTitle(),
            'body' => $blog->getBody(),
            'user' => $blog->getUser(),
        ]);
    }


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

        return $this->render('viewUser.html.twig', [
            'form' => $form->createView(),
            'username' => $user->getUsername(),
            'id' => $user->getId(),
        ]);
    }
}