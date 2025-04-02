<?php
namespace App\Controller;

use App\Entity\Categorie;
use App\Repository\CategorieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Contrôleur d'administration des catégories.
 *
 * Permet la gestion (CRUD) des catégories depuis le back-office.
 */
#[Route('/admin/categories', name: 'admin_categories_')]
class AdminCategorieController extends AbstractController
{
    /**
     *  Injection du repository.
     * @var CategorieRepository
     */
    private CategorieRepository $categorieRepository;

    /**
     *  Injection du repository.
     * @param CategorieRepository $categorieRepository
     */
    public function __construct(CategorieRepository $categorieRepository)
    {
        $this->categorieRepository = $categorieRepository;
    }

    /**
     *  Affiche la liste de toutes les catégories.
     * @return Response
     */
    #[Route('/', name: 'index')]
    public function index(): Response
    {
        $categories = $this->categorieRepository->findAll();
        return $this->render('admin/categories.html.twig', [
            'categories' => $categories
        ]);
    }

    /**
     *  Permet d'ajouter une nouvelle catégorie.
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
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

    /**
     *  Supprime une catégorie si elle n'est pas liée à une formation.
     * @param Categorie $categorie
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
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
