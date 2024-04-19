<?php

namespace App\Controller;

use App\Entity\Response as EntityResponse;
use App\Entity\Votes;
use App\Repository\ResponseRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class VotesController extends AbstractController
{
    #[Route('/votesUp/{id}', name: 'app_upVotes')]
    public function upVote(EntityManagerInterface $entityManager, int $id, Security $security, EntityResponse $response, ResponseRepository $responseRepository): Response
    {
        $response = $responseRepository->find($id);
        $vote = new Votes();
        $user = $security->getUser();

        $responseThread = $response->getThread();
        $idThread = $responseThread->getId();

        $existingVote = $entityManager->getRepository(Votes::class)->findOneBy([
            'user' => $user,
            'response' => $response,
        ]);
        
        if ($existingVote) {
            if (!$existingVote->isVote()) {
                $existingVote->setVote(true);
                $entityManager->flush();
            } else {
                $entityManager->remove($existingVote);
                $entityManager->flush();
            }

            return $this->redirectToRoute('app_threadPost', [
                'id' => $idThread
            ]);
        }

        $vote = new Votes();
        $vote->setResponse($response);
        $vote->setUser($user);  
        $vote->setVote(true);

        $entityManager->persist($vote);
        $entityManager->flush();

        return $this->redirectToRoute('app_threadPost', [
            'id' => $idThread
        ]);
    }

    #[Route('/votesDown/{id}', name: 'app_downVotes')]
    public function downVote(EntityManagerInterface $entityManager, int $id, Security $security, EntityResponse $response, ResponseRepository $responseRepository): Response
    {
        $response = $responseRepository->find($id);
        $vote = new Votes();
        $user = $security->getUser();

        $responseThread = $response->getThread();
        $idThread = $responseThread->getId();

        $existingVote = $entityManager->getRepository(Votes::class)->findOneBy([
            'user' => $user,
            'response' => $response,
        ]);
        
        if ($existingVote) {
            if ($existingVote->isVote()) {
                $existingVote->setVote(false);
                $entityManager->flush();
            } else {
                $entityManager->remove($existingVote);
                $entityManager->flush();
            }

            return $this->redirectToRoute('app_threadPost', [
                'id' => $idThread
            ]);
        }

        $vote = new Votes();
        $vote->setResponse($response);
        $vote->setUser($user);
        $vote->setVote(false);

        $entityManager->persist($vote);
        $entityManager->flush();

        return $this->redirectToRoute('app_threadPost', [
            'id' => $idThread
        ]);
    }
}
