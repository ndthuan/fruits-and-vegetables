<?php

declare(strict_types=1);

namespace App\Service\EdibleStorage;

use App\Enum\EdibleType;
use App\Filters\EdibleFilter;
use App\Model\Edible;
use App\Service\EdibleStorage\Collection\EdibleCollectionInterface;
use App\Service\EdibleStorage\Exception\DuplicateEdibleException;
use App\Service\EdibleStorage\Exception\EdibleNotFoundException;
use App\Service\EdibleStorage\Exception\InvalidEdibleTypeException;

/**
 * Class EdibleStorageService.
 *
 * @package App\Service\EdibleStorage
 */
class EdibleStorageService implements EdibleStorageServiceInterface
{
    private int $maxId = 0;

    /**
     * @var RequestReaderInterface
     */
    private RequestReaderInterface $requestReader;

    /**
     * Associative array of EdibleCollectionInterface instances, indexed by EdibleType value.
     *
     * @var EdibleCollectionInterface[]
     */
    private array $collections;

    /**
     * Associative array of edible ID to edible type mappings.
     *
     * @var string[]
     */
    private array $idToTypeMap = [];

    /**
     * @param RequestReaderInterface $requestReader
     * @param EdibleCollectionInterface[] $collections
     */
    public function __construct(RequestReaderInterface $requestReader, array $collections)
    {
        $this->requestReader = $requestReader;

        $this->initCollections($collections);
        $this->loadFromRequest();
    }

    /**
     * @param EdibleCollectionInterface[] $collections
     *
     * @return void
     */
    private function initCollections(array $collections): void
    {
        $this->collections = [];

        foreach ($collections as $collection) {
            $this->collections[$collection->getAcceptableType()->value] = $collection;
        }
    }

    /**
     * @return void
     */
    private function loadFromRequest(): void
    {
        $edibles = $this->requestReader->read();

        foreach ($edibles as $edible) {
            $this->add($edible);
        }
    }

    /**
     * @inheritDoc
     */
    public function add(Edible $edible): void
    {
        $this->maintainEdibleId($edible);

        $type = $edible->getType()->value;

        if (!isset($this->collections[$type])) {
            throw new InvalidEdibleTypeException(sprintf('No collection found for edible type "%s"', $type));
        }

        $this->collections[$type]->add($edible);
        $this->idToTypeMap[$edible->getId()] = $type;
    }

    /**
     * @inheritDoc
     */
    public function remove(int $id): void
    {
        if (!isset($this->idToTypeMap[$id])) {
            throw new EdibleNotFoundException(sprintf('No edible found with ID "%d"', $id));
        }

        $type = $this->idToTypeMap[$id];
        $this->collections[$type]->remove($id);
        unset($this->idToTypeMap[$id]);
    }

    /**
     * @inheritDoc
     */
    public function list(?EdibleType $type, ?EdibleFilter $filter): array
    {
        $results = [];

        foreach ($this->collections as $collection) {
            if ($type && $collection->getAcceptableType() !== $type) {
                continue;
            }

            $results = array_merge($results, $collection->list($filter));
        }

        return $results;
    }

    protected function maintainEdibleId(Edible $edible): void
    {
        if ($edible->getId() === null) {
            $edible->setId(++$this->maxId);

            return;
        }

        if (isset($this->idToTypeMap[$edible->getId()])) {
            throw new DuplicateEdibleException(sprintf('Edible with ID "%d" already exists', $edible->getId()));
        }

        if ($edible->getId() > $this->maxId) {
            $this->maxId = $edible->getId();
        }
    }
}