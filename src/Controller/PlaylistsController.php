<?php
namespace App\Controller;

use App\Repository\CategorieRepository;
use App\Repository\FormationRepository;
use App\Repository\PlaylistRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Description of PlaylistsController
 * Contrôleur de gestion des playlists (côté utilisateur).
 * Permet d'afficher, trier, filtrer et consulter les playlists.
 * @author emds
 */
class PlaylistsController extends AbstractController {
    /**
     * Template de base pour les pages de playlists.
     */
    private const TEMPLATE_PLAYLISTS = "pages/playlists.html.twig";

    /**
     *
     * @var PlaylistRepository
     */
    private $playlistRepository;

    /**
     *
     * @var FormationRepository
     */
    private $formationRepository;

    /**
     *
     * @var CategorieRepository
     */
    private $categorieRepository;

    /**
     *  Injection des repositories nécessaires.
     * @param PlaylistRepository $playlistRepository
     * @param CategorieRepository $categorieRepository
     * @param FormationRepository $formationRespository
     */
    public function __construct(PlaylistRepository  $playlistRepository,
                                CategorieRepository $categorieRepository,
                                FormationRepository $formationRespository) {
        $this->playlistRepository = $playlistRepository;
        $this->categorieRepository = $categorieRepository;
        $this->formationRepository = $formationRespository;
    }

    /**
     *  Affiche toutes les playlists triées par nom (ordre ASC par défaut).
     * @Route("/playlists", name="playlists")
     * @return Response
     */
    #[Route('/playlists', name: 'playlists')]
    public function index(): Response{
        $playlists = $this->playlistRepository->findAllOrderByName('ASC');
        $categories = $this->categorieRepository->findAll();
        return $this->render(self::TEMPLATE_PLAYLISTS, [
            'playlists' => $playlists,
            'categories' => $categories
        ]);
    }

    /**
     *  Trie les playlists par champ (nom ou nombre de formations).
     * @param $champ
     * @param $ordre
     * @return Response
     */
    #[Route('/playlists/tri/{champ}/{ordre}', name: 'playlists.sort')]
    public function sort($champ, $ordre): Response {
        if ($champ === "name") {
            $playlists = $this->playlistRepository->findAllOrderByName($ordre);
        } elseif ($champ === "formations") {
            $playlists = $this->playlistRepository->findAllOrderByFormationCount($ordre);
        } else {
            throw new \InvalidArgumentException("Champ de tri invalide : " . $champ);
        }

        $categories = $this->categorieRepository->findAll();
        return $this->render(self::TEMPLATE_PLAYLISTS, [
            'playlists' => $playlists,
            'categories' => $categories
        ]);
    }

    /**
     *  Recherche les playlists contenant une valeur dans un champ donné.
     * @param $champ
     * @param Request $request
     * @param $table
     * @return Response
     */
    #[Route('/playlists/recherche/{champ}/{table}', name: 'playlists.findallcontain')]
    public function findAllContain($champ, Request $request, $table=""): Response{
        $valeur = $request->get("recherche");
        $playlists = $this->playlistRepository->findByContainValue($champ, $valeur, $table);
        $categories = $this->categorieRepository->findAll();
        return $this->render(self::TEMPLATE_PLAYLISTS, [
            'playlists' => $playlists,
            'categories' => $categories,
            'valeur' => $valeur,
            'table' => $table
        ]);
    }

    /**
     *  Affiche le détail d'une playlist.
     * @param $id
     * @return Response
     */
    #[Route('/playlists/playlist/{id}', name: 'playlists.showone')]
    public function showOne($id): Response{
        $playlist = $this->playlistRepository->find($id);
        $playlistCategories = $this->categorieRepository->findAllForOnePlaylist($id);
        $playlistFormations = $this->formationRepository->findAllForOnePlaylist($id);
        return $this->render("pages/playlist.html.twig", [
            'playlist' => $playlist,
            'playlistcategories' => $playlistCategories,
            'playlistformations' => $playlistFormations
        ]);
    }

}
