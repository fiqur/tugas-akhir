<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Textmining_model extends MY_Model
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

    function cekKataPentingSpesial($kata)
    {
        $query = $this->get_single($this->sentimentalWord, 'word', $kata);
        if($query)
            return TRUE;
        else
            return FALSE;
    }
}