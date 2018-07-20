<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Datadumping extends CI_Controller 
{
	function __construct() 
    {
    	parent::__construct();
        $this->load->model('Sentiment_Model', '', TRUE);
    }

    function read_Adjtxt()
    {
        $i = 0;
        $filePath = "assets/words/katasifat.txt";
        $res = fopen($filePath, "r");
        while ( $line = fgets($res, 10000) )
        {
            $line = preg_replace('/\b([A-Z]+)\b/', '', $line);
            $line = preg_replace('/\s+/', '', $line);
            $line = str_replace(',', '", "Adjektiva", ', $line);
            $data = '("'.$line.')';
            $this->Sentiment_Model->save_sentiword($data);
            echo $data;
            echo '<br/>';
            $i++;
        }
        echo $i;
    }

    function read_Verbtxt()
    {
        $i = 0;
        $filePath = "assets/words/katakerja.txt";
        $res = fopen($filePath, "r");
        while ( $line = fgets($res, 10000) )
        {
            $line = preg_replace('/\b([A-Z]+)\b/', '', $line);
            $line = preg_replace('/\s+/', '', $line);
            $line = str_replace(',', '", "Verba", ', $line);
            $data = '("'.$line.')';
            $this->Sentiment_Model->save_sentiword($data);
            echo $data;
            echo '<br/>';
            $i++;
        }
        echo $i;
    }

    function read_Katabaku()
    {
        $i = 0;
        $filePath = "assets/words/katabaku.txt";
        $res = fopen($filePath, "r");
        while ( $line = fgets($res, 10000) )
        {
            $line = preg_replace('/\b([A-Z]+)\b/', '', $line);
            $line = preg_replace('/\s+/', '', $line);
            $line = str_replace(',','", "', $line);
            $data = '("'.$line.'")';
            $this->Sentiment_Model->save_katabaku($data);
            echo $data;
            echo '<br/>';
            $i++;
        }
        echo $i;
    }   
}