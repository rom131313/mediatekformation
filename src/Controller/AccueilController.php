<?php
namespace App\Controller;

use App\Repository\FormationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Description of AccueilController
 *
 * @author emds
 */
class AccueilController extends AbstractController{

    /**
     * Repository des formations pour accéder aux données.
     * @var FormationRepository
     */
    private $repository;

    /**
     * Injection du repository dans le contrôleur.
     *
     * @param FormationRepository $repository
     */
    public function __construct(FormationRepository $repository) {
        $this->repository = $repository;
    }

    /**
     * Affiche la page d'accueil avec les deux dernières formations publiées.
     *
     * @return Response
     */
    #[Route('/', name: 'accueil')]
    public function index(): Response{
        $formations = $this->repository->findAllLasted(2);
        return $this->render("pages/accueil.html.twig", [
            'formations' => $formations
        ]);
    }

    /**
     * Affiche la page des Conditions Générales d'Utilisation (CGU).
     *
     * @return Response
     */
    #[Route('/cgu', name: 'cgu')]
    public function cgu(): Response{
        return $this->render("pages/cgu.html.twig");
    }
}
