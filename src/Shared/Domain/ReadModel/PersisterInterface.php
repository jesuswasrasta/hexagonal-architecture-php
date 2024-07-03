<?php
declare(strict_types=1);

namespace App\Shared\Domain\ReadModel;


/* Nella mio modo di chiamare le cose,
 * il Persister fa il CRUD sul ReadModel.
 *
 * Ce ne possono essere n, uno per projection.
 * Una Projection è una "vista denornmalizzata",
 * un modo di vedere i dati che è ottimizzato per una certa query.
 *
 * È diverso da Repository:
 * Il repository carica solo aggregati "by Id()",
 * e li salva con save()
 *
 * Il Persister scrive sul ReadModel, un altro "DB", separato dal DB dove scrive il repository.
 * Il Repository scrive su WriteModel, un DB ottimizzato per salvataggio e recupero edegli aggregati.
 * */

interface PersisterInterface
{
    // TODO: implementa
}
