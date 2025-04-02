<?php

namespace App\Tests\Repository;

use App\Entity\Formation;
use App\Repository\FormationRepository;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class FormationRepositoryTest extends KernelTestCase
{
    public function recupRepository(): FormationRepository {
        self::bootKernel();
        return self::getContainer()->get(FormationRepository::class);
    }
    


    public function testNbFormations() {
        $repository = $this->recupRepository();
        $nbFormations = $repository->count([]);
        $this->assertEquals(237, $nbFormations);
    }

    public function newFormation(): Formation {
        return (new Formation())
            ->setTitle("Titre de la formation")
            ->setDescription("Description de la formation")
            ->setPublishedAt(new DateTime('now'));
    }
    

    public function testAddFormation() {
        $repository = $this->recupRepository();
        $formation = $this->newFormation();
        $nbFormations = $repository->count([]);
        $repository->add($formation);
        $this->assertEquals($nbFormations + 1, $repository->count([]), "Erreur lors de l'ajout de la formation");
    }

    public function testRemoveFormation() {
        $repository = $this->recupRepository();
        $formation = $this->newFormation();
        $repository->add($formation);
        $nbFormations = $repository->count([]);
        $repository->remove($formation);
        $this->assertEquals($nbFormations - 1, $repository->count([]), "Erreur lors de la suppression de la catégorie");
    }

    public function testFindAllOrderBy() {
        $repository = $this->recupRepository();
        $formation = $this->newFormation();
        $repository->add($formation);
        $formations = $repository->findAllOrderBy("title", "DESC");
        $nbFormations = count($formations);
        $this->assertEquals(238, $nbFormations);
        $this->assertEquals("UML : Diagramme de paquetages", $formations[0]->getTitle());
    }
    
}
