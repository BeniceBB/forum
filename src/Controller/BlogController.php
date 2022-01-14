<?php

namespace App\Controller;

use App\Entity\Blog;
use App\Form\Type\BlogFormType;
use App\Repository\BlogRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class BlogController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/")
     *
     * @param BlogRepository $blogRepository
     *
     * @return Response
     */
    public function index(BlogRepository $blogRepository)
    {
        return $this->render('blog/list.html.twig', ['blogs' => $blogRepository->findAll()]);
    }

    /**
     * @Route("/create")
     *
     * @param Request $request
     *
     * @return Response
     */
    public function createBlog(Request $request)
    {
        $user = $this->getUser();

        if (!$this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY')) {
            $blog = new Blog();
            $form = $this->createForm(BlogFormType::class, $blog);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $blog->setUser($user);
                $this->entityManager->persist($blog);
                $this->entityManager->flush();
                $this->addFlash('success', 'Post was created!');
                return $this->redirectToRoute('app_blog_index');
            }

            return $this->render('blog/create.html.twig', [
                'form' => $form->createView(),
            ]);
        } else {
            return $this->redirectToRoute('app_blog_index');
        }
    }

    /**
     * @Route("/delete/{id}", name="app_blog_delete")
     *
     * @param Blog $blog
     *
     * @return RedirectResponse
     */
    public function deleteBlog(Blog $blog): RedirectResponse
    {
        if (!$this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY')) {
            $this->entityManager->remove($blog);
            $this->entityManager->flush();
            $this->addFlash('success', 'Post is deleted!');
        }
        return $this->redirectToRoute('app_blog_index');
    }

    /**
     * @Route("/view/{id}")
     *
     * @ParamConverter("blog", class="App:Blog")
     *
     * @return Response
     */
    public function viewBlog(Blog $blog)
    {
        return $this->render('blog/view.html.twig', ['blog' => $blog]);
    }
}