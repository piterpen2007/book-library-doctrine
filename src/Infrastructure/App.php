<?php

namespace EfTech\BookLibrary\Infrastructure;
use EfTech\BookLibrary\Exception\RuntimeException;
use EfTech\BookLibrary\Infrastructure\Controller\ControllerInterface;
use EfTech\BookLibrary\Infrastructure\DI\ServiceLocator;
use EfTech\BookLibrary\Infrastructure\http\httpResponse;
use EfTech\BookLibrary\Infrastructure\http\ServerRequest;
use EfTech\BookLibrary\Infrastructure\http\ServerResponseFactory;
use EfTech\BookLibrary\Infrastructure\Logger\LoggerInterface;
use Throwable;
use EfTech\BookLibrary\Exception;
use EfTech\BookLibrary\Infrastructure\View\RenderInterface;
/**
 * Ядро приложения
 */
final class App
{
    /**
     * @var array Обработчики запросов
     */
    private array $handlers;

    /** Конфиг приложения
     * @var AppConfig
     */
    private AppConfig $appConfig;
    /** Логирование
     * @var LoggerInterface
     */
    private LoggerInterface $logger;

    /** Компонент отвечающий за рендеринг
     * @var RenderInterface
     */
    private RenderInterface $render;
    /** Локатор сервисов
     * @var ServiceLocator
     */
    private ServiceLocator $serviceLocator;


    /** Инициация обработки ошибок
     *
     */
    private function initErrorHandling():void
    {
        set_error_handler(static function(int $errNom, string $errStr){
            throw new RuntimeException($errStr);
        });
    }



    /** Локатор сервисов
     * @param ServiceLocator $serviceLocator
     */
    public function __construct(ServiceLocator $serviceLocator)
    {
        $this->handlers = $serviceLocator->get('handlers');
        $this->logger = $serviceLocator->get(LoggerInterface::class);
        $this->render = $serviceLocator->get(RenderInterface::class);
        $this->appConfig = $serviceLocator->get(AppConfig::class);
        $this->serviceLocator = $serviceLocator;
        $this->initErrorHandling();
    }
    private function getController(string $urlPath):callable
    {
        if(is_callable($this->handlers[$urlPath])) {
            $controller = $this->handlers[$urlPath];
        } elseif (is_string($this->handlers[$urlPath]) &&
            is_subclass_of($this->handlers[$urlPath], ControllerInterface::class, true)) {
            $controller = new ($this->handlers[$urlPath])($this->serviceLocator);

        } else {
            throw new RuntimeException("Для url '$urlPath' зарегистрирован некорректный обработчик");
        }
        return $controller;
    }
    /** Обработчик запроса
     * @param ServerRequest $serverRequest - объект серверного http запроса
     * @return httpResponse - реез ответ
     */
    public function dispath(ServerRequest $serverRequest):httpResponse
    {
        try {
            $appConfig = $this->appConfig;
            $logger = $this->logger;

            $urlPath = $serverRequest->getUri()->getPath();
            $logger->log('Url request received' . $urlPath);

            if(array_key_exists($urlPath,$this->handlers)) {

                $controller = $this->getController($urlPath);
                $httpResponse = $controller($serverRequest);

                if (!($httpResponse instanceof httpResponse)) {
                    throw new Exception\UnexpectedValueException('Контроллер вернул некорректный результат');
                }
            } else {
                $httpResponse = ServerResponseFactory::createJsonResponse(
                    404,
                    ['status' => 'fail', 'message' => 'unsupported request']
                );
            }

        } catch (Exception\invalidDataStructureException $e) {
            $httpResponse = ServerResponseFactory::createJsonResponse(
                503,
                ['status' => 'fail', 'message' => $e->getMessage()]
            );
        } catch (Throwable $e) {
            $errMsg = ($this->appConfig instanceof AppConfig && !$appConfig->isHideErrorMsg())
                || $e instanceof Exception\ErrorCreateAppConfigException
                ? $e->getMessage()
                : 'system error';
            try {
                $this->logger->log($e->getMessage());
            } catch (Throwable $e) {}
                $httpResponse = ServerResponseFactory::createJsonResponse(
                    500,
                    ['status' => 'fail', 'message' => $errMsg]
                );
        }
        $this->render->render($httpResponse);
        return $httpResponse;
    }


}