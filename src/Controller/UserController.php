<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserEditType;
use App\Repository\UserRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

class UserController extends AbstractController
{
    #[Route('/user/{id}', name: 'app_user')]
    public function index(int $id, UserRepository $userRepository): Response
    {
        $user = $userRepository->find($id);
        return $this->render('user/index.html.twig', [
            'user' => $user,
            'id' => $id
        ]);
    }

    #[Route('/user/{id}/edit', name: 'app_editUser', methods: ['POST', 'GET'])]
    public function editUser(int $id, User $user, Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {
        $formUserEdit = $this->createForm(UserEditType::class, $user);
        $formUserEdit->handleRequest($request);

        if ($formUserEdit->isSubmitted() && $formUserEdit->isValid()) {
            $user->setDateUpdate(new DateTime());
            // encode the plain password
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $formUserEdit->get('plainPassword')->getData()
                )
            );

            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('success', 'Les modifications ont bien été effectués.');

            return $this->redirectToRoute('app_user', [
                'id' => $id
            ]);
        }

        return $this->render('user/useredit.html.twig', [
            'id' => $id,
            'user' => $user,
            'formUserEdit' => $formUserEdit
        ]);
    }

    #[Route('/user/{id}/delete', name: 'app_deleteUser', methods: ['DELETE'])]
    public function deleteUser(int $id, EntityManagerInterface $entityManager, User $user): Response
    {
        $this->container->get('security.token_storage')->setToken(null);
        
        $entityManager->remove($user);
        $entityManager->flush();

        $this->addFlash('success', 'Le compte a été supprimé.');

        return $this->redirectToRoute('app_homepage');
    }
}