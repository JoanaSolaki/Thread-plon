<?php

namespace App\Controller;

use App\Repository\ThreadRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ThreadController extends AbstractController
{
    #[Route('/thread', name: 'app_thread')]
    public function index(ThreadRepository $ThreadRepository): Response
    {
        $threads = $ThreadRepository->findAll();

        return $this->render('thread/index.html.twig', [
            'threads' => $threads,
        ]);
    }

    #[Route('/thread', name: 'app_thread_show')]
    public function indexThread(): Response
    {
        return $this->render('thread/thread.html.twig', [

        ]);
    }

    #[Route('/', name: 'app_homepage')]
    public function indexHomePage(ThreadRepository $ThreadRepository): Response
    {
        $threads = $ThreadRepository->findAll();
        
        return $this->render('homepage.html.twig', [
            'threads' => $threads
        ]);
    }
}
