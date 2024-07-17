<?php
declare(strict_types=1);

namespace App\Infrastructure;

use App\Domain\Users\SubscriptionDate;
use App\Domain\Users\Username;
use App\Domain\Users\Users;
use App\Shared\Domain\Aggregate\AggregateInterface;
use App\Shared\Domain\Aggregate\DomainIdInterface;
use App\Shared\Domain\Repository\RepositoryInterface;

// Questo è un Repository.
// Serve per caricare gli aggregati e salvarli.
// Ha solo 2 metodi:
// - getById() -> carica l'aggregato che ha uno specifico id
// - save() -> salva l'aggregato
//
// In questa prima implementazione il repository
// mantiene accoppiamento fra il modello di dominio,
// ovvero l'aggregato così com'è fatto, con il modello di lettura,
// ovvero le iste" dei dati dell'aggregato che di volta in volta
// risultano sono interessanti per qualcuno/qualcosa all'esterno.
//
// Andando avanti vedremo che il modello di lettura sarà separato:
// come rappresento e storicizzo i dati del dominio è un conto,
// come li combino per darli all'esterno è un altro... 😉
//
// Questo getterà le basi per introdurre concetti quali:
//  - ReadModel (vs WriteModel)
//  - Projection
//  - CQRS
// che vedremo più avanti 👍
//
class FileUsersRepository implements RepositoryInterface
{
    private string $filename;

    /**
     * @inheritDoc
     * @throws \Exception
     */
    public function getById(DomainIdInterface $id): AggregateInterface
    {
        $users = Users::create($id);

        $this->filename = $id->value() . ".txt";
        if (!file_exists($this->filename)) {
            return $users;
        }

        $fileContent = file($this->filename, FILE_IGNORE_NEW_LINES);
        if ($fileContent !== false) {
            foreach ($fileContent as $line) {
                $users->add(new Username(explode(" => ", $line)[0]), new SubscriptionDate(new \DateTime(explode(" => ", $line)[1])));
            }
        }
        return $users;
    }

    /**
     * @inheritDoc
     */
    public function save(AggregateInterface $aggregate): void
    {
        $this->filename = $aggregate->id()->value() . ".txt";
        if (!file_exists($this->filename)) {
            touch($this->filename);
        }

        file_put_contents($this->filename, implode("\n", $aggregate->toStringArray()));
    }
}
