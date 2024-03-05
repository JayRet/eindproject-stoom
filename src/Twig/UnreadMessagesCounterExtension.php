<?php
namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class UnreadMessagesCounterExtension extends AbstractExtension
{
    public function getFunctions(): array
    {
        return [
            new TwigFunction('unreadMessagesCount', [UnreadMessagesCounterRuntime::class, 'getUnreadMessagesCount']),
        ];
    }
}
