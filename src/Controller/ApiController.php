<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

class ApiController extends AbstractController
{
    #[Route('/api/user', name: 'app_api_user')]
    public function apiUser(#[CurrentUser] User $user): Response
    {
        return $this->json([
            'message' => 'You successfully authenticated!',
            'email' => $user->getUserIdentifier(),
        ]);
    }

    #[Route('/api/game/data', name: 'app_api_game_data')]
    public function apiGameData(#[CurrentUser] User $user): Response
    {
        return $this->json([
            'message' => 'You successfully authenticated!',
            'email' => $user->getUserIdentifier(),
        ]);
    }

    #[Route('/api/game/achievement', name: 'app_api_game_achievement')]
    public function apiGameAchievement(#[CurrentUser] User $user): Response
    {
        return $this->json([
            'message' => 'You successfully authenticated!',
            'email' => $user->getUserIdentifier(),
        ]);
    }
}
