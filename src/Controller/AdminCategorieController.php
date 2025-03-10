<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

class AdminCategorieController extends AbstractController
{
    #[Route('/admin/categories', name: 'admin_categories')]
    public function index(): Response
    {
        return $this->render('admin/categories.html.twig', [
            'categories' => [] // La liste des catégories sera ajoutée plus tard
        ]);
    }
}
