<?php
// src/Controller/NoteController.php
namespace App\Controller;

use App\Entity\Note;
use App\Form\NoteType;
use App\Repository\NoteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/note')]
class NoteController extends AbstractController
{

    #[Route('/', name: 'note_index')]
    public function index(Request $request, EntityManagerInterface $em, NoteRepository $repo): Response
    {
        if (!$request->getSession()->get('user')) {
        return $this->redirectToRoute('app_login');
        }
        $note = new Note();
        $form = $this->createForm(NoteType::class, $note);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($note);
            $em->flush();
            return $this->redirectToRoute('note_index');
        }

        $formEdits = [];
        foreach ($repo->findAll() as $n) {
            $formEdits[$n->getId()] = $this->createForm(NoteType::class, $n)->createView();
        }

        return $this->render('note/index.html.twig', [
            'form' => $form->createView(),
            'formEdits' => $formEdits,
            'notes' => $repo->findAll(),
        ]);
    }

    #[Route('/{id}/edit', name: 'note_edit', methods: ['POST'])]
    public function edit(Request $request, Note $note, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(NoteType::class, $note);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
        }

        return $this->redirectToRoute('note_index');
    }

    #[Route('/{id}/delete', name: 'note_delete', methods: ['POST'])]
    public function delete(Note $note, EntityManagerInterface $em): Response
    {
        $em->remove($note);
        $em->flush();

        return $this->redirectToRoute('note_index');
    }
}
