<?php

namespace EfTech\BookLibrary\ConsoleCommand;

use EfTech\BookLibrary\Infrastructure\Console\CommandInterface;
use EfTech\BookLibrary\Infrastructure\Console\Output\OutputInterface;
use EfTech\BookLibrary\Service\SearchTextDocumentService;
use EfTech\BookLibrary\Service\SearchTextDocumentService\SearchTextDocumentServiceCriteria;
use EfTech\BookLibrary\Service\SearchTextDocumentService\TextDocumentDto;
use JsonException;

/**
 *
 *
 * @package EfTech\BookLibrary\Controller
 */
final class FindBooks implements CommandInterface
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
     * @var SearchTextDocumentService
     */
    private SearchTextDocumentService $searchTextDocumentService;

    /**
     * @param OutputInterface $output
     * @param SearchTextDocumentService $searchTextDocumentService
     */
    public function __construct(OutputInterface $output, SearchTextDocumentService $searchTextDocumentService)
    {
        $this->output = $output;
        $this->searchTextDocumentService = $searchTextDocumentService;
    }

    /**
     * @inheritDoc
     */
    public static function getShortOption(): string
    {
        return '';
    }

    /**
     * @inheritDoc
     */
    public static function getLongOption(): array
    {
        return [
            'author_surname:',
            'id:',
            'title:'
        ];
    }

    /**
     * @inheritDoc
     * @throws JsonException
     */
    public function __invoke(array $params): void
    {
        $textDocumentsDto = $this->searchTextDocumentService->search((
            new SearchTextDocumentServiceCriteria())
            ->setAuthorSurname($params['author_surname'] ?? null)
            ->setId(isset($params['id']) ? (int)$params['id'] : null)
            ->setTitle($params['title'] ?? null));
        $jsonData = $this->buildJsonData($textDocumentsDto);
        $this->output->print(json_encode(
            $jsonData,
            JSON_THROW_ON_ERROR | JSON_PRETTY_PRINT |
            JSON_UNESCAPED_UNICODE
        ));
    }
    /**
     * @param TextDocumentDto[]
     * @return array
     */
    private function buildJsonData(array $foundTextDocuments): array
    {
        $result = [];
        foreach ($foundTextDocuments as $foundTextDocument) {
            $result[] = $this->serializeTextDocument($foundTextDocument);
        }
        return $result;
    }

    /**
     * @param TextDocumentDto $textDocument
     * @return array
     */
    private function serializeTextDocument(TextDocumentDto $textDocument): array
    {
        $jsonData = [
                'id' => $textDocument->getId(),
                'title' => $textDocument->getTitle(),
                'year' => $textDocument->getYear(),
                'title_for_printing' => $textDocument->getTitleForPrinting()
        ];
        if (TextDocumentDto::TYPE_MAGAZINE === $textDocument->getType()) {
            $jsonData['number'] = $textDocument->getNumber();
        }
        $authorDto = $textDocument->getAuthor();
        if (null !== $authorDto) {
            $jsonData['author'] = [
                'id' => $authorDto->getId(),
                'name' => $authorDto->getName(),
                'surname' => $authorDto->getSurname(),
                'birthday' => $authorDto->getBirthday(),
                'country' => $authorDto->getCountry(),
                ];
        } else {
            $jsonData['author'] = null;
        }
        return $jsonData;
    }
}
