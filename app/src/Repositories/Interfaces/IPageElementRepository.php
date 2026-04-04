<?php

namespace App\Repositories\Interfaces;

use App\Models\PageElementModel;

interface IPageElementRepository
{
    public function getByPageName(string $pageName): array;

    public function mapToModel(array $row): PageElementModel;
    public function getById(int $id): ?PageElementModel;
    public function getNextPosition(string $pageName, int $section): int;
    public function create(int $subId,string $type,string $pageName,int $section,int $position): bool;
}