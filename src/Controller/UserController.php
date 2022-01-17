<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class UserController extends AbstractController
{
    /**
     * @Route("/view/user/{username}")
     *
     * @ParamConverter("user", class="App:User")
     *
     * @param User $user
     * @return Response
     */

    public function viewUser(User $user): Response
    {
        return $this->render('user/view.html.twig', ['user' => $user]);
    }
}