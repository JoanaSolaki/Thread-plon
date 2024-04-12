<?php

namespace App\Controller;

use App\Repository\ThreadRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
}
