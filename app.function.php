<?php

/**
 * @param string $sourceName - путь до файла
 * @return array - вывод содержимого файла в виде массива
 */
function loadData (string $sourceName):array
{
    $pathToFile = __DIR__ . "/{$sourceName}.json";
    $content = file_get_contents($pathToFile);
    return json_decode($content, true);
}


/**
 * @param array $data - данные, которые хотим отобразить
 * @param int $httpCode - http code
 */
function render(array $data, int $httpCode)
{
    header('Content-Type: application/json');
    http_response_code($httpCode);
    echo json_encode($data);
    exit();
}

/**
 * Функция валидации
 *
 * @param array $validateParameters - валидируемые параметры, ключ имя параметра, а значение это текст сообщения о ошибке
 * @param array $params - все множество параметров
 * @return array - сообщение о ошибках
 */
function paramTypeValidation(array $validateParameters, array $params):?array
{
    $result = null;
    foreach ($validateParameters as $paramName => $errMsg) {
        if (array_key_exists($paramName, $params) && false === is_string($params[$paramName])) {
            $result = [
                'httpCode' => '500',
                'result' => [
                    'status' => 'fail',
                    'message' => $errMsg
                ]
            ];
            break;
        }
    }
    return $result;
}

/**
 * Логирует текстовое сообщение
 * @param string $errMsg - сообщение о ошибке
 */
function loggerInFile ( string $errMsg):void
{
    file_put_contents(__DIR__ . '/app.log',"{$errMsg}\n", FILE_APPEND);
}

/** Функция реализации веб приложения
 * @param $handler array - массив сопоставляющий url parh с функциями реализующими логику обработки запроса
 * @param $requestUri string - URI запроса
 * @param $request array - параметры которые передаёт пользователь
 * @param $logger callable - параметр инкапсулирующий логгирование
 * @return array
 */
function app (array $handler,string $requestUri ,array $request,callable $logger):array
{
    $logger('Url request received' . $requestUri);
    $urlPath=  parse_url($requestUri, PHP_URL_PATH);

    if (array_key_exists($urlPath, $handler)) {
        $result = $handler[$urlPath]($request,$logger);
    } else {
        $result = [
            'httpCode' => 404,
            'result' => [
                'status' => 'fail',
                'message' => 'unsupported request'
            ]
        ];
        $logger($result['result']['message']);
    }
    return $result;
}