<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Document_model extends MY_Model
{

    private $katadasar;
    private $stoplist;
    private $artikel;
            
    function __construct() 
    {
        parent::__construct();
        $this->katadasar = 'word_dictionary';
        $this->stoplist = 'stoplist';
    }
    
    function cekKataDasar($kata) 
    {
        $query = $this->get_single($this->katadasar, 'katadasar', $kata);
        if ($query) 
        {
            return TRUE;
        }
        else
        {
            return FALSE;
        }
    }
    
    function cekStopList($kata) 
    {
        $query = $this->get_single($this->stoplist, 'stoplist', $kata);
        if ($query) 
        {
            return FALSE;
        }
        else
        {
            return TRUE;
        }
    }

    function update_content($content, $id)
    {
        $data = array (
            'content' => $content
            );
        $this->db->where('document_id', $id);
        $this->db->update('document', $data);
    }

    function add_kataPenting($content, $id)
    {
        $data = array (
            'kataPenting' => $content
            );
        $this->db->where('document_id', $id);
        $this->db->update('document', $data);
    }
    
    function select_db($columns, $table, $field = NULL, $value = NULL)
    {
        $this->db->select($columns);
        //jika pakai where
        if(!is_null($field))
            $this->db->where($field, $value);

        $query = $this->db->get($table);
        return $query->result_array();
    }
    function insert_aggregationWords($table, $data)
    {
        foreach($data as $x)
        {
            if($x != '')
            {
                $field = array("aggregation_word" => $x);
                echo $x.'<br/>';
                $this->db->insert($table, $field);
            }
        }
    }
    function insert_aggregationValue($data)
    {
        var_dump($data);
        $this->db->insert_batch('aggregation_value', $data);
    }

    function sumValue($document_id)
    {
        $this->db->select_sum('value');
        $this->db->where('document_id', $document_id);
        $query = $this->db->get('comment');
        return $query->row('value');
    }

    function sumPositiveValue($document_id)
    {
        $this->db->select_sum('value');
        $this->db->where('document_id', $document_id);
        $this->db->where('value >', 0);
        $query = $this->db->get('comment');
        return $query->row('value');
    }

    function sumNegativeValue($document_id)
    {
        $this->db->select_sum('value');
        $this->db->where('document_id', $document_id);
        $this->db->where('value <', 0);
        $query = $this->db->get('comment');
        return $query->row('value');
    }

    function update_value($document_id, $value, $pos, $neg)
    {
        $data['c_value'] = $value;
        $data['positive_value'] = $pos;
        $data['negative_value'] = $neg;
        $this->db->where('document_id', $document_id);
        $this->db->update('document', $data);
    }

    function get_cvalue($field, $document_id)
    {
        $this->db->select($field);
        $this->db->where('document_id', $document_id);
        $query = $this->db->get('document');
        if(is_null($query->row($field)))
            return 0;
        else
            return $query->row($field);
    }

    function update_sumAggr($value, $posValue, $negValue, $id)
    {
        $data['summarized_value'] = $value;
        $data['summarized_positive_value'] = $posValue;
        $data['summarized_negative_value'] = $negValue;
        $this->db->where('value_id', $id);
        $this->db->update('aggregation_value', $data);
    }
}