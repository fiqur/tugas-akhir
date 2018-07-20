<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Sentiment_model extends MY_Model
{

    private $katadasar;
    private $stoplist;
    private $artikel;
            
    function __construct() 
    {
        parent::__construct();
        $this->katadasar = 'word_dictionary';
        $this->stoplist = 'stoplist';
        $this->sentimentalWord = 'sentimental_word';
        $this->kataBaku = 'kata_baku';
    }

    function save_sentiword($data)
    {
        $dt = $this->db->query('INSERT INTO sentimental_word (`word`, `type`, `value`) VALUES '.$data.';');
    }

    function save_katabaku($data)
    {
        $dt = $this->db->query('INSERT INTO kata_baku (`kata`, `kata_asli`) VALUES '.$data.';');
        //$dt = $this->db->query('INSERT INTO word_dictionary (`katadasar`, `tipe_katadasar`) VALUES '.$data.';');
    }

    function get_word_type($data)
    {
        $this->db->select('word, type, value');
        $this->db->where('word', $data);
        $query = $this->db->get($this->sentimentalWord);
        $result = $query->row_array();
        if(empty($result))
        {
            $result['word'] = $data;
            $result['type'] = $this->getWordType($data);
            $result['value'] = '0';
            return $result;
        }
        else
            return $result;
    }

    function getWordType($kata)
    {
        $this->db->select('tipe_katadasar');
        $this->db->where('katadasar', $kata);
        $query = $this->db->get($this->katadasar);
        $result = $query->row_array();
        if(empty($result))
            return 'Unknown';
        else
            return $result['tipe_katadasar'];
    }

    function cekKataBaku($kata) 
    {
        $query = $this->get_single($this->kataBaku, 'kata', $kata);
        if ($query) 
        {
            return $query['kata_asli'];
        }
        else
        {
            return $kata;
        }
    }
}