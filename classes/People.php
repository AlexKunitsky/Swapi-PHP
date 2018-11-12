<?php

class People
{

    private $_db,
            $_data;

    // constructor get instance of db 
    public function __construct()
    {
        $this->_db = DB::getInstance();
    }

    // insert new to DB
    public function create($fields = array())
    {
        if (!$this->_db->insert('peoples', $fields)) {
            // if it doesn't work throw exception
            throw new Exception('There was a problem creating.');
        }
    }

    public function update($fields = array(), $id = null)
    {
        if (!$this->_db->update('peoples', $id, $fields)) {
            throw new Exception('There was a problem updating.');
        }
    }

    public function delete($id = null)
    {
        if (!$this->_db->delete('peoples', array('id', '=', $id))) {
            throw new Exception('There was a problem deleting a theory.');
        }
    }

    public function find($people = null)
    {
        // check if theory is just numbers, set as id or not. Grab data from DB to compare with input
        if ($theory) {
            $field = (is_numeric($theory)) ? 'id' : 'name';

            $data = $this->_db->get('theories', array($field, '=', $theory));

            // return true and show just first result, _data will contains all data of the theory
            if ($data->count()) {
                $this->_data = $data->first();
                return true;
            }
        }
        return false;
    }
}