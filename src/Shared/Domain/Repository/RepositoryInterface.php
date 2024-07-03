<?php
declare(strict_types=1);

namespace App\Shared\Domain\Repository;


/*

Per adesso Repository legge e scrive da file, secco
tengo insieme modello di scrittura save() e mdoello di lettura getById()
poi introdurrò i ReadModel e Persister

Indica nei commenrti che questo repsoitory per ora ha questo"difetto": modello d lettura e scrittura sono insieme

Adesso il repository legge e salva EntityBase, ma dovrebbe legegre e salvare AggregateInterface: val la pena d farla?
Sì, vedi

public abstract class AggregateRoot : IAggregate, IEquatable<IAggregate>

su BrewUp

* */

use App\Shared\Domain\Aggregate\AggregateInterface;
use App\Shared\Domain\Aggregate\DomainIdInterface;

// Il Repository è colui che carica e salva gli aggregati.
// Ha due metodi:
// - getById() -> carica l'aggregato che ha uno specifico id
// - save() -> salva l'aggregato
//
// Tutti i repository implementano questa interfaccia.
// C'è un Repository per ogni aggregato.

interface RepositoryInterface
{
    /**
     * Get an entity by its ID
     *
     * @param DomainIdInterface $id The ID of the aggregate to retrieve
     * @return AggregateInterface The retrieved aggregate
     */
    public function getById(DomainIdInterface $id) : AggregateInterface;

    /**
     * Save an aggregate
     *
     * @param AggregateInterface $aggregate The aggregate to be saved
     * @return void
     */
    public function save(AggregateInterface $aggregate) : void;
}
