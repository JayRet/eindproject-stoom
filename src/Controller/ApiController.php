<?php

namespace App\Controller;

use App\Entity\Achievement;
use App\Entity\Score;
use App\Entity\User;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

class ApiController extends AbstractController
{
    #[Route('/api/user', name: 'app_api_user')]
    public function apiUser(#[CurrentUser] User $user): Response
    {
        return $this->json([
            'username' => $user->getName(),
            'image_url' => $user,
        ]);
    }

    #[Route('/api/game/scores', name: 'app_api_game_data')]
    public function apiGameScore(Request $request, #[CurrentUser] User $user, EntityManager $entityManager): Response
    {
        if ($request->isMethod('post')) {
            $score = new Score();
            $score->setUser($user);
            $score->setScore($request->query->get('score'));
            $score->setTime($request->query->get('time'));

            $entityManager->persist($score);
            $entityManager->flush();

            $user->addScore($score);

            return $this->json([
                'message' => 'scores updated successfully',
            ]);
        }
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
    public function apiGameAchievement(Request $request, #[CurrentUser] User $user, EntityManager $entityManager): Response
    {
        if ($request->isMethod('post')) {
            $achievement = new Achievement();
            $achievement->setUser($user);
            $achievement->setTitle($request->query->get('title'));
            $achievement->setDescription($request->query->get('description'));
            $achievement->setPathOrUrl($request->query->get('image_url'));

            $entityManager->persist($achievement);
            $entityManager->flush();

            $user->addAchievement($achievement);

            return $this->json([
                'message' => 'Achievement successfully created',
            ]);
        }

        if ($request->isMethod('get')) {
            $achievements = $user->getAchievements()->get('title');

            return $this->json([
                'achievements' => $achievements,
            ]);
        }
    }
}
