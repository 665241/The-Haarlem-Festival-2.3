<?php

namespace App\Repositories;

use App\Framework\Repository;
use App\Models\ButtonModel;
use App\Repositories\Interfaces\IButtonRepository;
use PDO;

class ButtonRepository extends Repository implements IButtonRepository
{
    public function getById(int $id): ?ButtonModel
    {
        $sql = "SELECT * FROM button WHERE id = :id";

        $stmt = $this->connection->prepare($sql);
        $stmt->execute(['id' => $id]);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row ? $this->mapToModel($row) : null;
    }

    public function mapToModel(array $row): ButtonModel
    {
        return new ButtonModel(
            (int)$row['id'],
            $row['path'] ?? '',
            $row['text'] ?? ''
        );
    }
    public function saveButtonTextChanges($id, $newText){
        $sql = "UPDATE button SET text = :text WHERE id = :id";
        return $this->connection->prepare($sql)->execute([
            'id'   => $id,
            'text' => $newText
        ]);
    }
}