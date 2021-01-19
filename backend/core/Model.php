<?php

namespace Core;

use JsonException;
use PDO;
use RuntimeException;

abstract class Model
{
    private static PDO $connection;
    private array $content = [];
    protected ?string $table = NULL;
    protected ?string $idField = NULL;

    public function __construct()
    {
        if ($this->table === NULL) {
            $this->table = strtolower(get_class($this));
        }
        if ($this->idField === NULL) {
            $this->idField = 'id';
        }
    }

    public function __set($parameter, $value)
    {
        $this->content[$parameter] = $value;
    }

    public function __get($parameter)
    {
        return $this->content[$parameter];
    }

    public function __isset($parameter): bool
    {
        return isset($this->content[$parameter]);
    }

    public function __unset($parameter): bool
    {
        if (isset($parameter)) {
            unset($this->content[$parameter]);
            return true;
        }
        return false;
    }

    private function __clone()
    {
        if (isset($this->content[$this->idField])) {
            unset($this->content[$this->idField]);
        }
    }

    public function toArray(): array
    {
        return $this->content;
    }

    public function fromArray(array $array): void
    {
        $this->content = $array;
    }

    /**
     * @return false|string
     * @throws JsonException
     */
    public function toJson()
    {
        return json_encode($this->content, JSON_THROW_ON_ERROR);
    }

    /**
     * @param string $json
     * @throws JsonException
     */
    public function fromJson(string $json): void
    {
        $this->content = json_decode($json, true, 512, JSON_THROW_ON_ERROR);
    }

    private function format($value): string
    {
        if (is_string($value) && !empty($value)) {
            return "'" . addslashes($value) . "'";
        }

        if (is_bool($value)) {
            return $value ? 'TRUE' : 'FALSE';
        }

        if ($value !== '') {
            return $value;
        }

        return "NULL";
    }

    private function convertContent(): array
    {
        $newContent = array();
        foreach ($this->content as $key => $value) {
            if (is_scalar($value)) {
                $newContent[$key] = $this->format($value);
            }
        }
        return $newContent;
    }

    public function save()
    {
        $newContent = $this->convertContent();

        if (isset($this->content[$this->idField])) {
            $sets = array();
            foreach ($newContent as $key => $value) {
                if ($key === $this->idField) {
                    continue;
                }
                $sets[] = "{$key} = {$value}";
            }
            $sql = "UPDATE {$this->table} SET " . implode(', ', $sets) . " WHERE {$this->idField} = {$this->content[$this->idField]};";
        } else {
            $sql = "INSERT INTO {$this->table} (" . implode(', ', array_keys($newContent)) . ') VALUES (' . implode(',', array_values($newContent)) . ');';
        }
        if (self::$connection) {
            $db = self::$connection->prepare($sql);
            $db->execute($newContent);
            $lastId = self::$connection->lastInsertId();
            return self::find($lastId);
        }

        throw new RuntimeException("Não há conexão com Banco de dados!");
    }

    /**
     * @param $id
     * @return array
     */
    public static function find($id): array
    {
        $class = static::class;
        $idField = (new $class())->idField;
        $table = (new $class())->table;

        $sql = 'SELECT * FROM ' . (is_null($table) ? strtolower($class) : $table);
        $sql .= ' WHERE ' . (is_null($idField) ? 'id' : $idField);
        $sql .= " = {$id} ;";

        if (self::$connection) {
            $result = self::$connection->query($sql);
            if ($result) {
                $res = $result->fetch(PDO::FETCH_ASSOC);
                return $res !== false ? $res : [];
            }
        }

        throw new RuntimeException("Não há conexão com Banco de dados!");
    }

    public function delete()
    {
        if (isset($this->content[$this->idField])) {
            $sql = "DELETE FROM {$this->table} WHERE {$this->idField} = {$this->content[$this->idField]};";

            if (self::$connection) {
                return self::$connection->exec($sql);
            }

            throw new RuntimeException("Não há conexão com Banco de dados!");
        }
        throw new RuntimeException("Id não especificado");
    }

    public static function all(string $filter = '', int $limit = 0, int $offset = 0): array
    {
        $class = static::class;
        $table = (new $class())->table;
        $sql = 'SELECT * FROM ' . (is_null($table) ? strtolower($class) : $table);
        $sql .= ($filter !== '') ? " WHERE {$filter}" : "";
        $sql .= ($limit > 0) ? " LIMIT {$limit}" : "";
        $sql .= ($offset > 0) ? " OFFSET {$offset}" : "";
        $sql .= ';';
        if (self::$connection) {
            $result = self::$connection->query($sql);
            return $result->fetchAll(PDO::FETCH_ASSOC);
        }

        throw new RuntimeException("Não há conexão com Banco de dados!");
    }

    public static function count(string $fieldName = '*', string $filter = ''): int
    {
        $class = static::class;
        $table = (new $class())->table;
        $sql = "SELECT count($fieldName) as t FROM " . (is_null($table) ? strtolower($class) : $table);
        $sql .= ($filter !== '') ? " WHERE {$filter}" : "";
        $sql .= ';';
        if (self::$connection) {
            $q = self::$connection->query($sql);
            $a = $q->fetch(PDO::FETCH_ASSOC);
            return (int)$a['t'];
        }

        throw new RuntimeException("Não há conexão com Banco de dados!");
    }

    public static function findFirst(string $filter = '')
    {
        return self::all($filter, 1);
    }

    public static function setConnection(PDO $connection): void
    {
        self::$connection = $connection;
    }
}
