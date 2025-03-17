<?php
namespace App\Controller;

use App\Entity\Categorie;
use App\Repository\CategorieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/categories', name: 'admin_categories_')]
class AdminCategorieController extends AbstractController
{
    private CategorieRepository $categorieRepository;

    public function __construct(CategorieRepository $categorieRepository)
    {
        $this->categorieRepository = $categorieRepository;
    }

    #[Route('/', name: 'index')]
    public function index(): Response
    {
        $categories = $this->categorieRepository->findAll();
        return $this->render('admin/categories.html.twig', [
            'categories' => $categories
        ]);
    }

    #[Route('/add', name: 'add', methods: ['POST'])]
    public function add(Request $request, EntityManagerInterface $entityManager): Response
    {
        $name = $request->request->get('name');
        if (!$name) {
            $this->addFlash('error', 'Le nom de la catégorie est requis.');
            return $this->redirectToRoute('admin_categories_index');
        }

        // Vérifier si la catégorie existe déjà
        $existingCategory = $this->categorieRepository->findOneBy(['name' => $name]);
        if ($existingCategory) {
            $this->addFlash('error', 'Cette catégorie existe déjà.');
            return $this->redirectToRoute('admin_categories_index');
        }

        $categorie = new Categorie();
        $categorie->setName($name);
        $entityManager->persist($categorie);
        $entityManager->flush();

        $this->addFlash('success', 'Catégorie ajoutée avec succès.');
        return $this->redirectToRoute('admin_categories_index');
    }

    #[Route('/delete/{id}', name: 'delete')]
    public function delete(Categorie $categorie, EntityManagerInterface $entityManager): Response
    {
        if (!$categorie) {
            throw $this->createNotFoundException('Catégorie non trouvée.');
        }

        if (!$categorie->getFormations()->isEmpty()) {
            $this->addFlash('error', 'Impossible de supprimer une catégorie liée à une formation.');
            return $this->redirectToRoute('admin_categories_index');
        }

        $entityManager->remove($categorie);
        $entityManager->flush();

        $this->addFlash('success', 'Catégorie supprimée avec succès.');
        return $this->redirectToRoute('admin_categories_index');
    }
}
