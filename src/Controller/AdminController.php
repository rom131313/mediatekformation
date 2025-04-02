<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

/**
 * Contrôleur principal de l'espace d'administration.
 */
class AdminController extends AbstractController
{
    /**
     *  Affiche le tableau de bord de l'administration.
     * @return Response
     */
    #[Route('/admin', name: 'admin_dashboard')]
    public function index(): Response
    {
        return $this->render('admin/dashboard.html.twig');
    }
}
