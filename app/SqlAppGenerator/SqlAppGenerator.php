<?php

namespace app\SqlAppGenerator;

use app\InvalidSqlGeneratorPathException\InvalidSqlGeneratorPathException;
use SplFileObject;

class SqlAppGenerator
{
    public static function create(string $file_path): array
    {
        if(!file_exists($file_path)) {
            throw new InvalidSqlGeneratorPathException('Данный csv файл не найден в указанной дериктории');
        }

        $data = new SplFileObject($file_path);

        $table = str_replace('.csv', '', $data->getFilename());
        $sql = '';

        foreach ($data as $item) {
            $item = trim($item);

            if(!$item) {
                continue;
            }

            $item = explode(',', $item);

            if($data->key() === 0) {
                $item = implode(',', array_map(function($title) {
                    return "`$title`";
                }, $item));
                $sql .= "INSERT INTO `$table` ($item) VALUES ";
            } else {
                $item = implode(',', array_map(function($title) {
                    return "'$title'";
                }, $item));
                $sql .= "($item),";
            }
        }

        return ['sql' => rtrim($sql, ',') . ';', 'table' => $table];
    }

    public static function createSqlCollection(array $csv_files, string $output_dir): void
    {
        $sql = '';

        foreach ($csv_files as $path) {
            $data = self::create($path);

            $fp = fopen("$output_dir/{$data['table']}.sql", 'w');
            fwrite($fp, $data['sql']);
            fclose($fp);

            $sql .= ($data['sql'] . "\r\n");
        }

        $fp = fopen("$output_dir/all.sql", 'w');
        fwrite($fp, $sql);
        fclose($fp);
    }
}
