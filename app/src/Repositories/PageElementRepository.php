<?php

namespace App\Repositories;

use App\Framework\Repository;
use App\Models\PageElementModel;
use App\Repositories\Interfaces\IPageElementRepository;
use PDO;

class PageElementRepository extends Repository implements IPageElementRepository
{
    /**
     * @return PageElementModel[]
     */
    public function getByPageName(string $pageName): array
    {
        $sql = "SELECT * 
                FROM pageElement 
                WHERE pageName = :pageName
                ORDER BY section ASC, position ASC";

        $stmt = $this->connection->prepare($sql);
        $stmt->execute(['pageName' => $pageName]);

        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return array_map(fn($row) => $this->mapToModel($row), $rows);
    }

    public function mapToModel(array $row): PageElementModel
    {
        return new PageElementModel(
            (int)$row['id'],
            (int)$row['subElementId'],
            $row['type'],
            $row['pageName'],
            (int)$row['section'],
            (int)$row['position'],
        );
    }
    public function getById(int $id): ?PageElementModel
    {
        $sql = "SELECT * FROM pageElement WHERE id = :id";

        $stmt = $this->connection->prepare($sql);
        $stmt->execute(['id' => $id]);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row ? $this->mapToModel($row) : null;
    }
    public function getNextPosition(string $pageName, int $section): int
{
    $sql = "SELECT MAX(position) as maxPos 
            FROM pageElement 
            WHERE pageName = :pageName AND section = :section";

    $stmt = $this->connection->prepare($sql);
    $stmt->execute([
        'pageName' => $pageName,
        'section' => $section
    ]);

    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    return ($result['maxPos'] ?? 0) + 1;
}
public function create(
    int $subId,
    string $type,
    string $pageName,
    int $section,
    int $position
): bool {
    $sql = "INSERT INTO pageElement 
            (subElementId, type, pageName, section, position)
            VALUES (:subId, :type, :pageName, :section, :position)";

    $stmt = $this->connection->prepare($sql);

    return $stmt->execute([
        'subId' => $subId,
        'type' => $type,
        'pageName' => $pageName,
        'section' => $section,
        'position' => $position
    ]);
}
}