<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ajax_redirect extends CI_Controller 
{
	function __construct() 
    {
    	parent::__construct();
		$this->lib = new redirect_url();
    }

    //grab link from detik - AFTA
	public function index()
	{
		$this->load->view("ajaxredirect");
	}
	public function get_all_link(){
		$filePath = "assets/words/link_jpnn.txt";
        $res = fopen($filePath, "r");
        $i = 0;
        $links = array();
        while ( $line = fgets($res, 10000) )
        {
        	$links[$i] = $line;
        	$i++;
        }

        echo json_encode($links);
	}

	public function redir_link(){
		$link = $this->input->get("link");
		
		$dt = $this->lib->get_all_redirects(trim($link));
		echo json_encode($dt[0]);
	}
}