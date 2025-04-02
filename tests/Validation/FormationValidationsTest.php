<?php

namespace App\Tests\Validations;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use App\Entity\Formation;
use DateTime;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class FormationValidationsTest extends KernelTestCase
{
    public function getFormation(): Formation {
        return (new Formation())
            ->setTitle("Le titre cool de la formation")
            ->setPublishedAt(new DateTime("2026-11-1"));
    }

    public function testDateDevraitEchouerLorsquElleEstDansLeFutur(): void
    {
        $formation = $this->getFormation()->setPublishedAt(new DateTime("2026-11-1"));
        $this->assertErrors($formation, 1, "[ERROR] 2026-11-1 est bien postérieure à aujourd'hui");
    }

    public function testDateDevraitReussirLorsquElleEstDansLePassé(): void
    {
        $formation = $this->getFormation()->setPublishedAt(new DateTime("2020-07-5"));
        $this->assertErrors($formation, 0, "[OK !!] 2020-07-5 est bien antérieure à aujourd'hui");
    }

    public function assertErrors(Formation $formation, int $nombreErreurs, string $message="") {
        self::bootKernel();
        $validator = self::getContainer()->get(ValidatorInterface::class);
        $error = $validator->validate($formation);
        $this->assertCount($nombreErreurs, $error, $message);
    }
}

