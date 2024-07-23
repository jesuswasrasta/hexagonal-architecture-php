<?php

namespace App\Unit\Domain;

use App\Domain\Services\IdServizio;
use App\Domain\Services\ServiceStatus;
use App\Domain\Services\Servizio;
use PHPUnit\Framework\TestCase;
use App\Domain\Services\CatalogoServizi;
class CatalogoServiziTest extends TestCase
{

    public function testQuandoAggiungoUnServizioLoStessoEDisabilitato(): void
    {
        $catalogoServizi = new CatalogoServizi();
        $id = new IdServizio(IdServizio::random());
        $servizio = new Servizio($id);
        $catalogoServizi->addServizio($servizio);
        $servizioCorrente = $catalogoServizi->getServizio($servizio->id());
        $this->assertEquals($servizioCorrente->getStatus(), new ServiceStatus());
    }

    public function testServizio(): void
    {
        $id = new IdServizio(IdServizio::random());
        $servizio = new Servizio($id);
        $this->assertNotNull($servizio->id());
    }

    public function testAddServizio(): void
    {
        $catalogoServizi = new CatalogoServizi();
        $id = new IdServizio(IdServizio::random());
        $servizio = new Servizio($id);
        $catalogoServizi->addServizio($servizio);
        $this->assertNotNull($catalogoServizi->getServizio($servizio->id()));
    }

    public function testGetStatus(): void
    {
        $id = new IdServizio(IdServizio::random());
        $servizio = new Servizio($id);
        $this->assertNotNull($servizio->getStatus());
    }

}
