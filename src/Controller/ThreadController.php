<?php

namespace App\Controller;

use App\Entity\Thread;
use App\Form\ThreadEditType;
use App\Form\ThreadType;
use App\Repository\ThreadRepository;
use DateTime;
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
        return $this->redirectToRoute('app_homepage', [
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

    #[Route('/thread/{id}', name: 'app_threadPost')]
    public function threadPost(int $id, ThreadRepository $threadRepository): Response
    {
        $thread = $threadRepository->find($id);
        return $this->render('thread/threadpost.html.twig', [
            'id' => $id,
            'thread' => $thread
        ]);
    }

    #[Route('/thread/{id}/edit', name: 'app_threadEdit', methods: ['POST', 'GET'])]
    public function threadEdit(int $id, Thread $thread, Request $request, EntityManagerInterface $entityManager): Response
    {
        $formThreadEdit = $this->createForm(ThreadEditType::class, $thread);
        $formThreadEdit->handleRequest($request);

        if ($formThreadEdit->isSubmitted() && $formThreadEdit->isValid()) {
            $thread->setDateUpdate(new DateTime());

            $entityManager->persist($thread);
            $entityManager->flush();

            $this->addFlash('success', 'Les modifications ont bien été effectués.');

            return $this->redirectToRoute('app_threadPost', [
                'id' => $id
            ]);
        }

        return $this->render('thread/threadedit.html.twig', [
            'id' => $id,
            'thread' => $thread,
            'formThreadEdit' => $formThreadEdit
        ]);
    }

}
