<?php

namespace EfTech\BookLibrary\Controller;

use EfTech\BookLibrary\Infrastructure\Controller\ControllerInterface;
use EfTech\BookLibrary\Infrastructure\http\ServerResponseFactory;
use Psr\Log\LoggerInterface;
use EfTech\BookLibrary\Infrastructure\Validator\Assert;
use EfTech\BookLibrary\Service\SearchAuthorsService;
use EfTech\BookLibrary\Service\SearchAuthorsService\AuthorDto;
use EfTech\BookLibrary\Service\SearchAuthorsService\SearchAuthorsCriteria;
use JsonException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/** Контроллер поиска авторов
 *
 */
class GetAuthorsCollectionController implements ControllerInterface
{
    private ServerResponseFactory $serverResponseFactory;
    /** Логгер
     * @var LoggerInterface
     */
    private LoggerInterface $logger;
    /**
     *
     *
     * @var SearchAuthorsService
     */
    private SearchAuthorsService $searchAuthorsService;

    /**
     * @param LoggerInterface $logger
     * @param SearchAuthorsService $searchAuthorsService
     * @param ServerResponseFactory $serverResponseFactory
     */
    public function __construct(
        LoggerInterface $logger,
        SearchAuthorsService $searchAuthorsService,
        ServerResponseFactory $serverResponseFactory
    ) {
        $this->logger = $logger;
        $this->searchAuthorsService = $searchAuthorsService;
        $this->serverResponseFactory = $serverResponseFactory;
    }

    /** Валидирует параметры запроса
     * @param ServerRequestInterface $serverRequest - объект серверного http запроса
     * @return string|null - строка с ошибкой или нулл если ошибки нет
     */
    private function validateQueryParams(ServerRequestInterface $serverRequest): ?string
    {
        $paramsValidation = [
            'surname' => 'incorrect author surname',
            'id' => 'incorrect author id',
            'name' => 'incorrect author name',
            'birthday' => 'incorrect author birthday',
            'country' => 'incorrect author country'
        ];
        $params = array_merge($serverRequest->getQueryParams(), $serverRequest->getAttributes());

        return Assert::arrayElementsIsString($paramsValidation, $params);
    }

    /**
     * @param ServerRequestInterface $request - серверный объект запроса
     * @return ResponseInterface - объект http ответа
     * @throws JsonException
     */
    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        $this->logger->info("Ветка authors");

        $resultOfParamValidation = $this->validateQueryParams($request);

        if (null === $resultOfParamValidation) {
            $params = array_merge($request->getQueryParams(), $request->getAttributes());
            $foundAuthors = $this->searchAuthorsService->search(
                (new SearchAuthorsCriteria())
                    ->setId(isset($params['id']) ? (int)$params['id'] : null)
                    ->setName($params['name'] ?? null)
                    ->setBirthday($params['birthday'] ?? null)
                    ->setCountry($params['country'] ?? null)
                    ->setSurname($params['surname'] ?? null)
            );

            $httpCode = $this->buildHttpCode($foundAuthors);
            $result = $this->buildResult($foundAuthors);
        } else {
            $httpCode = 500;
            $result = [
                'status' => 'fail',
                'message' => $resultOfParamValidation
            ];
        }
        return $this->serverResponseFactory->createJsonResponse($httpCode, $result);
    }

    /** Определяет http code
     * @param array $foundAuthors
     * @return int
     */
    protected function buildHttpCode(array $foundAuthors): int
    {
        return 200;
    }

    /** Подготавливает данные для ответа
     * @param array $foundAuthors
     * @return array
     */
    protected function buildResult(array $foundAuthors)
    {
        $result = [];
        foreach ($foundAuthors as $foundAuthor) {
            $result[] = $this->serializeAuthor($foundAuthor);
        }
        return $result;
    }

    /**
     * @param AuthorDto $authorDto
     * @return array
     */
    final protected function serializeAuthor(AuthorDto $authorDto): array
    {
        return [
            'id' => $authorDto->getId(),
            'name' => $authorDto->getName(),
            'surname' => $authorDto->getSurname(),
            'birthday' => $authorDto->getBirthday(),
            'country' => $authorDto->getCountry(),
        ];
    }
}
