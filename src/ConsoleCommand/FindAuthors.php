<?php

namespace EfTech\BookLibrary\ConsoleCommand;

use EfTech\BookLibrary\Infrastructure\Console\CommandInterface;
use EfTech\BookLibrary\Infrastructure\Console\Output\OutputInterface;
use EfTech\BookLibrary\Service\SearchAuthorsService\AuthorDto;
use EfTech\BookLibrary\Service\SearchAuthorsService\SearchAuthorsCriteria;
use EfTech\BookLibrary\Service\SearchAuthorsService\SearchAuthorsService;
use JsonException;

/**
 *
 *
 * @package EfTech\BookLibrary\Controller
 */
final class FindAuthors implements CommandInterface
{
    /**
     *
     *
     * @var OutputInterface
     */
    private OutputInterface $output;
    /**
     *
     *
     * @var SearchAuthorsService
     */
    private SearchAuthorsService $searchAuthorsService;
    /**
     * FindAuthors constructor.
     *
     * @param OutputInterface $output
     * @param SearchAuthorsService $searchAuthorsService
     */
    public function __construct(OutputInterface $output, SearchAuthorsService $searchAuthorsService)
    {
        $this->output = $output;
        $this->searchAuthorsService = $searchAuthorsService;
    }


    /**
     * @inheritDoc
     */
    public static function getShortOption(): string
    {
        return 'n:';
    }

    /**
     * @inheritDoc
     */
    public static function getLongOption(): array
    {
        return [
            'surname:',
            'id:'
        ];
    }

    /**
     * @inheritDoc
     * @throws JsonException
     */
    public function __invoke(array $params): void
    {
        $dtoCollection = $this->searchAuthorsService->search(
            (new SearchAuthorsCriteria())
                ->setSurname($params['surname'] ?? null)
                ->setId($params['id'] ?? null)
        );
        $jsonData = $this->buildJsonData($dtoCollection);
        $this->output->print(json_encode($jsonData,
            JSON_THROW_ON_ERROR | JSON_PRETTY_PRINT |
            JSON_UNESCAPED_UNICODE));
    }
    /**
     *
     *
     * @param AuthorDto[] $dtoCollection
     *
     * @return array
     */
    private function buildJsonData(array $dtoCollection):array
    {
        $result = [];
        foreach ($dtoCollection as $authorDto) {
            $result[] = [
                'id' => $authorDto->getId(),
                'name' => $authorDto->getName(),
                'surname' => $authorDto->getSurname(),
                'birthday' => $authorDto->getBirthday(),
                'country' => $authorDto->getCountry(),
            ];
        }
        return $result;
    }

}