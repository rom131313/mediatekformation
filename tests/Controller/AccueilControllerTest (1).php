<?php
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AccueilControllerTest extends WebTestCase
{
    public function testPageAccueilEstAccessible(): void {
        $client = static::createClient();
        $client->request('GET', '/');
        // la réponse HTTP doit etre 200
        $this->assertResponseIsSuccessful();
        // Pour être sûr on vérifie qu'il y a bien le h3
        $this->assertSelectorTextContains('h3', 'Bienvenue sur le site de MediaTek86 consacré aux formations en ligne');
    }
}
