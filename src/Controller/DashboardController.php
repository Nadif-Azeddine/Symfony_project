<?php

namespace App\Controller;

use App\Entity\Reclamation;
use App\Repository\ReclamationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractController
{
    private $reclamationRepository;
    private $entityManager;
    public function __construct(ReclamationRepository $reclamationRepository, EntityManagerInterface $entityManager){
        $this->reclamationRepository=$reclamationRepository;
        $this->entityManager=$entityManager;
    }


    #[Route('/dashboard', name: 'app_dashboard')]
    public function index(): Response
    {
        $reclamations = $this->entityManager
        ->getRepository(Reclamation::class) 
        ->createQueryBuilder('r')
        ->select('r', 'u') 
        ->leftJoin('r.utilisateur', 'u') 
        ->getQuery()
        ->getResult();
        return $this->render('dashboard/index.html.twig', [
            'reclamations' => $reclamations,
        ]);
    }
}
