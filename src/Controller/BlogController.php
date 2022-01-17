<?php

namespace App\Controller;

use App\Entity\Blog;
use App\Form\Type\BlogFormType;
use App\Repository\BlogRepository;
use App\Services\BlogContentManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Contracts\Translation\TranslatorInterface;

class BlogController extends AbstractController
{
    private $entityManager;
    private $translator;

    public function __construct(EntityManagerInterface $entityManager, TranslatorInterface $translator, private ManagerRegistry $doctrine)
    {
        $this->entityManager = $entityManager;
        $this->translator = $translator;
    }

    /**
     * @Route("{_locale}/")
     *
     * @param BlogRepository $blogRepository
     *
     * @return Response
     */
    public function index(BlogRepository $blogRepository): Response
    {
        $em = $this->doctrine->getManager();
        $repoBlogs = $em->getRepository(Blog::class);
        $totalBlogs = $repoBlogs->createQueryBuilder('a')
            ->select('count(a.id)')
            ->getQuery()
            ->getSingleScalarResult();

        return $this->render('blog/list.html.twig', ['blogs' => $blogRepository->findAll(), 'post_amount' => $this->translator->trans('post.amount', ['amount' => $totalBlogs])]);
    }

    /**
     * @Route("/{_locale}/create")
     *
     * @param Request $request
     *
     * @return Response
     */
    public function createBlog(Request $request): Response
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
                $this->addFlash('success', $this->translator->trans('post.created'));
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
            $this->addFlash('success', $this->translator->trans('post.deleted'));
        }
        return $this->redirectToRoute('app_blog_index');
    }

    /**
     * @Route("/view/{id}")
     *
     * @ParamConverter("blog", class="App:Blog")
     */
    public function viewBlog(BlogContentManager $blogContentManager, int $id): Response
    {
        return $this->render('blog/view.html.twig', ['blog' => $blogContentManager->getBlogContent($id)]);
    }
}