<?php

use PDO;
use PDOException;
class Database
{
    private static ?Database $instance = null;
    private PDO $connection;
    private string $host = 'sql100.infinityfree.com';
    private string $dbName = 'if0_40863795_XXX';
    private string $user = 'if0_40863795';
    private string $pass = 'kerymy200905';
    private string $charset = "utf8mb4";
    private function __construct()
    {
        $dsn = "mysql:host={$this->host};dbname={$this->dbName};charset={$this->charset}";
        try {
            $this->connection = new PDO($dsn, $this->user, $this->pass, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            ]);
        } catch (PDOException $e) {
            die($e->getMessage());
        }
    }
    public static function getInstance(): Database
    {
        if (self::$instance === null) {
            self::$instance = new Database();
        }
        return self::$instance;
    }
    public function getConnection(): PDO
    {
        return $this->connection;
    }
}