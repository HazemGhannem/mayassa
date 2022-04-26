<?php

namespace App\Controller;

use App\Entity\Reclamation;
use App\Entity\User;
use App\Form\ReclamationType;
use App\Repository\ReclamationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;

/**
 * @Route("/reclamation")
 */

class ReclamationController extends AbstractController
{
    /**
     * @Route("/listRec", name="app_reclamation_index", methods={"GET"})
     */
    public function index(Request $request,PaginatorInterface $paginator): Response
    {
        $reclamation =$this->getDoctrine()->getRepository(Reclamation::class)->findAll();
        $reclamation = $paginator->paginate(
            $reclamation, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            5/*limit per page*/
        );
        return $this->render('reclamation/index.html.twig', [
            'reclamations' => $reclamation
            
            
        ]);
    }

    /**
     * @Route("/new", name="app_reclamation_new", methods={"GET", "POST"})
     */
    public function new(Request $request, ReclamationRepository $reclamationRepository): Response
    {
        $reclamation = new Reclamation();
        $user = $this->getDoctrine()->getRepository(User::class)->find(1);
        $form = $this->createForm(ReclamationType::class, $reclamation);
        $form->handleRequest($request);
        $reclamation->setEtat("non traite");
        $reclamation->setUser($user);
        $reclamation->setDateRec(new \DateTimeImmutable());

        if ($form->isSubmitted() && $form->isValid()) {
            $reclamationRepository->add($reclamation);
            return $this->redirectToRoute('reclamation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('rec_front/new.html.twig', [
            'reclamation' => $reclamation,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="app_reclamation_show", methods={"GET"})
     */
    public function show(Reclamation $reclamation): Response
    {
        return $this->render('reclamation/show.html.twig', [
            'reclamation' => $reclamation,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="reclamation_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Reclamation $reclamation, ReclamationRepository $reclamationRepository): Response
    {
        $form = $this->createForm(ReclamationType::class, $reclamation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $reclamationRepository->add($reclamation);
            return $this->redirectToRoute('reclamation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('rec_front/edit.html.twig', [
            'reclamation' => $reclamation,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/del/{id}", name="app_reclamation_delete")
     */
    public function delete($id): Response
    {
        $reclamation = $this->getDoctrine()->getRepository(Reclamation::class)->find($id);
        $em = $this->getDoctrine()->getManager();
        $em->remove($reclamation);
        $em->flush();

        return $this->redirectToRoute('app_reclamation_index', [], Response::HTTP_SEE_OTHER);
    }
    /**
     * @Route("/", name="reclamation_index", methods={"GET"})
     */
    public function indexx(Request $request,PaginatorInterface $paginator): Response
    {
        $reclamation =$this->getDoctrine()->getRepository(Reclamation::class)->findAll();
        $reclamation = $paginator->paginate(
            $reclamation, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            5/*limit per page*/
        );
        return $this->render('rec_front/index.html.twig', [
            'reclamations' => $reclamation
            
            
        ]);
    }
    /**
     * @Route("/show/{id}", name="reclamation_show", methods={"GET"})
     */
    public function showw(Reclamation $reclamation): Response
    {
        return $this->render('rec_front/show.html.twig', [
            'reclamation' => $reclamation,
        ]);
    }
     /**
     * @Route("/dele/{id}", name="reclamation_delete", requirements={"id":"\d+"})
     */
    public function deletefront($id): Response
    {
        $reclamation = $this->getDoctrine()->getRepository(Reclamation::class)->find($id);
        $em = $this->getDoctrine()->getManager();
        $em->remove($reclamation);
        $em->flush();

        return $this->redirectToRoute('reclamation_index', [], Response::HTTP_SEE_OTHER);
    }
 
}
