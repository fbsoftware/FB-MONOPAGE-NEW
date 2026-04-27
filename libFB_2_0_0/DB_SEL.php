<?php 
class DB_SEL {

    private static ?PDO $pdo = null;

    private static function connect(): PDO {
        if (self::$pdo === null) {

            $dsn = "mysql:host=" . DB::$host .
                   ";dbname=" . DB::$db .
                   ";charset=utf8mb4";

            self::$pdo = new PDO(
                $dsn,
                DB::$user,
                DB::$pw,
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
                ]
            );
        }
        return self::$pdo;
    }

    public static function select(string $sql, array $params = []): array {
        $stmt = self::connect()->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }
}

?>