<?php

namespace App\Controller;

use App\Entity\Blog;
use App\Form\Type\BlogFormType;
use App\Form\Type\SearchFormType;
use App\Repository\BlogRepository;
use App\Services\BlogContentManager;
use App\Services\SearchFilterManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
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
    private SearchFilterManager $searchFilterManager;

    public function __construct(EntityManagerInterface $entityManager, TranslatorInterface $translator, SearchFilterManager $searchFilterManager)
    {
        $this->entityManager = $entityManager;
        $this->translator = $translator;
        $this->searchFilterManager = $searchFilterManager;
    }

    /**
     * @Route("/")
     *
     * @return Response
     */
    public function index(array $data = [], int $offset = 0): Response
    {
        $form = $this->createForm(SearchFormType::class);
        $filteredBlogs = $this->searchFilterManager->getBlogs($data, $offset);
        return $this->render('blog/list.html.twig', [
            'blogs' => $filteredBlogs,
            'post_amount' => $this->translator->trans('post.amount', ['amount' => count($filteredBlogs)]),
            'searchForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/search", name="blogsearch" )
     *
     * @return Response
     */
    public function search(Request $request, int $offset = 0): Response
    {
        $form = $this->createForm(SearchFormType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $offset = $data['amountfilter'];
        }
        $filteredBlogs = $this->searchFilterManager->getBlogs($data, $offset);
        return $this->json([
            'result' => $this->renderView('blog/blogtable.html.twig', [
            'blogs' => $filteredBlogs,
            'post_amount' => $this->translator->trans('post.amount', ['amount' => count($filteredBlogs)]),

            ]),
            'offset' => $offset,
        ]);
    }

    /**
     * @Route("/create")
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