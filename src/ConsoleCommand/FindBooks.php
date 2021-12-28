<?php

namespace EfTech\BookLibrary\ConsoleCommand;

use EfTech\BookLibrary\Infrastructure\Console\CommandInterface;
use EfTech\BookLibrary\Infrastructure\Console\Output\OutputInterface;

class FindBooks implements CommandInterface
{
    /** Компонент отвечающий ща вывод данных в консоль
     * @var OutputInterface
     */
    private OutputInterface $output;

    /**
     * @param OutputInterface $output
     */
    public function __construct(OutputInterface $output)
    {
        $this->output = $output;
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
     */
    public function __invoke(array $params): void
    {
        $this->output->print('FindBooks');
    }

}