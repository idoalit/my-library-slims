<?php
/**
 * @Created by          : Waris Agung Widodo (ido.alit@gmail.com)
 * @Date                : 23/05/20 09.34
 * @File name           : DB.php
 */

namespace Klaras\Utils;


use PDO;
use PDOException;

class DB
{
    /**
     * @var PDO
     */
    protected static $pdo = null;
    protected $last_id = 0;
    protected $error = '';
    protected $success = false;
    protected $sql = '';

    public static function getConnection()
    {
        if (is_null(self::$pdo)) self::init();

        $error_level = 0;

        try {
            $error_level = error_reporting($error_level);
            self::$pdo->query("SELECT 1");
        } catch (PDOException $e) {
            self::init();
        }

        error_reporting($error_level);

        return self::$pdo;
    }

    public static function query($sql)
    {
        $connection = self::getConnection();
        return $connection->query($sql);
    }

    public static function insert($table, $data)
    {
        $self = new static();
        $columns = implode(', ', array_keys($data));
        $value = '';
        foreach (array_keys($data) as $key) {
            $value .= ':'.$key.', ';
        }
        $value = substr(trim($value), 0, -1);
        $sql = "INSERT INTO {$table} ({$columns}) VALUES ({$value})";
        $self->setSql($sql);
        $conn = self::getConnection();
        $stmt = $conn->prepare($sql);
        try {
            $stmt->execute($data);
            $self->setSuccess(true);
            $self->setLastId($conn->lastInsertId());
        } catch (PDOException $exception) {
            $self->setSuccess(false);
            $self->setError($exception->getMessage());
        }
        return $self;
    }

    public static function update($table, $data, $criteria) {
        $self = new static();
        $set = '';
        foreach ($data as $key => $val) {
            $set .= $key.'=:'.$key.', ';
        }
        $set = substr(trim($set), 0, -1);
        $sql = "UPDATE {$table} SET {$set} WHERE {$criteria}";
        $self->setSql($sql);
        $stmt = self::getConnection()->prepare($sql);
        try {
            $stmt->execute($data);
            $self->setSuccess(true);
        } catch (PDOException $exception) {
            $self->setSuccess(false);
            $self->setError($exception->getMessage());
        }
        return $self;
    }

    public static function insertOrUpdate($table, $data, $criteria) {
        // try insert
        $insert = self::insert($table, $data);
        if (!$insert->isSuccess()) {
            return self::update($table, $data, $criteria);
        }
        return $insert;
    }

    public static function delete($table, $criteria) {
        $self = new static();
        $sql = "DELETE FROM {$table} WHERE {$criteria}";
        $self->setSql($sql);
        $stmt = self::getConnection()->prepare($sql);
        try {
            $stmt->execute();
            $self->setSuccess(true);
        } catch (PDOException $exception) {
            $self->setSuccess(false);
            $self->setError($exception->getMessage());
        }
        return $self;
    }

    protected static function init()
    {
        try {
            $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME;
            self::$pdo = new PDO($dsn, DB_USERNAME, DB_PASSWORD);
            self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die($e->getMessage());
        }
    }

    /**
     * @return int
     */
    public function getLastId(): int
    {
        return $this->last_id;
    }

    /**
     * @param int $last_id
     */
    protected function setLastId(int $last_id)
    {
        $this->last_id = $last_id;
    }

    /**
     * @return string
     */
    public function getError(): string
    {
        return $this->error;
    }

    /**
     * @param string $error
     */
    protected function setError(string $error)
    {
        $this->error = $error;
    }

    /**
     * @return bool
     */
    public function isSuccess(): bool
    {
        return $this->success;
    }

    /**
     * @param bool $success
     */
    protected function setSuccess(bool $success)
    {
        $this->success = $success;
    }

    /**
     * @return string
     */
    public function getSql(): string
    {
        return $this->sql;
    }

    /**
     * @param string $sql
     * @return DB
     */
    public function setSql(string $sql): DB
    {
        $this->sql = $sql;
        return $this;
    }
}