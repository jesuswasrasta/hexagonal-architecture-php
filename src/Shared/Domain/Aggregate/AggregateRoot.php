<?php

declare(strict_types=1);

namespace App\Shared\Domain\Aggregate;

// Classe che rende evidente il concetto di Aggregate Root (AR).
// Ora è vuota, potrà sembrare una inutile complicazione (e in effeti lo è,
// almeno per ora). In futuro la estenderemo per gestire gli eventi 😉
//
// Un AR è una entità (da qui il fatto che estenda EntityBase).
// Tra tutte le entità che compongono l'aggregato, è quella che
// rinforza le invarianti di dominio.
// Ogni comando che modifica l'aggregato deve passare per l'AR.
// È il 'rappresentante sindacale' dell'aggregato 😁
abstract class AggregateRoot extends EntityBase
{
}
