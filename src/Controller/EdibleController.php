<?php

declare(strict_types=1);

namespace App\Controller;

use App\Enum\EdibleType;
use App\Enum\EdibleUnit;
use App\Filters\EdibleFilter;
use App\Service\EdibleFormatter\EdibleFormatterInterface;
use App\Service\EdibleParser\EdibleParserInterface;
use App\Service\EdibleStorage\EdibleStorageServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class EdibleController.
 *
 * @package App\Controller
 */
class EdibleController extends AbstractController
{
    /**
     * @param EdibleStorageServiceInterface $storageService
     * @param EdibleFormatterInterface $formatter
     * @param EdibleParserInterface $edibleParser
     */
    public function __construct(
        private readonly EdibleStorageServiceInterface $storageService,
        private readonly EdibleFormatterInterface $formatter,
        private readonly EdibleParserInterface $edibleParser
    ) {}

    /**
     * @return Response
     */
    public function index(Request $request): Response
    {
        $type = EdibleType::tryFrom($request->query->get('type', ''));
        $unit = EdibleUnit::tryFrom($request->query->get('unit', '')) ?? EdibleStorageServiceInterface::STORAGE_UNIT;

        $keyword = $request->query->get('keyword');
        $minGrams = $request->query->get('minGrams');
        $maxGrams = $request->query->get('maxGrams');

        $filter = new EdibleFilter(
            $keyword,
            $minGrams ? (float) $minGrams : null,
            $maxGrams ? (float) ($maxGrams) : null,
        );

        $edibles = $this->storageService->list($type, $filter);

        return $this->json($this->formatEdibles($edibles, $unit));
    }

    public function add(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);

        $edible = $this->edibleParser->parseEdible($data);

        if ($edible === null) {
            return $this->json(['error' => 'Invalid edible data provided'], Response::HTTP_BAD_REQUEST);
        }

        $this->storageService->add($edible);

        $edibles = $this->storageService->list(null, null);
        $formattedEdibles = $this->formatEdibles($edibles, EdibleStorageServiceInterface::STORAGE_UNIT);

        return $this->json($formattedEdibles, Response::HTTP_CREATED);
    }

    private function formatEdibles(array $edibles, EdibleUnit $unit): array
    {
        $formattedEdibles = [];

        foreach ($edibles as $edible) {
            $formattedEdibles[] = $this->formatter->format($edible, $unit);
        }

        return $formattedEdibles;
    }
}