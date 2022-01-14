<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class UserController extends AbstractController
{
    /**
     * @Route("/view/user/{username}")
     *
     * @ParamConverter("user", class="App:User")
     *
     * @return Response
     */

    public function viewUser(User $user, Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger)
    {
        return $this->render('user/view.html.twig', ['user' => $user]);
    }
}