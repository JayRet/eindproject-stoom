<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ApiController extends AbstractController
{
    #[Route('/api/user', name: 'app_api_user')]
    public function apiUser(): Response
    {
        $user = $this->getUser();
        return $this->json([
            'message' => 'You successfully authenticated!',
            'email' => $user->getUserIdentifier(),
        ]);
    }

    #[Route('/api/game/data', name: 'app_api_game_data')]
    public function apiGameData(): Response
    {
        $user = $this->getUser();
        return $this->json([
            'message' => 'You successfully authenticated!',
            'email' => $user->getUserIdentifier(),
        ]);
    }

    #[Route('/api/game/achievement', name: 'app_api_game_achievement')]
    public function apiGameAchievement(): Response
    {
        $user = $this->getUser();
        return $this->json([
            'message' => 'You successfully authenticated!',
            'email' => $user->getUserIdentifier(),
        ]);
    }
}
