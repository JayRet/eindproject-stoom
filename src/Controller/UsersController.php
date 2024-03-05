<?php

namespace App\Controller;

use App\Entity\Conversation;
use App\Entity\Message;
use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

class UsersController extends AbstractController
{
    #[Route('/users', name: 'app_users')]
    public function index(Request $request, EntityManagerInterface $entityManager, #[CurrentUser] User $user, UserRepository $userRepository): Response
    {
        $users = $userRepository->findAllUsersExluding($user);

        if ($request->isMethod('post')) {
            $requestedFriendName = $request->request->get('requested_friend');
            $requestedFriend = $userRepository->findOneBy(['name' => $requestedFriendName]);

            $friendRequestExists = $requestedFriend->getFriendRequests()->exists(function($key, $value,User $user) {
                return $value === $user;
            });

            if (/*! ($requestedFriend === $user || */ $friendRequestExists) {
                return $this->render('users/index.html.twig', [
                    'users' => $users,
                ]);
                die();
            }
                $requestedFriend->addFriendRequest($user);

                $conversation = new Conversation();
                $requestedFriend->addConversation($conversation);
                $user->addConversation($conversation);

                $messageContent = 'new friend request.';
                $message = new Message($user, $messageContent, $conversation);
                $message->setIsFriendRequest(true);

                $entityManager->persist($conversation);
                $entityManager->persist($message);
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

        return $this->render('users/friends.html.twig', [
            'friends' => $friends,
        ]);
    }
}
