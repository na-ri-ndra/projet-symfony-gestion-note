<?php
// src/Controller/EtudiantPdfController.php
namespace App\Controller;

use Dompdf\Dompdf;
use Dompdf\Options;
use App\Repository\EtudiantRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class EtudiantPdfController extends AbstractController
{

    #[Route('/etudiants/pdf', name: 'etudiant_pdf')]
    public function generatePdf(EtudiantRepository $etudiantRepository, Request $request): Response
    {
        if (!$request->getSession()->get('user')) {
        return $this->redirectToRoute('app_login');
    }
        $etudiants = $etudiantRepository->findAll();

        // Configure Dompdf
        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');

        $dompdf = new Dompdf($pdfOptions);

        // Génère le HTML à partir d'un template Twig
        $html = $this->renderView('etudiant_pdf/pdf.html.twig', [
            'etudiants' => $etudiants,
        ]);

        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        return new Response($dompdf->stream('liste_etudiants.pdf', [
            "Attachment" => true
        ]));
    }
}
