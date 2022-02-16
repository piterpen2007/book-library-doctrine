<?php

namespace EfTech\BookLibrary\Controller;

use EfTech\BookLibrary\Exception\RuntimeException;
use EfTech\BookLibrary\Infrastructure\Auth\HttpAuthProvider;
use EfTech\BookLibrary\Infrastructure\Controller\ControllerInterface;
use EfTech\BookLibrary\Infrastructure\http\ServerResponseFactory;
use EfTech\BookLibrary\Infrastructure\ViewTemplate\ViewTemplateInterface;
use Nyholm\Psr7\Uri;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\UriFactoryInterface;
use Throwable;

class LoginController implements ControllerInterface
{
    private ServerResponseFactory $serverResponseFactory;
    private HttpAuthProvider $authProvider;
    /** шаблонизатор
     * @var ViewTemplateInterface
     */
    private ViewTemplateInterface $template;

    /** Фабрика для создания ури
     * @var UriFactoryInterface
     */
    private UriFactoryInterface $uriFactory;

    /**
     * @param ViewTemplateInterface $template
     * @param HttpAuthProvider $authProvider
     * @param UriFactoryInterface $uriFactory
     * @param ServerResponseFactory $serverResponseFactory
     */
    public function __construct(
        ViewTemplateInterface $template,
        HttpAuthProvider $authProvider,
        UriFactoryInterface $uriFactory,
        \EfTech\BookLibrary\Infrastructure\http\ServerResponseFactory $serverResponseFactory
    ) {
        $this->template = $template;
        $this->authProvider = $authProvider;
        $this->uriFactory = $uriFactory;
        $this->serverResponseFactory = $serverResponseFactory;
    }


    /** Обработка http запроса
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     */
    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        try {
            $response = $this->doLogin($request);
        } catch (Throwable $e) {
            $response = $this->buildErrorResponse($e);
        }
        return $response;
    }

    /**
     * @param Throwable $e
     * @return ResponseInterface
     */
    private function buildErrorResponse(Throwable $e): ResponseInterface
    {
        $httpCode = 500;
        $contex = [
            'errors' => [
                $e->getMessage()
            ]
        ];
        $html = $this->template->render(
            'errors.twig',
            $contex
        );
        return $this->serverResponseFactory->createHtmlResponse($httpCode, $html);
    }

    private function doLogin(ServerRequestInterface $request): ResponseInterface
    {
        $response = null;
        $contex = [];
        if ('POST' === $request->getMethod()) {
            $authData = [];
            parse_str($request->getBody(), $authData);

            $this->validateAuthData($authData);
            if ($this->isAuth($authData['login'], $authData['password'])) {
                $queryParams = $request->getQueryParams();
                $redirect = array_key_exists('redirect', $queryParams)
                    ? $this->uriFactory->createUri(($queryParams['redirect']))
                    : $this->uriFactory->createUri($queryParams['/']);
                $response = $this->serverResponseFactory->redirect($redirect);
            } else {
                $contex['errMsg'] = 'Логин и пароль не подходят';
            }

//            if (array_key_exists('redirect', $queryParams)) {
//                $response = ServerResponseFactory::redirect(Uri::createFromString($queryParams['redirect']));
//            }
        }
        if (null === $response) {
            $html = $this->template->render('login.twig', $contex);
            $response = $this->serverResponseFactory->createHtmlResponse(200, $html);
        }
        return $response;
    }

    /** Логика валидации данных формы аутификации
     * @param array $authData
     */
    private function validateAuthData(array $authData): void
    {
        if (false === array_key_exists('login', $authData)) {
            throw new RuntimeException('Отсутствует логин');
        }
        if (false === is_string($authData['login'])) {
            throw new RuntimeException('Логин имеет неверный формат');
        }

        if (false === array_key_exists('password', $authData)) {
            throw new RuntimeException('Отсутствует password');
        }
        if (false === is_string($authData['password'])) {
            throw new RuntimeException('password имеет неверный формат');
        }
    }

    /** Проводит аутентификацию пользователя
     * @param string $login
     * @param string $password
     * @return bool
     */
    private function isAuth(string $login, string $password): bool
    {
        return $this->authProvider->auth($login, $password);
    }
}
