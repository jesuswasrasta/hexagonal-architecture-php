<?php
declare(strict_types=1);

namespace App\Infrastructure;

use App\Application\ServizioCatalogoService;
use App\Domain\Services\CatalogoServizi;
use App\Domain\Services\DescrizioneServizio;
use App\Domain\Services\IdServizio;
use App\Domain\Services\ServiceStatus;
use App\Domain\Services\Servizio;
use App\Domain\Services\StatiServizio;
use App\Domain\Services\TitoloServizio;
use App\Domain\Users\SubscriptionDate;
use App\Domain\Users\Username;
use App\Domain\Users\Users;
use App\Shared\Domain\Aggregate\AggregateInterface;
use App\Shared\Domain\Aggregate\DomainIdInterface;
use App\Shared\Domain\Repository\RepositoryInterface;

class JsonServizioRepository implements RepositoryInterface
{
    private string $filename;
    private string $delimiter = '=>'; // delimiter string

    /**
     * Retrieves an aggregate by its ID from a JSON file.
     *
     * @param DomainIdInterface $id The ID of the aggregate to retrieve.
     *
     * @return AggregateInterface The retrieved aggregate.
     *
     * @throws JsonRepositoryException If the JSON file contains invalid data or the file cannot be read.
     */
    public function getById(DomainIdInterface $id): AggregateInterface
    {
        $servizi = CatalogoServizi::create($id);

        $this->filename = $id->value() . ".json";
        if (!file_exists($this->filename)) {
            return $servizi;
        }
        $fileContent = file_get_contents($this->filename);
        if ($fileContent !== false) {
            $serviziJson = json_decode($fileContent, true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($serviziJson)) {
                foreach ($serviziJson as $servizioJson){
                    $servizio = explode($this->delimiter, $servizioJson);
                    $id = new IdServizio($servizio[0]);
                    $titolo = new TitoloServizio($servizio[1]);
                    $desc = new DescrizioneServizio($servizio[2]);
                    $status = new ServiceStatus(StatiServizio::from($servizio[3]));
                    $s = new Servizio($id,$titolo,$desc,$status);
                    $servizi->addServizio($s);
                }
            } else {
                throw new JsonRepositoryException($this->filename, 'Invalid JSON data in file: ' . json_last_error_msg());
            }

            } else {
            throw new JsonRepositoryException($this->filename, 'Failed to read file contents');
        }
        return $servizi;

    }

    /**
     * Saves the aggregate to a JSON file.
     *
     * @param AggregateInterface $aggregate The aggregate to be saved.
     *
     * @throws JsonRepositoryException If there is an error encoding the data to JSON format.
     */
    public function save(AggregateInterface $aggregate): void
    {
        $this->filename = $aggregate->id()->value() . ".json";
        $jsonData = json_encode($aggregate->toStringArray(), JSON_PRETTY_PRINT);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new JsonRepositoryException($this->filename, 'Error encoding data to JSON format: ' . json_last_error_msg());
        }

        file_put_contents($this->filename, $jsonData);
    }
}
