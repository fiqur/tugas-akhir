<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Detik extends CI_Controller 
{
	function __construct() 
    {
    	parent::__construct();
		$this->load->library('simple_html_dom');
		$this->load->library('redirect_url');
		$this->load->model('Detik_Model', '', TRUE);
    }

    //grab link from detik - AFTA
	public function index()
	{
		$index = 0;
		$i = 0;
		while($index >= 0)
		{
			$data_link = 'http://m.search.detik.com/index.php?fa=detik.searchresult&amp;hitsPerPage=10&amp;query=afta&amp;sortby=time&amp;start='.$index.'&amp;site=null&amp;siteid=&amp;kanal=&amp;location=&amp;keyword=&amp;title=null&amp;subtitle_or=&amp;keywordor=&amp;fromdate=&amp;todate=';
			$html = file_get_html($data_link);
			var_dump($html);
			foreach($html->find('a') as $e) 
			{
				if(strpos($e->href, 'read') !== false)
				{
					$grab[$i] = $e->href;
					// $this->Detik_Model->save_links($grab[$i]);
					echo $grab[$i].'<br/>';
					$i++;
				}
			}
			foreach($html->find('li') as $f)
			{
				if($f->innertext == 'Data tidak ditemukan')
				{
					var_dump(count($grab));
					var_dump($grab);
					$exitloop = true;
					break;
				}
				else
				{
					$exitloop = false;
					break;
				}
			}

			if($exitloop) 
			{
				break;
			}
			$index+=10;
			echo '<br/><br/><br/>';
		}
	}

	//get article content from link
	public function get_documents()
	{
		$links = $this->Detik_Model->get_links();
		$j = 0;
		for($i = 100; $i < 134; $i++)
		{
			$data_link = $links[$i]['link'];
			$link_id = $links[$i]['link_id'];
			$html = file_get_html($data_link);
			
			foreach($html->find('h1') as $e) 
    			$title = $e->innertext;
    		foreach($html->find('div.date') as $e)
    			$date = $e->innertext;

    		$pos = strpos($date, '/');
        	$date = substr($date, $pos-2, 10);
        	$date = explode('/', $date);
        	$newdate = $date[2].'-'.$date[1].'-'.$date[0];
        	$date = strtotime($newdate);
        	$date = date('Y-m-d', $date);

			if(strpos($data_link, 'http://news.detik.com/') !== false)
			{
				foreach($html->find('div.artikel2') as $e)
				{
					$j++;
					$text = strip_tags($e->plaintext);
					$text = trim($text);
					$text = preg_replace('/\s+/', ' ', $text);
					$text = preg_replace('/Ikuti berbagai.*/', '.', $text, -1);
					// echo $i.' '.$link_id.' '.$data_link.'<br/>';
					// echo $text.'<br/><br/><br/><br/>';
					$this->Detik_Model->save_document($title, $date, $text, $link_id);
				}
			}
			else
			{
				foreach($html->find('div.text_detail') as $e)
				{
					$j++;
					$content = strip_tags($e->innertext);
					$text = trim($content);
					$text = str_replace('&nbsp', '', $content);
					// echo $i.' '.$link_id.' '.$data_link.'<br/>';
					// echo $text.'<br/><br/><br/><br/>';
					$this->Detik_Model->save_document($title, $date, $text, $link_id);
				}
			}
		}
		
	}

	//get comments
	public function get_comments()
	{
		$metadata_link = $this->Detik_Model->get_document_links();
		$limit = 134;
		for($i = 100; $i < $limit; $i++)
		{
			$this->json_grab_comments($metadata_link[$i]['link'], $metadata_link[$i]['document_id']);
		}
	}

	//grab json data from detik comments
	public function json_grab_comments($link, $doc_id)
	{
		$link_value = $this->get_value_json($link);
		$json_url = 'http://comment.detik.com/v1.1/?format=jsonp&callback=cmmnt.commentcall&thn='.$link_value['year'].'&bln='.$link_value['month'].'&tgl='.$link_value['date'].'&idkanal='.$link_value['kanal'].'&idnews='.$link_value['id'].'&p=1&limit=500';
		$json_comments = file_get_html($json_url)->plaintext;
		$data = $this->jsonp_decode($json_comments);
		if(count($data) != 1)
		{
			$limit = count($data['data']);
			for($i = 0; $i < $limit; $i++)
			{
				//echo $data['data'][$i]['content'].'<br/>';
				$this->Detik_Model->save_comments($data['data'][$i]['content'], $doc_id);
			}
		}
		//$data['data'][0]['content'];
	}

	//decode jsonp function
	function jsonp_decode($jsonp, $assoc = true) { 
		// PHP 5.3 adds depth as third parameter to json_decode
    	$jsonLiterals = array('true' => 1, 'false' => 1, 'null');
    	if(preg_match('/^[^[{"\d]/', $jsonp) && !isset($jsonLiterals[$jsonp])) { // we have JSONP
       		$jsonp = substr($jsonp, strpos($jsonp, '('));
    	}
    	return json_decode(trim($jsonp,'();'), $assoc);
	}

	//explode link to get article value
	public function get_value_json($link)
	{
		list($http, $empty, $domain, $read, $year, $month, $date, $id, $id_article, $id_kanal, $title) = explode("/", $link);
		$data['year'] = $year;
		$data['month'] = $month;
		$data['date'] = $date;
		$data['kanal'] = $id_kanal;
		$data['id'] = $id_article;
		return $data;
	}
}