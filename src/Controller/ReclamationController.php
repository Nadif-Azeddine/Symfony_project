<?php

namespace App\Controller;

use App\Entity\Reclamation;
use App\Entity\Utilisateur;
use App\Form\ReclamationFormType;
use App\Repository\ReclamationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ReclamationController extends AbstractController
{
    private $entityManager;
    private $reclamationRepository;
    public function __construct(EntityManagerInterface $entityManager, ReclamationRepository $reclamationRepository)
    {
        $this->entityManager = $entityManager;
        $this->reclamationRepository = $reclamationRepository;
    }



    #[Route('/reclamation', name: 'app_reclamation')]
    public function index(Request $request): Response
    {
        $reclamation = new Reclamation();
        $form = $this->createForm(ReclamationFormType::class, $reclamation);

        try {
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {

                $user = $this->getUser();

                $reclamation->setDateReclamation(new \DateTime());

                $utilisateur = $this->entityManager->getRepository(Utilisateur::class)->find($user->getUserIdentifier());
                $reclamation->setUtilisateur($utilisateur);

                $this->entityManager->persist($reclamation);
                $this->entityManager->flush();

                return $this->redirectToRoute('app_reclamation');
            }
        } catch (\Exception $e) {
            dd($e->getMessage());
        }


        return $this->render('reclamation/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/reclamation/{id}', name: 'visit_reclamation')]
    public function view($id): Response
    {
        $reclamation = $this->reclamationRepository->find($id);

        if ($reclamation && ($reclamation->isBelongsTo($this->getUser()) || $this->getUser()->isAdmin())) {
            $utilisateur = $reclamation->getUtilisateur();
            return $this->render('reclamation/view.html.twig', [
                "reclamation" => $reclamation,
                "utilisateur" => $utilisateur,

            ]);
        } else {
            return $this->redirectToRoute('404');
        }


    }

    #[Route('/reclamation/{id}/edit', name: 'edit_reclamation')]
    public function edit(Request $request, $id): Response
    {
        $reclamation = $this->reclamationRepository->find($id);


        if ($reclamation && ($reclamation->isBelongsTo($this->getUser()) || $this->getUser()->isAdmin())) {
            $utilisateur = $reclamation->getUtilisateur();

            $form = $this->createForm(ReclamationFormType::class, $reclamation);

            try {
                $form->handleRequest($request);

                if ($form->isSubmitted() && $form->isValid()) {

                    $reclamation->setCorpReclamation($form->get('CorpReclamation')->getData());
                    $this->entityManager->flush();

                    return $this->redirectToRoute('edit_reclamation', ['id' => $id]);
                }
            } catch (\Exception $e) {
                return $this->redirectToRoute('edit_reclamation' , ['id' => $id]);
            }

            return $this->render('reclamation/edit.html.twig', [
                'form' => $form->createView(),
                'utilisateur' => $utilisateur,
                'reclamation' => $reclamation,
            ]);
        } else {
            return $this->redirectToRoute('404');
        }

    }

    #[Route("/reclamation/{id}/delete", name: "delete_reclamation", methods: ['POST'])]
    public function delete($id): Response
    {
        $reclamation = $this->reclamationRepository->find($id);
        if ($reclamation && ($reclamation->isBelongsTo($this->getUser()) || $this->getUser()->isAdmin())) {
            $this->entityManager->remove($reclamation);
            $this->entityManager->flush();

            if ($this->getUser()->isAdmin()) {
                return $this->redirectToRoute('app_dashboard');
            } else {
                return $this->redirectToRoute('app_reclamation');
            }
        }
        return $this->redirectToRoute('app_reclamation');

    }
}
