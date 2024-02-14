<?php

namespace App\Controller;

use App\Entity\Game;
use App\Entity\User;
use App\Form\GameFormType;
use App\Repository\GameRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

class GamesController extends AbstractController
{
    private $gameRepository;

    public function __construct(GameRepository $gameRepository) // QUESTION: ask koen if this is a bad practice
    {
        $this->gameRepository = $gameRepository;
    }

    #[Route('/games', name: 'app_games')]
    public function index(Request $request, EntityManagerInterface $entityManager, #[CurrentUser] User $user): Response
    {
        // $repository = $entityManager->getRepository(Game::class); // DELETE: if __construct method is not a bad practice
        $games = $this->gameRepository->findAllVisable($user); // NOTE: might not work -> check the DELETE comment

        if ($request->request->get('game'))
        {
            $gameSlug = $request->request->get('game');
            $game = $this->gameRepository->findOneBy(['slug' => $gameSlug]);

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
        $game = new Game($user);
        $form = $this->createForm(GameFormType::class, $game);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $OAuth2Client = $game->getOAuth2ClientProfile()->getClient();
            $OAuth2ClientId = $OAuth2Client->getIdentifier();
            // $OAuth2ClientSecret = $OAuth2Client->getSecret(); // in case of a client secret
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
        // $repository = $entityManager->getRepository(Game::class); // DELETE: same as before
        $game = $this->gameRepository->findOneBy(['slug' => $gameSlug]);

        // for editing the game when the user is the creator
        if ($user === $game->getCreator() && (!$request->query->get('user_mode'))) { // QUESTION: does this work
            $form = $this->createForm(GameFormType::class, $game);
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
