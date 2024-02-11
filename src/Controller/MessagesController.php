<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

class MessagesController extends AbstractController
{
    #[Route('/messages', name: 'app_messages')]
    public function index(Request $request, #[CurrentUser] User $user): Response
    {
        $messages = $user;
        $friends = $user->getFriends(); // TODO: check if friends are blocked from messaging

        $request->query->get('user');

        return $this->render('messages/index.html.twig', [
            'chats' => $friends,
            'messages' => $messages,
        ]);
    }
}
