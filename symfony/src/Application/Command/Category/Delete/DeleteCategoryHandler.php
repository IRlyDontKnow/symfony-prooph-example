<?php

declare(strict_types=1);

namespace App\Application\Command\Category\Delete;

use App\Application\Command\Book\Delete\DeleteBookCommand;
use App\Domain\Book\BookRepository;
use App\Domain\Category\Category;
use App\Domain\Category\CategoryStore;
use App\Domain\Common\ValueObject\AggregateRootId;
use App\Infrastructure\Book\Query\Projections\BookView;
use App\Infrastructure\Common\CommandHandler\CommandBus;
use App\Infrastructure\Common\CommandHandler\CommandHandlerInterface;

class DeleteCategoryHandler implements CommandHandlerInterface
{
    /**
     * @var CategoryStore
     */
    private $categoryStoreRepository;
    /**
     * @var BookRepository
     */
    private $bookRepository;
    /**
     * @var CommandBus
     */
    private $commandBus;

    public function __construct(CategoryStore $categoryStoreRepository, BookRepository $bookRepository, CommandBus $commandBus)
    {
        $this->categoryStoreRepository = $categoryStoreRepository;
        $this->bookRepository = $bookRepository;
        $this->commandBus = $commandBus;
    }

    public function __invoke(DeleteCategoryCommand $command)
    {
        $books = $this->bookRepository->getAllByAuthorId($command->getId());
        /** @var BookView $book */
        foreach ($books as $book) {
            $command = new DeleteBookCommand($book->getId());
            $this->commandBus->handle($command);
        }
        /** @var Category $category */
        $category = $this->categoryStoreRepository->get(AggregateRootId::fromString($command->getId()));
        $category->delete();
        $this->categoryStoreRepository->save($category);
    }
}
