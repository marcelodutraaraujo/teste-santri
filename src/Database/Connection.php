<?php
declare(strict_types=1);

namespace App\Database;

use PDO;

class Connection
{
    public static function make(): PDO
    {
        $host = getenv('DB_HOST');
        $db   = getenv('DB_NAME');
        $user = getenv('DB_USER');
        $pass = getenv('DB_PASS');

        return new PDO(
            "mysql:host={$host};dbname={$db};charset=utf8mb4",
            $user,
            $pass,
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
            ]
        );
    }
}
