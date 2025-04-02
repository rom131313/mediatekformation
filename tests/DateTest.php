<?php
use PHPUnit\Framework\TestCase;
use App\Entity\Formation;

class DateTest extends TestCase
{
    public function testGetPublishedAtString()
    {
        $formation = new Formation();
        // Définit une date de publication
        $formation->setPublishedAt(new \DateTime('2024-12-08 16:01:24'));
        // Vérifie que la méthode retourne la date au format souhaité
        $this->assertEquals('08/12/2024', $formation->getPublishedAtString());
    }

    public function testGetPublishedAtStringWhenNull()
    {
        $formation = new Formation();
        // Vérifie que la méthode retourne une chaîne vide
        $this->assertEquals('', $formation->getPublishedAtString());
    }
}
