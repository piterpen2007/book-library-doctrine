<?php

namespace EfTech\BookLibrary\Controller;
/**
 * Получение информации о одной книге
 */
class GetBooksController extends GetBooksCollectionController
{
    /**
     * @inheritDoc
     */
    protected function buildHttpCode(array $foundTextDocument): int
    {
        return 0 === count($foundTextDocument) ? 404 : 200;
    }

    /**
     * @inheritDoc
     */
    protected function buildResult(array $foundTextDocument)
    {
        return 1 === count($foundTextDocument)
            ? current($foundTextDocument)
            : ['status' => 'fail', 'message' => 'entity not found'];

    }

}