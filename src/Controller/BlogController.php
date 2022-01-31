<?php

namespace App\Controller;

use App\Entity\Blog;
use App\Form\Type\BlogFormType;
use App\Form\Type\SearchFormType;
use App\Repository\BlogRepository;
use App\Services\BlogContentManager;
use App\Services\SearchFilterManager;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Contracts\Translation\TranslatorInterface;

class BlogController extends AbstractController
{
    private EntityManagerInterface $entityManager;
    private TranslatorInterface $translator;
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
    public function index(): Response
    {
        $form = $this->createForm(SearchFormType::class);
        $filteredBlogs = $this->searchFilterManager->getBlogs(['type' => ['all']]);
        $totalFilteredBlogs = $this->searchFilterManager->totalFilteredBlogs(['type' => ['all']]);

        return $this->render('blog/list.html.twig', [
            'blogs' => $filteredBlogs,
            'postAmount' => $this->translator->trans('post.amount', ['amount' => count($filteredBlogs)]),
            'totalFilteredBlogs' => $totalFilteredBlogs,
            'searchForm' => $form->createView(),
            'page' => 0,
        ]);
    }

    /**
     * @Route("/search/{page}", name="blogsearch" )
     *
     * @return Response
     */
    public function search(Request $request, ?int $page = 0): Response
    {
        $form = $this->createForm(SearchFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $filteredBlogs = $this->searchFilterManager->getBlogs($data, $page);
            $totalFilteredBlogs = $this->searchFilterManager->totalFilteredBlogs($data);
            $currentAmountBlogs = $this->searchFilterManager->currentBlogCount($page, $filteredBlogs, $data);

            return $this->json([
                'result' => $this->renderView('blog/blogtable.html.twig', [
                    'blogs' => $filteredBlogs,
                    'postAmount' => $this->translator->trans('post.amount', ['amount' => $currentAmountBlogs]),
                    'totalFilteredBlogs' => $totalFilteredBlogs,
                ]),
                'page' => $page,
                'numberOfBlogs' => count($filteredBlogs),
                'numberOfBlogsPerPage' => $data['postsPerPage'] ?? 5,
            ]);
        }
        return $this->json('error', 503);
    }

    /**
     * @Route("/searchDatabase/{page}", name="blogSearchDatabase" )
     *
     * @return Response
     */
    public function searchDatabase(Request $request, ?int $page = 0): Response
    {
//        $data = ['search' => '', 'type' => ['all'], 'postsPerPage' => 5];
//        $blogs = $this->searchFilterManager->getBlogsWithQuery($data);

        $form = $this->createForm(SearchFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            $filteredBlogs = $this->searchFilterManager->getBlogsWithQuery($data);
            $totalFilteredBlogs = $this->searchFilterManager->totalFilteredBlogs($data);
            $currentAmountBlogs = $this->searchFilterManager->currentBlogCount($page, $filteredBlogs, $data);

            return $this->json([
                'result' => $this->renderView('blog/blogtable.html.twig', [
                    'blogs' => $filteredBlogs,
                    'postAmount' => $this->translator->trans('post.amount', ['amount' => $currentAmountBlogs]),
                    'totalFilteredBlogs' => $totalFilteredBlogs,
                ]),
                'page' => $page,
                'numberOfBlogs' => count($filteredBlogs),
                'numberOfBlogsPerPage' => $data['postsPerPage'] ?? 5,
            ]);
        }
        return $this->render('search/searchDatabase.html.twig', [
            'blogs' => $blogs,
            'postAmount' => $this->translator->trans('post.amount', ['amount' => 0]),
            'totalFilteredBlogs' => count($blogs),
            'searchForm' => $form->createView(),
            'page' => $page,
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
        $this->entityManager->remove($blog);
        $this->entityManager->flush();
        $this->addFlash('success', $this->translator->trans('post.deleted'));

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