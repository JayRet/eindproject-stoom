<?php

namespace App\Controller;

use App\Entity\Game;
use App\Entity\OAuth2ClientProfile;
use App\Entity\User;
use App\Form\GameType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

class GamesController extends AbstractController
{
    #[Route('/games', name: 'app_games')]
    public function index(Request $request, EntityManagerInterface $entityManager, #[CurrentUser] User $user): Response
    {
        $repository = $entityManager->getRepository(Game::class);

        if ($request->request->get('game'))
        {
            $gameSlug = $request->request->get('game');
            $game = $repository->findOneBy(['slug' => $gameSlug]);
            $games = $repository->findAllVisable($user); // QUESTION: make a repository service so this error goes away -> ask koen

            if ($request->query->get('play') && in_array($game, $games))
            {
                return $this->redirectToRoute('oauth2_authorize', array(
                    'client_id' => $game->getOAuth2ClientProfile()->getClient()->getIdentifier(),
                    'redirect_uri' => $game->getUrl(),
                    'response_type' => 'code',
                    'scopes' => $game->getOAuth2ClientProfile()->getScopes(),
                ));
            }

            return $this->redirectToRoute('app_games_game', ['game' => $gameSlug]);
        }


        return $this->render('games/index.html.twig', [
            'games' => $games,
        ]);
    }

    #[Route('/games/new', name: 'app_games_new')]
    public function newGame(Request $request, EntityManagerInterface $entityManager, #[CurrentUser] User $user): Response
    {
        $game = new Game();
        $OAuth2ClientProfile = new OAuth2ClientProfile();

        $game->setOAuth2ClientProfile($OAuth2ClientProfile);
        $game->setCreator($user);
        $game->setSlug(); // QUESTION: ask koen if this can be done inside the entity itself -> inside the constructor

        $OAuth2Client = $game->getOAuth2ClientProfile()->getClient();
        $OAuth2ClientId = $OAuth2Client->getIdentifier();
        // $OAuth2ClientSecret = $OAuth2Client->getSecret(); // in case of a client secret

        $form = $this->createForm(GameType::class, $game);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($game, $OAuth2ClientProfile);
            $entityManager->flush();
        }

        // TODO: create a form for creating games
        return $this->render('games/new.html.twig', [
            'title' => 'Create a new game',
            'gameForm' => $form->createView(),
            'OAuth2ClientId' => $OAuth2ClientId,
            // 'OAuth2ClientSecret' => $OAuth2ClientSecret, // in case of a client secret
        ]);
    }

    #[Route('/games/{game}', name: 'app_games_game')]
    public function game(Request $request, EntityManagerInterface $entityManager, string $gameSlug, #[CurrentUser] User $user): Response
    {
        $repository = $entityManager->getRepository(Game::class);
        $game = $repository->findOneBy(['slug' => $gameSlug]);

        // for editing the game when the user is the creator
        if ($user === $game->getCreator() && (!$request->query->get('user_mode'))) {
            $form = $this->createForm(GameType::class, $game);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $entityManager->persist($game);
                $entityManager->flush();

                return $this->redirectToRoute('app_games_game', ['game' => $gameSlug]);
            }

            // TODO: same form as creating the game, but filled in with existing values
            return $this->render('games/new.html.twig', [
                'title' => 'Edit ' + $game->getName(),
                'gameForm' => $form->createView(),
            ]); 
        }

        return $this->render('games/game.html.twig', [
            'title' => $game->getName(),
            'game' => $game,
        ]);
    }
}
