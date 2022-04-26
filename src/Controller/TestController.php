<?php

namespace App\Controller;

use App\Form\ReponseType;
use App\Entity\Reponse;
use App\Entity\User;
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

use Mediumart\Orange\SMS\SMS;
use Mediumart\Orange\SMS\Http\SMSClient;

class TestController extends AbstractController
{
     /**
     * @Route("/reponse/new/{id}", name="app_reponse_new")
     */
    public function new(Reclamation $recl,Reclamation $subj,Request $req,
    ReclamationRepository $rep, $id,SessionInterface $session,ReponseRepository $reponseRepository
    , \Swift_Mailer $mailer ): Response
    {

        $user = $this->getDoctrine()->getRepository(User::class)->find(1);
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
            $em->flush();
            //dd($reclamation,$reponses,$idReclamation);
           
            
            
             $client = SMSClient::getInstance('nEoxkRRL52MtHzUNAoaXc0ngnNVl9KDC', 'zSB1YIu2CSwoLnBL');
             $sms = new SMS($client);
             $sms->message( "Reclamation traiter avec success" )
                 ->from('+21654302753')
                 ->to("+216". strval($user->getTelephone()))
                  ->send();


                $message = (new \Swift_Message('Response'))
                ->setFrom('hamatalbi9921@gmail.com')
                ->setTo("mayssa.klay@esprit.tn")
               ->setBody("Reclamation traiter avec success"
                       
        );
        $mailer->send($message);
    
            return $this->redirectToRoute('app_reponse_index');
        }

        return $this->render('reponse/new.html.twig', [
            'form' => $form->createView(),
            
           

        ]);
    }
  
    
    

}
