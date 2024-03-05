<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\ProfileFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

class ProfileController extends AbstractController
{
    #[Route('/profile/edit', name: 'app_profile')]
    public function userEditor(Request $request, EntityManagerInterface $entityManager, #[CurrentUser] User $user): Response
    {
        $form = $this->createForm(ProfileFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($user);
            $entityManager->flush();
        }

        return $this->render('profile/index.html.twig', [
            'profileForm' => $form->createView(),
        ]);
    }

    #[Route('/profile/achievements', name: 'app_profile_achievements')]
    public function userAchievements(Request $request, EntityManagerInterface $entityManager, #[CurrentUser] User $user): Response
    {
        $achievements = $user->getAchievements();

        return $this->render('profile/achievements.html.twig', [
            'achievements' => $achievements,
        ]);
    }

    #[Route('/profile/stats', name: 'app_profile_stats')]
    public function userStats(Request $request, EntityManagerInterface $entityManager, #[CurrentUser] User $user): Response
    {
        return $this->render('profile/index.html.twig', [
        ]);
    }
}
