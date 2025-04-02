<?php

namespace App\Tests\Repository;

use App\Entity\Playlist;
use App\Repository\PlaylistRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class PlaylistRepositoryTest extends KernelTestCase
{
    // Récupération du Repository Playlist
    public function recupRepository(): PlaylistRepository {
        self::bootKernel();
        return self::getContainer()->get(PlaylistRepository::class);
    }

    // Test du nombre de Playlist
    public function testNbPlaylists() {
        $repository = $this->recupRepository();
        $nbPlaylists = $repository->count([]);
        $this->assertEquals(28, $nbPlaylists);
    }

    // Définition du nom et titre de la playlist afin de tester l'ajout
    public function newPlaylist(): Playlist {
        return (new Playlist())
            ->setName("Titre de la playlist")
            ->setDescription("Description de la playlist");
    }

    // Test ajout playlist
    public function testAddPlaylist() {
        $repository = $this->recupRepository();
        $playlist = $this->newPlaylist();
        $nbPlaylist = $repository->count([]);
        $repository->add($playlist);
        $this->assertEquals($nbPlaylist + 1, $repository->count([]), "Erreur lors de l'ajout de la playlist");
    }

    // Test suppression Playlist
    public function testRemovePlaylist() {
        $repository = $this->recupRepository();
        $playlist = $this->newPlaylist();
        $repository->add($playlist);
        $nbPlaylist = $repository->count([]);
        $repository->remove($playlist);
        $this->assertEquals($nbPlaylist - 1, $repository->count([]), "Erreur lors de la suppression de la playlist");
    }

    
}
