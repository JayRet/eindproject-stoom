<?php

namespace App\Controller;

use App\Entity\Message;
use App\Entity\User;
use App\Repository\ConversationRepository;
use App\Repository\MessageRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

class MessagesController extends AbstractController
{
    #[Route('/messages', name: 'app_messages')]
    public function index(Request $request, #[CurrentUser] User $user, UserRepository $userRepository, ConversationRepository $conversationRepository, MessageRepository $messageRepository, EntityManagerInterface $entityManager): Response
    {
        $conversationId = $request->query->get('id');
        $conversation = $conversationRepository->findOneBy(['id' => $conversationId]);
        $conversationsCollectionArray = $user->getConversations();

        $unreadMessages = $messageRepository->findBy(['isRead' => false,'isRead' => null]);

        foreach ($unreadMessages as $message) {
            $message->setIsRead(true);
        }

        $messages = null;
        $blocked = true;

        if ($conversationsCollectionArray->contains($conversation)) {
            if ($request->isMethod('get') && $conversation != null) {
                    $messages = $conversation->getMessages();
            }

            if (in_array($user, $userRepository->findByInviteBlocked())) {
                if ($request->isMethod('post')) {

                    if (!$conversation->isIsAccepted()) {
                        if ($request->request->get('accept_friend_request') === 'accept') {
                            $conversation->setIsAccepted(true);
                            $conversation->getMessages()->clear();
                        }

                        if ($request->request->get('accept_friend_request') === 'decline') {
                            $requestedFriend = $request->request->get('friend_name');
                            $user->removeFriendRequest($requestedFriend);

                            $conversation->setIsAccepted(false);
                            $conversation->getMessages()->clear();
                        }
                    }

                    $messageContent = $request->request->get('content');

                    if (isset($messageContent) && $conversation->isIsAccepted()) {
                        $message = new Message();
                        $message
                            ->setSender($user)
                            ->setContent($messageContent)
                            ->setConversation($conversation)
                            ->isIsRead(false)
                            /* ->addReplyOn($reply) */
                        ;

                        $entityManager->persist($message);
                        $entityManager->flush();
                    }
                }
                $blocked = false;
            }
        }
        
        return $this->render('messages/index.html.twig', [
            'messages' => $messages,
            'conversations' => $conversationsCollectionArray->getValues(),
            'blocked' => $blocked,
        ]);
    }
}
