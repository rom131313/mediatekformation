<?php
namespace App\Controller;

use App\Entity\Formation;
use App\Form\FormationType;
use App\Repository\FormationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Contrôleur d'administration des formations.
 * Permet de gérer l'ajout, la modification, la suppression et le tri des formations.
 */
#[Route('/admin/formations', name: 'admin_formations_')]
class AdminFormationController extends AbstractController
{
    /**
     *  Repository des formations.
     * @var FormationRepository
     */
    private FormationRepository $formationRepository;

    /**
     *  Injection du repository de formations.
     * @param FormationRepository $formationRepository
     */
    public function __construct(FormationRepository $formationRepository)
    {
        $this->formationRepository = $formationRepository;
    }

    /**
     *  Liste toutes les formations.
     * @return Response
     */
    #[Route('/', name: 'index')]
    public function index(): Response
    {
        $formations = $this->formationRepository->findAll();
        return $this->render('admin/formations.html.twig', [
            'formations' => $formations
        ]);
    }

    /**
     *  Trie les formations selon un champ et un ordre.
     * @param $champ
     * @param $ordre
     * @return Response
     */
    #[Route('/tri/{champ}/{ordre}', name: 'sort')]
    public function sort($champ, $ordre): Response
    {
        $formations = $this->formationRepository->findAllOrderBy($champ, $ordre);
        return $this->render('admin/formations.html.twig', [
            'formations' => $formations
        ]);
    }

    /**
     *  Ajoute une nouvelle formation via formulaire.
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    #[Route('/ajout', name: 'add')]
    public function add(Request $request, EntityManagerInterface $entityManager): Response
    {
        $formation = new Formation();
        $form = $this->createForm(FormationType::class, $formation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($formation);
            $entityManager->flush();

            return $this->redirectToRoute('admin_formations_index');
        }

        return $this->render('admin/formations_form.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     *  Modifie une formation existante.
     * @param Formation $formation
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    #[Route('/edit/{id}', name: 'edit')]
    public function edit(Formation $formation, Request $request, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(FormationType::class, $formation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            return $this->redirectToRoute('admin_formations_index');
        }

        return $this->render('admin/formations_form.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     *  Supprime une formation.
     * @param Formation $formation
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    #[Route('/delete/{id}', name: 'delete')]
    public function delete(Formation $formation, EntityManagerInterface $entityManager): Response
    {
        // Vérifie si la formation est bien liée à une playlist avant de la supprimer
        if ($formation->getPlaylist()) {
            $formation->setPlaylist(null);
        }

        $entityManager->remove($formation);
        $entityManager->flush();

        return $this->redirectToRoute('admin_formations_index');
    }





}
