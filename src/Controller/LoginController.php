<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\DBAL\Connection;

final class LoginController extends AbstractController
{
    
    #[Route('/login', name: 'app_login')]
    public function login(Request $request, Connection $connection): Response
    {
        $error = null;

        if ($request->isMethod('POST')) {
            $login = $request->request->get('login');
            $password = $request->request->get('password');

            $sql = "SELECT * FROM tabila WHERE login = :login AND password = :password";
            $user = $connection->fetchAssociative($sql, [
                'login' => $login,
                'password' => $password,
            ]);

            if ($user) {
                $request->getSession()->set('user', $user['login']);
                return $this->redirectToRoute('app_home');
            } else {
                $error = "Identifiants incorrects.";
            }
        }

        return $this->render('login/index.html.twig', ['error' => $error]);
    }
    #[Route('/logout', name: 'manual_logout')]
    public function logout(Request $request): Response
    {
        // Supprime la session
        $session = $request->getSession();
        $session->remove('user');
        $session->invalidate(); // Facultatif : détruit complètement la session

        // Redirection vers la page de login
        return $this->redirectToRoute('app_login');
    }
    
}
