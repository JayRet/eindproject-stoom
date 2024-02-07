<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GamesController extends AbstractController
{
    #[Route('/games', name: 'app_games')]
    public function index(): Response
    {
        // TODO: give trough all names, pictures and url's of the games
        return $this->render('games/index.html.twig', [
            'controller_name' => 'GamesController',
        ]);
    }

    #[Route('/games/new', name: 'app_games_new')]
    public function newGame(): Response
    {
        // TODO: create a form for creating games
        return $this->render('games/new.html.twig', [
            'controller_name' => 'GamesController',
        ]);
    }

    #[Route('/game/somegame', name: 'app_games_game')]
    public function game(): Response
    {
        // TODO: same form as creating the game, but filled in with existing values
        return $this->render('games/game.html.twig', [
            'controller_name' => 'GamesController',
        ]);
    }
}
