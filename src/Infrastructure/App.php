<?php

namespace EfTech\BookLibrary\Infrastructure;
use EfTech\BookLibrary\Infrastructure\Logger\LoggerInterface;
use Throwable;
use UnexpectedValueException;
use EfTech\BookLibrary\Exception;
/**
 * Ядро приложения
 */
final class App
{
    /**
     * @var array Обработчики запросов
     */
    private array $handlers;
    /** Фабрика для создания логгеров
     * @var callable
     */
    private $loggerFactory;
    /**     Фабрика для создания конфига приложения
     * @var callable
     */
    private $appConfigFactory;
    /** Конфиг приложения
     * @var AppConfig|null
     */
    private ?AppConfig $appConfig = null;
    /** Логирование
     * @var LoggerInterface|null
     */
    private ?LoggerInterface $logger = null;

    /** Инициация обработки ошибок
     *
     */
    private function initErrorHandling():void
    {
        set_error_handler(static function(int $errNom, string $errStr){
            throw new Exception\RuntimeException($errStr);
        });
    }

    /**
     * @return LoggerInterface
     */
    public function getLogger(): LoggerInterface
    {
        if (null === $this->logger) {
            $logger =  call_user_func($this->loggerFactory,$this->getAppConfig());
            if (!($logger instanceof LoggerInterface)) {
                throw new UnexpectedValueException('incorrect logger');
            }
            $this->logger = $logger;
        }
        return $this->logger;
    }


    /**
     * @param array $handler - Обработчики запросов
     * @param callable $loggerFactory - Фабрика для создания логгеров
     * @param callable $appConfigFactory - Фабрика для создания конфига приложения
     */
    public function __construct(array $handler, callable $loggerFactory, callable $appConfigFactory)
    {
        $this->handlers = $handler;
        $this->loggerFactory = $loggerFactory;
        $this->appConfigFactory = $appConfigFactory;
        $this->initErrorHandling();
    }

    /**
     * @return AppConfig
     */
    private function getAppConfig(): AppConfig
    {
        if (null === $this->appConfig) {
            $appConfig = call_user_func($this->appConfigFactory);
            if (!($appConfig instanceof AppConfig)) {
                throw new UnexpectedValueException('incorrect application config');
            }
            $this->appConfig = $appConfig;
        }
        return $this->appConfig;
    }

    /** Извлекает параметры запроса из URL
     * @param string $requestUri - данные запроса uri
     * @return array - параметры запроса
     */
    private function extractQueryParams(string $requestUri):array
    {
        $query = parse_url($requestUri, PHP_URL_QUERY);
        $requestParams = [];
        parse_str($query,$requestParams );
        return $requestParams;
    }

    public function dispath(string $requestUri):array
    {
        $appConfig = null;
        try {
            $appConfig = $this->getAppConfig();
            $logger = $this->getLogger();

            $logger->log('Url request received' . $requestUri);
            $urlPath = parse_url($requestUri, PHP_URL_PATH);
            if(array_key_exists($urlPath,$this->handlers)) {

                $requestParams = $this->extractQueryParams($requestUri);
                $result = call_user_func($this->handlers[$urlPath], $requestParams , $logger , $appConfig);
            } else {
                $result = [
                    'httpCode' => 404,
                    'result' => [
                        'status' => 'fail',
                        'message' => 'unsupported request'
                    ]
                ];
            }

        } catch (Exception\invalidDataStructureException $e) {
            $result = [
                'httpCode' => 503,
                'result' => [
                    'status' => 'fail',
                    'message' => $e->getMessage()
                ]
            ];
        } catch (Throwable $e) {
            $errMsg = $appConfig instanceof AppConfig
                && false === $appConfig->isHideErrorMsg() ? $e->getMessage() : 'system error';
            try {
                $this->getLogger()->log($e->getMessage());
                $this->logger->log($e->getMessage());
            } catch (Throwable $e) {}

            $result = [
                'httpCode' => 500,
                'result' => [
                    'status' => 'fail',
                    'message' => $errMsg
                ]
            ];
        }
        return $result;
    }


}