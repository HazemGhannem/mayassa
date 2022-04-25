<?php

namespace App\Controller;

use App\Form\ReponseType;
use App\Entity\Reponse;
use App\Entity\Reclamation;
use App\Repository\ReclamationRepository;
use App\Repository\ResponseRepository;
use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ReponseRepository;

class TestController extends AbstractController
{
     /**
     * @Route("/reponse/new/{id}", name="app_reponse_new")
     */
    public function new(Reclamation $recl,Reclamation $subj,Request $req,
    ReclamationRepository $rep, $id,SessionInterface $session,ReponseRepository $reponseRepository ): Response
    {

       $reclamation = $session->get("reclamation",$subj->getId());
       


        $idReclamation = $rep->find($id);
       
        $em = $this->getDoctrine()->getManager();

        
        $reponses = new Reponse();
        
        $reponses->setCreatedAt(new \DateTimeImmutable());
        $recl->setEtat('Résolue');
        $form = $this->createForm(ReponseType::class, $reponses);
        
        
        $form->handleRequest($req);
        if ($form->isSubmitted() && $form->isValid()) {
            $reponses = $reponses->setReclamation($idReclamation);
            
            $em = $this->getDoctrine()->getManager();
            $em->persist($reponses);
            //dd($reclamation,$reponses,$idReclamation);
           
            
            $em->flush();
 
            return $this->redirectToRoute('app_reponse_index');
        }

        return $this->render('reponse/new.html.twig', [
            'form' => $form->createView(),
            
           

        ]);
    }
    

}
