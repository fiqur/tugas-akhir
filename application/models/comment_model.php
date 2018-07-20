<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Comment_model extends MY_Model
{
	function getAllComment()
    {
        $query = $this->db->get('comment');
        return $query->result_array();
    }

    function updateComment($field, $value, $id)
    {
    	$data['value'] = $value;
    	$this->db->where('comment_id', $id);
    	$this->db->update('comment', $data);
    }

    function getAllComment_Where($field, $value)
    {
        $this->db->where($field, $value);
        $query = $this->db->get('comment');
        return $query->result_array();
    }

}