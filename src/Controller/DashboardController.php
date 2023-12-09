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

    // exporter data
    #[Route('/export', name: 'export_data_csv')]
    public function exportData(): Response
    {

        $data = $this->entityManager
        ->getRepository(Reclamation::class) 
        ->createQueryBuilder('r')
        ->select('r', 'u') 
        ->leftJoin('r.utilisateur', 'u') 
        ->getQuery()
        ->getResult();

        $csvData = $this->prepareCsvData($data);

        $csvContent = $this->arrayToCsv($csvData);

        $response = new Response($csvContent);
        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', 'attachment; filename="reclamation_data.csv"');

        return $response;
      
    }

    private function prepareCsvData(array $data): array
    {
        $columnNames = ['Reclamation', 'date Raclamation', 'Pseudo Nom', 'Nom', 'Prenom', 'Addresse',  'Profession', 'Sexe', 'Date Naissanse'];
        $csvData = [];
        $csvData[] = $columnNames;

        foreach ($data as $entity) {

            $dateRec = $entity->getDateReclamation();
            $dateNaiss = $entity->getUtilisateur()->getDateNaissance();

            $csvData[] = [
                $entity->getCorpReclamation(),
                $dateRec->format('Y-m-d'),
                $entity->getUtilisateur()->getPseudoNom(),
                $entity->getUtilisateur()->getNom(),
                $entity->getUtilisateur()->getPrenom(),
                $entity->getUtilisateur()->getAdresse(),
                $entity->getUtilisateur()->getProfession(),
                $entity->getUtilisateur()->getSexe(),
                $dateNaiss->format('Y-m-d'),
                
            ];
        }

        return $csvData;
    }

    private function arrayToCsv(array $data): string
    {
        $output = fopen('php://temp', 'w+');
        foreach ($data as $row) {
            fputcsv($output, $row);
        }
        rewind($output);
        $csv = stream_get_contents($output);
        fclose($output);

        return $csv;
    }

}
