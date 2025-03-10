<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

class AdminPlaylistController extends AbstractController
{
    #[Route('/admin/playlists', name: 'admin_playlists')]
    public function index(): Response
    {
        return $this->render('admin/playlists.html.twig', [
            'playlists' => [] // La liste des playlists sera ajoutée plus tard
        ]);
    }
}
