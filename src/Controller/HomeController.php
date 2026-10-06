<?php

namespace App\Controller;

use App\Repository\NoteRepository;
use App\Form\NoteType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

final class HomeController extends AbstractController
{
    #[Route('/home', name: 'app_home')]
    public function index(Request $request, NoteRepository $noteRepo): Response
    {
        if (!$request->getSession()->get('user')) {
            return $this->redirectToRoute('app_login');
        }
        $min = $request->query->get('min');
        $max = $request->query->get('max');
        $nom = $request->query->get('nom');
        $matiere = $request->query->get('matiere');

        $qb = $noteRepo->createQueryBuilder('n')
            ->join('n.etudiant', 'e')
            ->join('n.matiere', 'm');

        if ($min !== null && $min !== '') {
            $qb->andWhere('n.note >= :min')
                ->setParameter('min', $min);
        }

        if ($max !== null && $max !== '') {
            $qb->andWhere('n.note <= :max')
                ->setParameter('max', $max);
        }

        if ($nom !== null && $nom !== '') {
            $qb->andWhere('e.nom LIKE :nom')
                ->setParameter('nom', "%$nom%");
        }

        if ($matiere !== null && $matiere !== '') {
            $qb->andWhere('m.nom LIKE :matiere')
                ->setParameter('matiere', "%$matiere%");
        }

        $notes = $qb->getQuery()->getResult();

        $formEdits = [];
        foreach ($notes as $note) {
            $formEdits[$note->getId()] = $this->createForm(NoteType::class, $note)->createView();
        }

        // ✅ Création de la réponse avec headers pour empêcher le cache navigateur
        $response = $this->render('home/index.html.twig', [
            'notes' => $notes,
            'min' => $min,
            'max' => $max,
            'nom' => $nom,
            'matiere' => $matiere,
            'formEdits' => $formEdits
        ]);

        $response->headers->set('Cache-Control', 'no-store, no-cache, must-revalidate');
        $response->headers->set('Pragma', 'no-cache');

        return $response;
    }

}
