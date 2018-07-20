<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Model Class
 *
 * Loads common used model
 *
 * @package		CodeIgniter
 * @subpackage          Libraries
 * @category            Model
 */
class MY_Model extends CI_Model 
{
    
    /**
     * Constructor. initialize parent
     */
    public function __construct()
    {
        parent::__construct();
    }
    
    
    /**
     * Get data from a table by id
     * @param string $table The table name
     * @param string $table_id Table id or table identifier
     * @param string $id identifier
     * @param integer $limit The limit value
     * @param integer $offset The offset value
     * @return array return query data result
     */
    function get_data_by_id($table, $table_id, $id, $limit=NULL, $offset=NULL) 
    {
        $query = $this->db->get_where($table, array($table_id => $id), $limit, $offset);
        return $query->result_array();
    }
    
    /**
     * Get all data from a table
     * @param string $table The table name
     * @return array return query result
     */
    function get_all_data($table)
    {
        $query = $this->db->get($table);
        $data = $query->result_array();

        return $data;
    }
    
    /**
     * Get spesific data from a table
     * @param string $table The table name
     * @param string $field The table field
     * @param string $id The spesific primary key of table
     * @return array return query result by id in field
     */
    function get_single($table, $field, $id)
    {
        $query = $this->db->get_where($table, array($field => $id));
        $data = $query->row_array();

        return $data;
    }

    /**
     * Insert data to a table
     * @param string $table The table name
     * @param array $data The data will be inserted
     * @return string The insert ID number when performing database inserts
     */
    function insert($table, $data)
    {
        $this->db->insert($table, $data);
        return $this->db->insert_id();        
    }

    /**
     * Update data to a table
     * @param string $table The table name
     * @param string $field The table field 
     * @param string $id The spesific primary key of table
     * @param array $data The data will be inserted e.g. ($field => $id)
     * @return integer Displays the number of affected rows from update query
     */
    function update($table, $field, $id, $data)
    {
        $this->db->where($field, $id);
        $this->db->update($table, $data);
        return $this->db->affected_rows();
    }

    /**
     * Delete spesific data from a table
     * @param string $table The table name
     * @param string $field The table field
     * @param string $id The spesific primary key of table
     * @return integer Displays the number of affected rows from delete query
     */
    function delete($table, $field, $id)
    {
        $this->db->where($field, $id);
        $this->db->delete($table);

        return $this->db->affected_rows();
    }
	
}