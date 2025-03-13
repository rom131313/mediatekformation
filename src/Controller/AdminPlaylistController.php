<?php

namespace App\Controller;

use App\Entity\Playlist;
use App\Form\PlaylistType;
use App\Repository\PlaylistRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/playlists', name: 'admin_playlists_')]
class AdminPlaylistController extends AbstractController
{
    private PlaylistRepository $playlistRepository;

    public function __construct(PlaylistRepository $playlistRepository)
    {
        $this->playlistRepository = $playlistRepository;
    }

    #[Route('/', name: 'index')]
    public function index(): Response
    {
        $playlists = $this->playlistRepository->findAll();
        return $this->render('admin/playlists.html.twig', [
            'playlists' => $playlists
        ]);
    }

    #[Route('/tri/{champ}/{ordre}', name: 'sort')]
    public function sort(string $champ, string $ordre, PlaylistRepository $playlistRepository): Response
    {
        $playlists = $playlistRepository->findAllOrderBy($champ, $ordre);
        return $this->render('admin/playlists.html.twig', [
            'playlists' => $playlists
        ]);
    }


    #[Route('/ajout', name: 'add')]
    public function add(Request $request, EntityManagerInterface $entityManager): Response
    {
        $playlist = new Playlist();
        $form = $this->createForm(PlaylistType::class, $playlist);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($playlist);
            $entityManager->flush();

            return $this->redirectToRoute('admin_playlists_index');
        }

        return $this->render('admin/playlists_form.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/edit/{id}', name: 'edit')]
    public function edit(Playlist $playlist, Request $request, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(PlaylistType::class, $playlist);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            return $this->redirectToRoute('admin_playlists_index');
        }

        return $this->render('admin/playlists_form.html.twig', [
            'form' => $form->createView(),
            'playlist' => $playlist
        ]);
    }

    #[Route('/delete/{id}', name: 'delete')]
    public function delete(Playlist $playlist, EntityManagerInterface $entityManager): Response
    {
        // Vérifie si la playlist contient encore des formations
        if (!$playlist->getFormations()->isEmpty()) {
            $this->addFlash('error', 'Impossible de supprimer une playlist contenant des formations.');
            return $this->redirectToRoute('admin_playlists_index');
        }

        $entityManager->remove($playlist);
        $entityManager->flush();

        $this->addFlash('success', 'Playlist supprimée avec succès.');
        return $this->redirectToRoute('admin_playlists_index');
    }



}
