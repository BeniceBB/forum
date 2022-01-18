<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SearchController extends AbstractController
{

    #[Route('/search', name: 'search')]
    public function searchBar()
    {
        $form = $this->createFormBuilder(null)
            ->setAction($this->generateUrl('handleSearch'))
            ->add('query', TextType::class)
            ->add('search', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-dark',
                ]
            ])
            ->getForm();

        return $this->render('search/searchBar.html.twig', [
            'searchform' => $form->createView()
        ]);
    }
//    /**
//     * @Route ("/handleSearch", name="handleSearch")
//     * @param Request $request
//     */
//
//    public function handleSearch(Request $request)
//    {
//        re
//    }

}
