<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Mbarang extends CI_Model
{

	function data_barang(){
		return $query = $this->db->get('barang')->result();		
	}

    function tampil_barang(){
        return $this->db->get('barang')->num_rows();
    }
}