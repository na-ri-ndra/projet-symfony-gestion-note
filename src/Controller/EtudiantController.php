<?php

namespace App\Controller;

use App\Entity\Etudiant;
use App\Form\EtudiantType;
use App\Repository\EtudiantRepository;
use Doctrine\ORM\EntityManagerInterface; // ✅ NÉCESSAIRE !
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class EtudiantController extends AbstractController
{

    #[Route('/etudiant', name: 'etudiant_index', methods: ['GET', 'POST'])]
    public function index(Request $request, EntityManagerInterface $em, EtudiantRepository $repo): Response
{
    if (!$request->getSession()->get('user')) {
        return $this->redirectToRoute('app_login');
    }
    $etudiant = new Etudiant();
    $form = $this->createForm(EtudiantType::class, $etudiant);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $em->persist($etudiant);
        $em->flush();
        return $this->redirectToRoute('etudiant_index');
    }

    // Créer un formulaire pour chaque étudiant (édition)
    $formEdits = [];
    foreach ($repo->findAll() as $e) {
        $formEdits[$e->getId()] = $this->createForm(EtudiantType::class, $e)->createView();
    }

    return $this->render('etudiant/index.html.twig', [
        'form' => $form->createView(),
        'formEdits' => $formEdits,
        'etudiants' => $repo->findAll(),
    ]);
}

#[Route('/etudiant/{id}/edit', name: 'etudiant_update', methods: ['POST'])]
public function update(Request $request, Etudiant $etudiant, EntityManagerInterface $em): Response
{
    $form = $this->createForm(EtudiantType::class, $etudiant);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $em->flush();
    }

    return $this->redirectToRoute('etudiant_index');
}

#[Route('/etudiant/{id}/delete', name: 'etudiant_delete', methods: ['POST'])]
public function delete(Etudiant $etudiant, EntityManagerInterface $em): Response
{
    $em->remove($etudiant);
    $em->flush();

    return $this->redirectToRoute('etudiant_index');
}

}
