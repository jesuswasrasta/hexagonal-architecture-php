<?php

namespace App\Unit\Domain;

use App\Domain\Services\CatalogoServiziId;
use App\Domain\Services\IdServizio;
use App\Domain\Services\ServiceStatus;
use App\Domain\Services\Servizio;
use App\Domain\Services\TitoloServizio;
use App\Domain\Services\DescrizioneServizio;
use PHPUnit\Framework\TestCase;
use App\Domain\Services\CatalogoServizi;

class CatalogoServiziTest extends TestCase
{


    public function testQuandoAggiungoUnServizioLoStessoEDisabilitato(): void
    {
        $catalogoServizi = CatalogoServizi::create(CatalogoServiziId::random());
        $id = new IdServizio(IdServizio::random());
        $titolo = new TitoloServizio('titol-o');
        $desc = new DescrizioneServizio('-');
        $servizio = new Servizio($id,$titolo,$desc,new ServiceStatus());
        $catalogoServizi->addServizio($servizio);
        $servizioCorrente = $catalogoServizi->getServizio($servizio->id());
        $this->assertEquals($servizioCorrente->getStatus(), new ServiceStatus());
    }

    public function testServizio(): void
    {
        $id = new IdServizio(IdServizio::random());
        $titolo = new TitoloServizio('titol-o');
        $desc = new DescrizioneServizio('-');
        $servizio = new Servizio($id,$titolo,$desc,new ServiceStatus());
        $this->assertNotNull($servizio->id());
    }

    public function testAddServizio(): void
    {
        $catalogoServizi = CatalogoServizi::create(CatalogoServiziId::random());
        $id = new IdServizio(IdServizio::random());
        $titolo = new TitoloServizio('titol-o');
        $desc = new DescrizioneServizio('-');
        $servizio = new Servizio($id,$titolo,$desc,new ServiceStatus());
        $catalogoServizi->addServizio($servizio);
        $this->assertNotNull($catalogoServizi->getServizio($servizio->id()));
    }

    public function testGetStatus(): void
    {
        $id = new IdServizio(IdServizio::random());
        $titolo = new TitoloServizio('titol-o');
        $desc = new DescrizioneServizio('-');
        $servizio = new Servizio($id,$titolo,$desc,new ServiceStatus());
        $this->assertNotNull($servizio->getStatus());
    }

    public function testTitoloServizio():void
    {
        $titolo = new TitoloServizio('titol-o');
        $this->assertEquals('titol-o', $titolo->value());
    }

    public function testTitoloServizioNonPiuLungo25Caratteri()
    {
        try{
            $titolo = new TitoloServizio('1234567890101112113141516171819202122232425');
        }catch (\Exception $e){
            $this->assertEquals('String must be mx 25chars', $e->getMessage());
        }
    }
    public function testTitoloServizioFormato()
    {
        try{
            $titolo = new TitoloServizio('asasasasas-');
        }catch (\Exception $e){
            $this->assertEquals('Invalid kebab-case string.', $e->getMessage());
        }
    }
    public function testDescrizioneServizioNonPiuLungo300Caratteri()
    {
        try{
            $desc = new DescrizioneServizio('-');
            $this->assertEquals($desc->value(),'-');
        }catch (\Exception $e){
            $this->assertEquals('String must be mx 300chars', $e->getMessage());
        }
    }
    public function testDescrizioneServizio()
    {
        try{
            $desc = new DescrizioneServizio('asasasasas');
            $this->assertEquals($desc->value(),'asasasasas');
        }catch (\Exception $e){
            $this->assertEquals('Invalid kebab-case string.', $e->getMessage());
        }
    }


}
