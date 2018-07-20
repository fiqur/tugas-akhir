<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Mfeedback extends CI_Model
{
	
	function data_feedback(){
		return $query = $this->db->get('feedback')->result();
	}

    function data_feedback_barang($idBarang){
        $this->db->where('id_barang', $idBarang);
        $this->db->order_by('tanggal', 'desc');
        $query = $this->db->get('feedback');
        return $query->result();
    }

    function tampil_feedback(){
        return $this->db->get('feedback')->num_rows();
    }
}