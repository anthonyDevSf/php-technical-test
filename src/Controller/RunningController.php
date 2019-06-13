<?php

namespace App\Controller;

use App\Entity\Running;
use App\Form\RunningType;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/running")
 */
class RunningController extends AbstractController
{
    /**
     * @Route("/create", name="app_running_create")
     * @Route("/update/{running}", name="app_running_update")
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

    /**
     * @Route("/delete/{running}", name="app_running_delete")
     * @param Running $running
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function delete(Running $running) : RedirectResponse
    {
        $em = $this->getDoctrine()->getManager();

        $em->remove($running);
        $em->flush();

        return $this->redirectToRoute('app_home');
    }
}