<?php

declare(strict_types=1);

namespace App\Infrastructure\Category\Projection;

use App\Infrastructure\Category\Query\Projections\CategoryView;
use App\Infrastructure\Category\Query\Repository\MysqlCategoryRepository;
use Prooph\EventStore\Projection\AbstractReadModel;

class CategoryReadModel extends AbstractReadModel
{
    /**
     * @var MysqlCategoryRepository
     */
    private $categoryRepository;

    public function __construct(MysqlCategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    public function init(): void
    {
        return;
    }

    public function isInitialized(): bool
    {
        return true;
    }

    public function reset(): void
    {
        return;
    }

    public function delete(): void
    {
        return;
    }

    protected function insert(array $categoryView)
    {
        $categoryView = new CategoryView(
            $categoryView['id'],
            $categoryView['name']
        );
        $this->categoryRepository->add($categoryView);
    }
}