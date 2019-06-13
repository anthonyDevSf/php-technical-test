<?php

namespace App\Controller;

use App\Entity\Running;
use App\Form\RunningType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class RunningController extends AbstractController
{
    /**
     * @Route("/running/create", name="app_running_create")
     * @Route("/running/update/{running}", name="app_running_update")
     *
     * @param Request      $request
     * @param Running|null $running
     *
     * @return Response
     */
    public function createOrUpdate(Request $request, Running $running = null) : Response
    {
        // to combine create and update
        null !== $running ?: $running = new Running();

        $form = $this->createForm(RunningType::class, $running);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            // add user to running
            $running->setUser($this->getUser());
            $em->persist($running);
            $em->flush();

            return $this->redirectToRoute('app_home');
        }

        return $this->render('running/create.html.twig', [
            'running' => $running,
            'form'    => $form->createView()
        ]);
    }
}