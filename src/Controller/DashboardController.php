<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\EtudiantRepository;
use App\Repository\MatiereRepository;
use App\Repository\NoteRepository;

final class DashboardController extends AbstractController
{
    #[Route('/dashboard', name: 'app_dashboard')]
    public function index(Request $request, NoteRepository $noteRepo, EtudiantRepository $etudiantRepo): Response
{
    if (!$request->getSession()->get('user')) {
        return $this->redirectToRoute('app_login');
    }
        $etudiants = $etudiantRepo->findAll();
        $moyennesEtudiants = [];

        foreach ($etudiants as $etudiant) {
            $totalNotes = 0;
            $totalCoef = 0;

            foreach ($etudiant->getNotes() as $note) {
                $coef = $note->getMatiere()->getCoefficient();
                $totalNotes += $note->getNote() * $coef;
                $totalCoef += $coef;
            }

            $moyenne = ($totalCoef > 0) ? $totalNotes / $totalCoef : 0;

            $moyennesEtudiants[] = [
                'nom' => $etudiant->getNom(),
                'moyenne' => round($moyenne, 2)
            ];
        }

        return $this->render('dashboard/index.html.twig', [
            'moyennesEtudiants' => $moyennesEtudiants
        ]);
}
}
