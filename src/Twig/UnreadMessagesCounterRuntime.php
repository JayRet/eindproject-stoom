<?php
namespace App\Twig;

use App\Entity\User;
use App\Repository\MessageRepository;
use Twig\Extension\RuntimeExtensionInterface;

class UnreadMessagesCounterRuntime implements RuntimeExtensionInterface
{
    public function __construct(private MessageRepository $messageRepository)
    {
    }

    public function getUnreadMessagesCount(User $user): int
    {
        $amount = $this->messageRepository->countUnreadMessages(
            $user->getConversations()->getValues()
        );

        return $amount;
    }
}
