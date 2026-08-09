<?php 
declare(strict_types=1);

namespace Core;
use PDO;

class Database 
{
    protected PDO $db;
    protected string $query = '';
    protected string $fromClause = '';
    protected string $selectClause = '';
    protected string $whereClause = '';
    protected string $orderByClause = '';
    protected string $limitClause = '';

    protected string $table;
    protected array $bindings = [];
    protected array $whereBindings = [];

    public function __construct()
    {
        $this->db = $this->connectMysql();
    }

    public function connectMysql()
    {
        $db_config = config('database.connections.mysql');
        $dns = $db_config['driver'] . ':host='.$db_config['host']. ((!empty($db_config['port']))? (';port='.$db_config['port']) : '')
        . ';dbname=' . $db_config['database'];

        return new PDO($dns,$db_config['username'], $db_config['password']);
    }

    public function table(string $table): self
    {
        $this->table = $table;
        return $this;
    }

    public function from(?string $table = null): self
    {
        $this->fromClause = ' FROM '. ($table ?? $this->table);
        return $this;
    }

    public function select(string|array $selection): self
    {
        if(is_array($selection)) {
            $this->selectClause = 'SELECT ' . implode(',', $selection) .' ';
        } else if(empty($selection)) {
            $this->selectClause = 'SELECT * ';
        } else {
            $this->selectClause = 'SELECT ' . "$selection" .' ';
        }

        return $this;
    }

    public function where(string $column, string $operator, mixed $val,string $andOr='AND') : self
    {
        if(str_contains($this->whereClause,'WHERE')) {
            $this->whereClause .= ' '.$andOr.' ';
        } else {
            $this->whereClause = ' WHERE ';
        }
        $this->whereClause .= $column . ' ' . $operator . ' ?';
        $this->whereBindings[] = $val;
        return $this;
    }


    public function whereIn(string $column, array $values,string $andOr='AND'): self
    {
        if(str_contains($this->whereClause,'WHERE')) {
            $this->whereClause .= " {$andOr} {$column}  IN (";
        } else {
            $this->whereClause = ' WHERE '.$column.' IN (';
        }

        $this->whereClause .= implode(',', array_fill(0, count($values), '?')). ')';

        $this->whereBindings = array_merge($this->whereBindings, $values);

        return $this;
    }

    public function get()
    {
        $this->buildQuery();
        if(empty($this->bindings)) {
                
            $stmt = $this->db->query($this->query);
            $row = $stmt->fetchAll(PDO::FETCH_OBJ);
            
        } else {
            $stmt = $this->db->Prepare($this->query);
            $stmt->execute($this->bindings);
            $row = $stmt->fetchAll(PDO::FETCH_OBJ);
            
        }

        $this->reset();
        return $row;
    }

    public function first()
    {
        $this->buildQuery();
        if(empty($this->bindings)) {
                
            $stmt = $this->db->query($this->query);
            $row = $stmt->fetch(PDO::FETCH_OBJ);
            
        } else {
            $stmt = $this->db->Prepare($this->query);
            $stmt->execute($this->bindings);
            $row = $stmt->fetch(PDO::FETCH_OBJ);
            
        }

        $this->reset();
        return $row;
    }

    public function insert(array $data): bool
    {
        $this->buildQuery('INSERT', $data);
        $stmt = $this->db->prepare($this->query);
        $result = $stmt->execute($this->bindings);
        $this->reset();
        return $result;
    }

    public function update(array $data): bool
    {
        $this->buildQuery('UPDATE', $data);
        $stmt = $this->db->prepare($this->query);
        $result = $stmt->execute($this->bindings);
        $this->reset();
        return $result;

    }

    public function orderBy(string $column, string $direction = 'ASC'): self
    {
        $this->orderByClause .= " ORDER BY $column";
        if(in_array(strtoupper($direction), ['ASC', 'DESC'])) {
            $this->orderByClause .= " {$direction}";
        } else {
            $this->orderByClause .= " ASC";
        }
        return $this;
    }

    public function limit(int $limit, int $offset = 0): self
    {
        $this->limitClause .= ' LIMIT '.$limit . ' OFFSET ' . $offset;
        return $this;
    }

    public function delete(): bool
    {
        $this->buildQuery('DELETE');
        $stmt = $this->db->prepare($this->query);
        $result = $stmt->execute($this->bindings);
        $this->reset();
        return $result;
    }

    private function reset(): void
    {
        $this->query = '';
        $this->selectClause = '';
        $this->fromClause = '';
        $this->whereClause = '';
        $this->orderByClause = '';
        $this->limitClause = '';

        $this->bindings = [];
        $this->whereBindings = [];
    }

    private function buildQuery(string $type = 'SELECT',array $data = []): void
    {
        if($type === 'SELECT') {
            $this->query = $this->selectClause . $this->fromClause . $this->whereClause . $this->orderByClause . $this->limitClause;
        } else if($type === 'INSERT') {
            $columns = implode(',', array_keys($data));
            $placeholders = implode(',', array_fill(0, count($data), '?'));
            $this->bindings = array_values($data);
            $this->query = "INSERT INTO {$this->table} ({$columns}) VALUES ({$placeholders})";
        }else if($type === 'UPDATE') {
            $setClause = implode(',', array_map(fn($column) => "{$column} = ?", array_keys($data)));
            $this->bindings = array_values($data);
            $this->query = "UPDATE {$this->table} SET {$setClause}" . $this->whereClause;
        } else if($type === 'DELETE') {
            $this->query = "DELETE FROM {$this->table}" . $this->whereClause;
        }
        $this->bindings = array_merge($this->bindings,$this->whereBindings);
    }

}