<?php

namespace App\Controller;

use App\Entity\Reclamation;
use App\Form\ReclamationType;
use App\Repository\ReclamationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/recUser")
 */

class RecUserController extends AbstractController
{
    /**
     * @Route("/recuser", name="app_rec_user")
     */
    public function index(): Response
    {
        return $this->render('rec_user/index.html.twig', [
            'controller_name' => 'RecUserController',
        ]);
    }

    /**
     * @Route("/new", name="app_rec_new", methods={"GET", "POST"})
     */
    public function new(Request $request, ReclamationRepository $reclamationRepository): Response
    {
        $reclamation = new Reclamation();
        $form = $this->createForm(ReclamationType::class, $reclamation);
        $form->add('Envoyer', SubmitType::class);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {$em=$this->getDoctrine()->getManager();
            $em->persist($reclamation);
            $em->flush();
            $this->addFlash('success','Reclamation Added Successfully !');
            return $this->redirectToRoute('app_rec_new');

        }

        return $this->render('rec_user/index.html.twig', [
            'form'=>$form->createView(),
        ]);
    }



}
