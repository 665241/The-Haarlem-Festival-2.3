<?php

namespace App\Repositories;

use App\Framework\Repository;
use App\Models\TextModel;
use App\Repositories\Interfaces\ITextRepository;
use PDO;

class TextRepository extends Repository implements ITextRepository
{
    public function getById(int $id): ?TextModel
    {
        $sql = "SELECT * FROM text WHERE id = :id";

        $stmt = $this->connection->prepare($sql);
        $stmt->execute(['id' => $id]);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row ? $this->mapToModel($row) : null;
    }

    private function mapToModel(array $row): TextModel
    {
        return new TextModel(
            (int)$row['id'],
            $row['content'] ?? ''
        );
    }
    public function saveTextChanges($id, $newText){
        $sql = "UPDATE text SET content = :content WHERE id = :id";
        return $this->connection->prepare($sql)->execute([
            'id'   => $id,
            'content' => $newText
        ]);
    }
    public function create(string $content): int
{
    $sql = "INSERT INTO text (content) VALUES (:content)";
    $stmt = $this->connection->prepare($sql);
    $stmt->execute(['content' => $content]);

    return (int)$this->connection->lastInsertId();
}
}