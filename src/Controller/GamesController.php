<?php

namespace App\Controller;

use App\Entity\Game;
use App\Entity\OAuth2ClientProfile;
use App\Entity\User;
use App\Form\GameFormType;
use App\Repository\GameRepository;
use App\Service\ImageUploader;
use Doctrine\ORM\EntityManagerInterface;
use League\Bundle\OAuth2ServerBundle\Entity\Client;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

class GamesController extends AbstractController
{

    public function __construct(private GameRepository $gameRepository, private Security $security)
    {
    }

    #[Route('/games', name: 'app_games')]
    public function index(Request $request): Response
    {
        $games = $this->gameRepository->findBy(['isPublic' => true], ['name' => 'ASC']);
        
        if ($this->security->isGranted('ROLE_USER')) {
            $games = $this->gameRepository->findAllVisible($this->getUser());
        }

        return $this->render('games/index.html.twig', [
            'games' => $games,
        ]);
    }

    #[Route('/games/friends', name: 'app_games_friends')]
    public function friendGames(Request $request, #[CurrentUser] User $user): Response
    {
        $games = $this->gameRepository->findAllFriendGames($user);

        return $this->render('games/index.html.twig', [
            'games' => $games,
        ]);
    }

    #[Route('/games/new', name: 'app_games_new')]
    public function newGame(Request $request, EntityManagerInterface $entityManager, #[CurrentUser] User $user, ImageUploader $imageUploader): Response
    {
        $oAuth2Client = new Client('temp', uniqid(md5), uniqid(md5));
        $oAuth2ClientProfile = new OAuth2ClientProfile($oAuth2Client);
        $game = new Game($user, $oAuth2ClientProfile);

        $form = $this->createForm(GameFormType::class, $game);
        $form->handleRequest($request);

        $oAuth2ClientId = $oAuth2Client->getIdentifier();
        //$oAuth2ClientSecret = $oAuth2Client->; // in case of a client secret

        if ($form->isSubmitted() && $form->isValid()) {
            $oAuth2Client->setName($game->getSlug());
            $oAuth2Client->setRedirectUri($game->getUrl());

            $imageLocation = $imageUploader->uploadImage($form['imageFile']->getData(), 'game');
            $game->setPicture($imageLocation);
            
            $entityManager->persist($oAuth2Client);
            $entityManager->persist($oAuth2ClientProfile);
            $entityManager->persist($game);
            $entityManager->flush();

            return $this->redirectToRoute('app_games_game', ['gameSlug' => $game->getSlug()]);
        }

        return $this->render('games/game.html.twig', [
            'title' => 'Create a new game',
            'gameForm' => $form->createView(),
            'OAuth2ClientId' => $oAuth2ClientId,
            // 'OAuth2ClientSecret' => $oAuth2ClientSecret, // in case of a client secret
        ]);
    }

    #[Route('/games/{gameSlug}', name: 'app_games_game')]
    public function game(Request $request, EntityManagerInterface $entityManager, string $gameSlug, #[CurrentUser] User $user): ?Response
    {
        $game = $this->gameRepository->findOneBy(['slug' => $gameSlug]);

        if ($game === null) {
            return new Response("Sorry boo, ya ain't got access to this.", 403);
        }
        // check if user is the creator or admin
        if ($user === $game->getCreator() || $this->security->isGranted('ROLE_ADMIN')) {

            if ($request->isMethod('post') && $request->request->get('play')) {
                return $this->redirectToRoute('oauth2_authorize', array(
                    'client_id' => $game->getOAuth2ClientProfile()->getClient()->getIdentifier(),
                    'redirect_uri' => $game->getUrl(),
                    'response_type' => 'code',
                    'scopes' => $game->getOAuth2ClientProfile()->getClient()->getScopes(),
                ));
            }


            $form = $this->createForm(GameFormType::class, $game);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $entityManager->persist($game);
                $entityManager->flush();

                return $this->redirectToRoute('app_games_game', ['gameSlug' => $game->getSlug()]);
            }

            return $this->render('games/game.html.twig', [
                'title' => 'Edit ' . $game->getName(),
                'gameForm' => $form->createView(),
                'OAuth2ClientId' => $game->getOAuth2ClientProfile()->getClient()->getIdentifier(),
                'playGameButton' => true,
            ]); 
        }

        $playAbleGames = $this->gameRepository->findAllVisible($user);

        if (in_array($game, $playAbleGames))
        {
            return $this->redirectToRoute('oauth2_authorize', array(
                'client_id' => $game->getOAuth2ClientProfile()->getClient()->getIdentifier(),
                //'client_secret' => $game->getOAuth2ClientProfile()->getClient(),
                'redirect_uri' => $game->getUrl(),
                'response_type' => 'code',
                'scopes' => 'email',
            ));
        }

        return new Response("Sorry boo, ya ain't got access to this.", 403);
    }
}
