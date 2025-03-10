<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

class AdminFormationController extends AbstractController
{
    #[Route('/admin/formations', name: 'admin_formations')]
    public function index(): Response
    {
        return $this->render('admin/formations.html.twig', [
            'formations' => [] // On affichera la liste plus tard
        ]);
    }
}
