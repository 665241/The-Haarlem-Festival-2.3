<?php

namespace App\Services\Interfaces;

use App\Models\PageElementModel; 

interface IPageElementService
{
    public function getByPageName(string $pageName): array;

    public function getById(int $id): ?PageElementModel;
    public function createElement(string $type,int $section,string $pageName,array $data): bool;
}