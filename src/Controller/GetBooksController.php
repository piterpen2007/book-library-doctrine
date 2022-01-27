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
     * @param array $foundTextDocuments
     * @inheritDoc
     */
    protected function buildResult(array $foundTextDocuments)
    {
        return 1 === count($foundTextDocuments)
            ? $this->serializeTextDocument(current($foundTextDocuments))
            : [ 'status' => 'fail', 'message' => 'entity not found'];
    }
}
