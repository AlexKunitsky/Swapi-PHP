<?php

class DB
{

    private static $_instance = null;
    private $_pdo,
        $_query,
        $_error = false,
        $_results,
        $_count = 0;

    private $_host = '127.0.0.1',
        $_db = 'swapi-app',
        $_username = 'root',
        $_password = '';

    // Simple constructor use internal parameters, will be changed to external Config class
    private function __construct()
    {
        try {
            $this->_pdo = new PDO('mysql:host=' . $this->_host . ';dbname=' . $this->_db, $this->_username, $this->_password);
        } catch (PDOException $e) {
            die($e->getMessage());
        }
    }

    // Singleton pattern for connection
    public static function getInstance()
    {

        if (!isset(self::$_instance)) {
            self::$_instance = new DB();
        }
        return self::$_instance;
    }

    // Params will be checked, if every thing is defined and secured from query INJECTION
    public function query($sql, $params = array())
    {
        // reset $_error to false to be sure if many queries in the same time
        $this->_error = false;
        // check if the $sql been prepared
        if ($this->_query = $this->_pdo->prepare($sql)) {
            // bind values to position (like index)
            $position = 1;
            // check parameters
            if (count($params)) {
                foreach ($params as $param) {
                    $this->_query->bindValue($position, $param);
                    $position++;
                }
            }
            // execute query if all params correct
            if ($this->_query->execute()) {
                // fetch all like objects to results and update count to number of rows, otherwise set an error to true
                $this->_results = $this->_query->fetchAll(PDO::FETCH_OBJ);
                $this->_count = $this->_query->rowCount();
            } else {
                $this->_error = true;
            }
        }
        return $this;
    }

    // NOT strictly required, but makes a lot easy and quicker
    // Specific action like SELECT or DELETE, specific table like users, and array of fields
    // Example $user = DB::getInstance()->get('users', array('username', '=', 'alex'));
    public function action($action, $table, $where = array())
    {
        // Check for requirements exists: field, operator, value
        if (count($where) === 3) {
            // list of operators I allow
            $operators = array('=', '>', '<', '>=', '<=');

            // extract data from where array
            $field = $where[0];    // like 'username'
            $operator = $where[1];    // like '='
            $value = $where[2];    // like 'alex'

            // compare operator to array of operators and construct query ( ? to hidden value )
            if (in_array($operator, $operators)) {
                $sql = "{$action} FROM {$table} WHERE {$field} {$operator} ?";
                // if it's not an error send it to query and ? mark will be replaced to the value
                if (!$this->query($sql, array($value))->error()) {
                    return $this;
                }
            }
        }
        return false;
    }

    public function get($table, $where)
    {
        // use returned object action - $this
        return $this->action('SELECT *', $table, $where);
    }

    public function delete($table, $where)
    {
        return $this->action('DELETE', $table, $where);
    }

    public function insert($table, $fields = array())
    {
        // if fields have any data
        if (count($fields)) {
            $keys = array_keys($fields); // mark keys of got array, like 'username'
            $values = null;
            $x = 1;

            // break to pieces a values binding (parameters) like - ?, ?, ?
            foreach ($fields as $field) {
                $values .= '?';
                //$values .= $field;
                if ($x < count($fields)) {
                    $values .= ', ';
                }
                $x++;
            }

            // build query from fields and table to insert to DB
            // example for sql INSERT INTO users (`username`, `password`, `email`, `salt`) VALUES (?, ?, ?)
            $sql = "INSERT INTO {$table} (`" . implode('`, `', $keys) . "`) VALUES ({$values})";

            // if no errors in insert query
            if (!$this->query($sql, $fields)->error()) {
                return true;
            }
        }
        return false;
    }

    public function update($table, $id, $fields)
    {
        // set will be a string of updating data
        $set = '';
        $x = 1;

        // build set for a query from fields array like, 'password' => 'NEWpass', of course bind values
        foreach ($fields as $name => $value) {
            $set .= "{$name} = ?";
            if ($x < count($fields)) {
                $set .= ', ';
            }
            $x++;
        }

        // sql query after build set will be for example ,like : UPDATE users SET password = ?, name = ? WHERE id = 1
        $sql = "UPDATE {$table} SET {$set} WHERE id = {$id}";

        // if no errors in query, like insert method
        if (!$this->query($sql, $fields)->error()) {
            return true;
        }
        return false;
    }

    public function results()
    {
        return $this->_results;
    }

    //return first result from results
    public function first()
    {
        return $this->results()[0];
    }

    public function error()
    {
        return $this->_error;
    }

    public function count()
    {
        return $this->_count;
    }
}