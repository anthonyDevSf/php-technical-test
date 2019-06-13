<?php

namespace App\Controller;

use App\Entity\Running;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    /**
     * @Route("/", name="app_home")
     * @param EntityManagerInterface $entityManager
     *
     * @return Response
     */
    public function index(EntityManagerInterface $entityManager) : Response
    {
        $runningRepository = $entityManager->getRepository(Running::class);

        return $this->render('default/index.html.twig', [
            'runnings' => $runningRepository->findAll()
        ]);
    }
}