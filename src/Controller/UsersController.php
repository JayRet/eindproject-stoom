<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

class UsersController extends AbstractController
{
    #[Route('/users', name: 'app_users')]
    public function index(Request $request,EntityManagerInterface $entityManager, #[CurrentUser] User $user): Response
    {
        $repository = $entityManager->getRepository(User::class);
        $users = $repository->findBy(['blockStatus' => 2]); // 2 is socialy blocked TODO: make cleaner FIX: this doesnt work

        if ($request)
        {
            $friendName = $request->query->get('requested_friend');
            $friend = $repository->findOneBy(['username' => $friendName]);
            $user->addFriend($friend);

            $entityManager->persist($user); // QUESTION: ask koen if this is necesary
            $entityManager->flush();
        }

        return $this->render('users/index.html.twig', [
            'users' => $users,
        ]);
    }

    #[Route('/friends', name: 'app_friends')]
    public function friends(#[CurrentUser] User $user): Response
    {
        $friends = $user->getFriends();

        return $this->render('users/index.html.twig', [
            'users' => $friends,
        ]);
    }
}
