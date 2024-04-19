<?php

namespace App\Controller;

use App\Entity\Response as EntityResponse;
use App\Form\ResponseType;
use App\Repository\ResponseRepository;
use App\Repository\ThreadRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ResponseController extends AbstractController
{
    #[Route('/response/{id}', name: 'app_response')]
    public function addResponse(int $id, Request $request, EntityManagerInterface $entityManager, Security $security, ThreadRepository $threadRepository): Response
    {
        $response = new EntityResponse();
        $formResponse = $this->createForm(ResponseType::class, $response);
        $formResponse->handleRequest($request);

        if ($formResponse->isSubmitted() && $formResponse->isValid()) {
            $response->setCreatedAt(new \DateTimeImmutable());
            $response->setUser($security->getUser());
            $threadResponded = $threadRepository->find($id);
            $response->setThread($threadResponded);
            $entityManager->persist(($response));
            $entityManager->flush();

            $this->addFlash('success', 'Le thread a bien été posté.');

            return $this->redirectToRoute('app_homepage');
        }

        return $this->render('response/index.html.twig', [
            'formResponse' => $formResponse,
            'id' => $id
        ]);
    }

    #[Route('/response/{id}/edit', name: 'app_responseEdit', methods: ['POST', 'GET'])]
    public function responseEdit(int $id, Request $request, EntityManagerInterface $entityManager, EntityResponse $response): Response
    {
        $formResponse = $this->createForm(ResponseType::class, $response);
        $formResponse->handleRequest($request);

        $idThread = $response->getThread();
        $idThread = $idThread->getId();

        if ($formResponse->isSubmitted() && $formResponse->isValid()) {
            $response->setDateUpdate(new DateTime());

            $entityManager->persist($response);
            $entityManager->flush();

            $this->addFlash('success', 'Les modifications ont bien été effectués.');

            return $this->redirectToRoute('app_threadPost', [
                'id' => $idThread
            ]);
        }

        return $this->render('response/index.html.twig', [
            'id' => $id,
            'response' => $response,
            'formResponse' => $formResponse
        ]);
    }
}
