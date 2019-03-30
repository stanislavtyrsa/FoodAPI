<?php


class PDOProvider
{
    private static $_instance = null;
    /**
     * @var PDO
     */
    private $pdo = null;

    private $DB_HOST = 'localhost';
    private $DB_NAME = 'food';
    private $DB_USER = 'coma';
    private $DB_PASS = 'coma';
    /**
     * PDOProvider constructor.
     */
    private function __construct()
    {
    }

    /**
     * @return PDOProvider|null
     */
    public static function getInstance()
    {
        if (self::$_instance !== null) {
            return self::$_instance;
        }
        return new self;
    }

    private function runPDO()
    {
        if ($this->pdo === null) {
            $this->pdo = new PDO("pgsql:host={$this->DB_HOST};port=5432;dbname={$this->DB_NAME}", $this->DB_USER, $this->DB_PASS);
            $this->pdo->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING );
        }
        return $this->pdo;

    }

    private function suspendPDO()
    {
        $this->pdo = null;
    }

    /**
     * @param PDO $pdo
     * @param string $query
     * @return array
     * @throws Exception
     */
    private function executeQuery(PDO $pdo, string $query)
    {
        $stmt = $pdo->prepare($query);
        $stmt->execute();
        if ($stmt->errorCode() !== '00000') {
            throw new Exception($stmt->errorInfo()[2]);
        }
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    /**
     * @param string $dbName
     * @param array $data
     * @throws Exception
     */
    public function createQuery(string $dbName, array &$data)
    {
        $pdo = $this->runPDO();
        /** @var array $record */
        foreach ($data as $record) {
            $keys = implode(',', array_keys($record));
            $tmp = array_values($record);
            array_walk($tmp, function (&$item, $key){
                if (is_string($item)) {
                    $item = '\'' . $item . '\'';
                }
            });
            $values = implode(',', $tmp);
            $query = "INSERT INTO {$dbName} ({$keys}) VALUES ({$values})";
            $this->executeQuery($pdo,$query);
        }
        $this->suspendPDO();
    }

    /**
     * @param string $dbName
     * @param array $data
     * @param string|null $criteriaField
     * @param string|null $criterialValue
     * @return array|null
     * @throws Exception
     */
    public function selectQuery(string $dbName, array &$data = null, string $criteriaField = null, string $criterialValue = null): ?array
    {
        $pdo = $this->runPDO();
        $fields = null;
        if ($data === null) {
            $fields = '*';
        } else {
            $fields = implode('/', $data);
        }
        $query = "SELECT {$fields} from {$dbName}";
        if ($criteriaField !== null && $criterialValue !== null) {
            $query .= " WHERE {$criteriaField} = {$criterialValue}";
        }
        $result = $this->executeQuery($pdo, $query);
        $this->suspendPDO();
        return $result;
    }

    /**
     * @param string $dbName
     * @param array $data
     * @throws Exception
     */
    public function updateQuery(string $dbName, array &$data)
    {
        $pdo = $this->runPDO();
        /** @var array $record */
        foreach ($data as $record) {
            $id = $record['id'];
            unset($record['id']);
            $keys = array_keys($record);
            $values = array_values($record);
            $query = "UPDATE {$dbName} SET ";
            $keyVal = array_map("self::updateFormat", $keys, $values);
            $query .= implode(',', $keyVal);
            $query .= " WHERE id = {$id}";
            $this->executeQuery($pdo,$query);
        }
        $this->suspendPDO();
    }

    private static function updateFormat($key,$value)
    {
        if (is_string($value)) {
            $value = '\'' . $value . '\'';
        }
        return ("$key = $value");
    }
}