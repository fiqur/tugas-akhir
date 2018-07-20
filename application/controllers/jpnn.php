<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Jpnn extends CI_Controller 
{
	function __construct() 
    {
    	parent::__construct();
		$this->load->library('simple_html_dom');
		$this->lib = new redirect_url();
		$this->load->model('Jpnn_Model', '', TRUE);
    }

    function optimize_code()
    {
        set_time_limit(86400);
        ini_set('memory_limit', '-1');
    }

    //ambil link dari txt lalu di redirect
    function read_link()
    {
    	$filePath = "assets/words/link_jpnn.txt";
        $res = fopen($filePath, "r");
        $i = 0;
        while ( $line = fgets($res, 10000) )
        {
        	$redirect = $this->lib->get_all_redirects(trim($line));
        	// echo $redirect[0].'<br/>';
        	$links[$i] = $this->get_link_value($redirect[0], 0);
        	$this->Jpnn_Model->save_links($links[$i]);
        	$i++;
        }
        // return $links;
    }

    //ambil value dari redirect link
    //cat = 0 => ambil link
    //cat = 1 => ambil id_berita
    function get_link_value($link, $cat)
    {
		//get link id
		$exploder = explode("/", $link);
		//jika linknya http://www.jpnn.com/read/2014/10/18/264409/AFTA-Diberlakukan,-Gaji-TKA-Disamakan-dengan-Pekerja-Lokal-
		if(strpos($link, 'http://www.jpnn.com/read/') !== false)
		{
			$id_link = $exploder[7];
		}

		//jika linknya http://www.jpnn.com/m/news_comment.php?id=264409
		elseif(strpos($link, 'http://www.jpnn.com/m/news_comment.php') !== false)
		{
			$id_link = str_replace('news_comment.php?id=', '', $exploder[4]);
		}

		//http://www.jpnn.com/index.php?mib=berita.detail&id=56812
		elseif(strpos($link, 'http://www.jpnn.com/index.php?mib=berita.detail&id') !== false)
		{
			$id_link = str_replace('index.php?mib=berita.detail&id=', '', $exploder[3]);
		}

		//http://www.jpnn.com/index.php?mib=email&id=56812
		elseif(strpos($link, 'http://www.jpnn.com/index.php?mib=email&id') !== false)
		{
			$id_link = str_replace('index.php?mib=email&id=', '', $exploder[3]);
		}

		//http://www.jpnn.com/m/news.php?id=77862
		elseif(strpos($link, 'http://www.jpnn.com/m/news.php?id=') !== false)
		{
			$id_link = str_replace('news.php?id=', '', $exploder[4]);
		}

		$link_result = "http://www.jpnn.com/index.php?mib=berita.detail&id=".$id_link;
		if($cat == 0)
			return $link_result;
		else
			return $id_link;
    }

    //grab & save document from jpnn - AFTA
	public function get_documents()
	{
		$link = $this->Jpnn_Model->get_links();
		for($i = 0; $i < count($link); $i++)
		{
			$link_id = $link[$i]['link_id'];
			$html = file_get_html($link[$i]['link']);
			foreach($html->find('div.tanggal') as $g)
			{
				// echo $g->plaintext.'<br/>';
				$tgl_info = explode(', ',$g->plaintext);
				$tanggal = explode(' ', $tgl_info[1]);
				$month = $this->Jpnn_Model->date_check($tanggal[1]);

				$newdate = $tanggal[2].'-'.$month.'-'.$tanggal[0];
		    	$date = strtotime($newdate);
		    	$date = date('Y-m-d', $date);
				echo $date.'<br/>';
			}
			foreach($html->find('div.hotnews') as $f)
			{
				$title = $f->plaintext;
				$title = trim($title);
				echo '<b>'.$title.'</b>';
			}
			foreach($html->find('div._wr_09') as $e) 
			{
				$content = $e->plaintext;
				$content = trim($content);
				$content = preg_replace('/\s+/', ' ', $content);
				$content = preg_replace("/&#?[a-z0-9]+;/i","",$content);
				echo $i.' - '.$content.'<br/><br/>';
			}
			$this->Jpnn_Model->save_document($title, $date, $content, $link_id);
		}
	}

	public function get_comments()
	{
		$this->optimize_code();
		$link = $this->Jpnn_Model->get_document_links();
		for($i = 40; $i < 66; $i++)
		{
			$id_link = str_replace('http://www.jpnn.com/index.php?mib=berita.detail&id=', '', $link[$i]['link']);
			$comment_link = 'http://www.jpnn.com/m/news_comment.php?id='.$id_link;
			$html = file_get_html($comment_link);
			foreach($html->find('div.commentText') as $g)
			{
				echo $link[$i]['document_id'].' : '.$g->plaintext.'<br/><br/>';
				$content = trim($g->plaintext);
				$content = preg_replace('/\s+/', ' ', $content);
				$content = preg_replace("/&#?[a-z0-9]+;/i","",$content);
				$this->Jpnn_Model->save_comments($content, $link[$i]['document_id']);
			}
			$html->clear(); 
			unset($html);
		}
	}

	function testing()
	{
		$data_link = "http://www.jpnn.com/m/news_comment.php?id=257136";
		$html = file_get_html($data_link);
		foreach($html->find('div.commentText') as $g)
		{
			if($g->plaintext == '')
				echo 'GG';
			else
				echo $g->plaintext.'<br/><br/>';
		}
	}
}