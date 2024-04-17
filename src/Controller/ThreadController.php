<?php

namespace App\Controller;

use App\Entity\Thread;
use App\Form\ThreadType;
use App\Repository\ThreadRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ThreadController extends AbstractController
{
    #[Route('/', name: 'app_homepage')]
    public function homePage(ThreadRepository $threadRepository): Response
    {
        $threads = $threadRepository->findAll();
        return $this->render('homepage.html.twig', [
            'threads' => $threads,
        ]);
    }

    #[Route('/thread', name: 'app_thread')]
    public function index(): Response
    {
        return $this->render('thread/index.html.twig', [
            'controller_name' => 'ThreadController',
        ]);
    }

    #[Route('/thread/add', name: 'app_threadAdd')]
    public function addThread(Request $request, EntityManagerInterface $entityManager, Security $security): Response
    {
        $thread = new Thread();
        $formThread = $this->createForm(ThreadType::class, $thread);
        $formThread->handleRequest($request);

        if ($formThread->isSubmitted() && $formThread->isValid()) {
            $thread->setCreatedAt(new \DateTimeImmutable());
            $thread->setUser($security->getUser());
            $thread->setStatus("ouvert");
            $entityManager->persist($thread);
            $entityManager->flush();

            $this->addFlash('success', 'Le thread a bien été posté.');

            return $this->redirectToRoute('app_homepage');
        }

        return $this->render('thread/addthread.html.twig', [
            'formThread' => $formThread,
        ]);
    }
}
