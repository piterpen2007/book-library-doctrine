<?php

namespace EfTech\BookLibrary\Controller;

class GetAuthorsController extends GetAuthorsCollectionController
{
    /**
     * @inheritDoc
     */
    protected function buildHttpCode(array $foundAuthors): int
    {
        return 0 === count($foundAuthors) ? 404 : 200;
    }

    /**
     * @inheritDoc
     */
    protected function buildResult(array $foundAuthors): array
    {
        return 1 === count($foundAuthors)
            ? $this->serializeAuthor(current($foundAuthors))
            : [ 'status' => 'fail', 'message' => 'entity not found'];
    }
}
