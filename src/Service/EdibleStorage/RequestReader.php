<?php

declare(strict_types=1);

namespace App\Service\EdibleStorage;

use App\Enum\EdibleType;
use App\Enum\EdibleUnit;
use App\Model\Edible;
use App\Service\EdibleParser\EdibleParserInterface;
use App\Service\UnitConverter\UnitConverterInterface;

/**
 * Class RequestReader.
 *
 * @package App\Service\EdibleStorage
 */
readonly class RequestReader implements RequestReaderInterface
{
    /**
     * @param string $requestFilePath
     * @param EdibleParserInterface $edibleParser
     */
    public function __construct(private string $requestFilePath, private EdibleParserInterface $edibleParser){}

    /**
     * @inheritDoc
     */
    public function read(): array
    {
        $edibles = [];

        $json = file_get_contents($this->requestFilePath);

        $data = json_decode($json, true);

        foreach ($data as $item) {
            $edible = $this->edibleParser->parseEdible($item);

            if ($edible !== null) {
                $edibles[] = $edible;
            }
        }

        return $edibles;
    }
}