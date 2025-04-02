<?php

namespace App\Tests\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class PlaylistsControllerTest extends WebTestCase
{
    private const PLAYLISTS = '/playlists';
    private const TITRE_H5 ='Bases de la programmation (C#)';
    // Test d'accès à la page
    public function testAccessPagePlaylists() {
        $client = static::createClient();
        $client->request('GET', self::PLAYLISTS);
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }

    // Tri ASC et DESC sur les playlists

    public function testTriPlaylists()
    {
        $client = static::createClient();
        $client->request('GET', self::PLAYLISTS.'/tri/name/ASC');
        $this->assertSelectorTextContains('h5', self::TITRE_H5);
        $client->request('GET', self::PLAYLISTS.'/tri/name/DESC');
        $this->assertSelectorTextContains('h5', "Visual Studio 2019 et C#");
    }

    // Filtre par playlist
    public function testFiltrePlaylists()
    {
        $client = static::createClient();
        $client->request('GET',self::PLAYLISTS);
        $crawler = $client->submitForm('filtrer', [
            'recherche' => 'Java'
        ]);
        $this->assertSelectorTextContains('h5', 'Eclipse et Java');
        $this->assertCount(2, $crawler->filter('h5'));
    }

    // Filtre par nombre de formations
    public function testFiltreFormations()
    {
        $client = static::createClient();
        $client->request('GET', self::PLAYLISTS.'?sortBy=ASC');
        $this->assertSelectorTextContains('h5', "playlist test");
        $client->request('GET', self::PLAYLISTS.'?sortBy=DESC');
        $this->assertSelectorTextContains('h5', self::TITRE_H5);
    }

    // clic sur le bouton voir détail
    public function testBouton()
    {
        $client = static::createClient();
        $client->request('GET',self::PLAYLISTS);
        $client->clickLink("Voir détail");
        $response = $client->getResponse();
        $this->assertResponseStatusCodeSame(Response::HTTP_OK, $response->getStatusCode());
        $uri = $client->getRequest()->server->get("REQUEST_URI");
        $this->assertEquals(self::PLAYLISTS.'/playlist/28', $uri);
        $this->assertSelectorTextContains('h4', "playlist test");
    }
}
