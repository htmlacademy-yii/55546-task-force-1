<?php

namespace app\SqlAppGenerator;

use app\InvalidSqlGeneratorPathException\InvalidSqlGeneratorPathException;
use SplFileObject;

class SqlAppGenerator
{
    public static function create(string $filePath): array
    {
        if (!file_exists($filePath)) {
            throw new InvalidSqlGeneratorPathException('Данный csv файл не найден в указанной дериктории');
        }

        $data = new SplFileObject($filePath);

        $table = str_replace('.csv', '', $data->getFilename());
        $sql = '';

        foreach ($data as $item) {
            $item = trim($item);

            if (!$item) {
                continue;
            }

            $item = implode(',', array_map(function ($cell) use ($data) {
                return $data->key() === 0 ? "`$cell`" : "'$cell'";
            }, explode(',', $item)));

            $sql .= $data->key() === 0 ? "INSERT INTO `$table` ($item) VALUES "
                : "($item),";
        }

        return ['sql' => rtrim($sql, ',').';', 'table' => $table];
    }

    public static function createSqlCollection(
        array $csvFiles,
        string $outputDir
    ): void {
        $sql = '';

        foreach ($csvFiles as $path) {
            $data = self::create($path);
            file_put_contents("$outputDir/{$data['table']}.sql", $data);
            $sql .= ($data['sql']."\r\n");
        }

        file_put_contents("$outputDir/all.sql", $sql);
    }
}
