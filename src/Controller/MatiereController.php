<?php

namespace App\Controller;

use App\Entity\Matiere;
use App\Form\MatiereType;
use App\Repository\MatiereRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MatiereController extends AbstractController
{
    #[Route('/matiere', name: 'matiere_index')]
    public function index(Request $request, EntityManagerInterface $em, MatiereRepository $repo): Response
    {
        if (!$request->getSession()->get('user')) {
        return $this->redirectToRoute('app_login');
    }
        $matiere = new Matiere();
        $form = $this->createForm(MatiereType::class, $matiere);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($matiere);
            $em->flush();
            return $this->redirectToRoute('matiere_index');
        }

        $formEdits = [];
        foreach ($repo->findAll() as $m) {
            $formEdits[$m->getId()] = $this->createForm(MatiereType::class, $m)->createView();
        }

        return $this->render('matiere/index.html.twig', [
            'form' => $form->createView(),
            'formEdits' => $formEdits,
            'matieres' => $repo->findAll(),
        ]);
    }

    #[Route('/matiere/{id}/edit', name: 'matiere_edit', methods: ['POST'])]
    public function edit(Request $request, Matiere $matiere, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(MatiereType::class, $matiere);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
        }

        return $this->redirectToRoute('matiere_index');
    }

    #[Route('/matiere/{id}/delete', name: 'matiere_delete', methods: ['POST'])]
    public function delete(Matiere $matiere, EntityManagerInterface $em): Response
    {
        $em->remove($matiere);
        $em->flush();

        return $this->redirectToRoute('matiere_index');
    }
}
