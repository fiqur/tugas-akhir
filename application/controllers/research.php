<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Research extends CI_Controller 
{
	function __construct() 
    {
    	parent::__construct();
        $this->load->model('Research_Model', '', TRUE);
        $this->load->model('Textmining_Model', '', TRUE);
        $this->load->model('Home_Model', '', TRUE);
        $this->load->library('textmining');
        $this->load->library('tools');
    }

    function test_array()
    {
        $data = array(
                array('nilai' => 10, 'nama' => 'mstf'),
                array('nilai' => 140, 'nama' => 'kml'),
                array('nilai' => 110, 'nama' => 'mstf'),
                array('nilai' => 510, 'nama' => 'mstf'),
                array('nilai' => 310, 'nama' => 'kml'),
                array('nilai' => 310, 'nama' => 'iclik')
        );
        $data_result = array();
        foreach($data as $value)
        {
            $nama = $value['nama'];
            $nilai = $value['nilai'];

            $found = false;
            foreach($data_result as $key => $res)
            {
                if($res['nama'] == $nama)
                {
                    $data_result[$key]['nilai'] += $nilai;
                    // echo $res['nilai']." ";
                    $found = true;
                    break;
                }
            }
            if(!$found)
            {
                array_push($data_result, $value);
            }
        }            
        var_dump($data_result);
    }
    
    function index()
    {
    	$text = $this->input->post('keyword');
        if($text == '' || $text == ' ')
        {
            redirect('home/research/start');
        }
        else
        {
            $portal = $this->input->post('checkboxPortal');
            $sentiment = $this->input->post('radioSentiment');
            $interval = $this->input->post('radioTime');
        	$kataPenting = $this->tools->reconstructArray($this->kataPenting($text));

            if($sentiment == 1)
                $sentiment = 'summarized_positive_value';
            elseif($sentiment == 2)
                $sentiment = 'summarized_negative_value';
            else
                $sentiment = 'summarized_value';
        	// $aggregation_id = $this->Research_Model->get_aggregation_id($kataPenting);
            
            $get_year = $this->Research_Model->get_year();
            if($interval == 1)
            {
                $from = $this->input->post('interval_date_from');
                $until = $this->input->post('interval_date_until');
                $data_score = $this->Research_Model->get_data_score_byIntervalDate($kataPenting, $portal, $from, $until, 0);
                if(empty($data_score))
                {
                    $data['score'] = '[0,0,0,0,0,0,0,0,0,0,0]';
                }
                else 
                {
                    $score = $this->Research_Model->get_chart_value_byIntervalDate($data_score, $sentiment);                
                    $data_graph = $this->Research_Model->set_data_series($score, $sentiment);
                    $data['score'] = implode(', ',$data_graph);
                }

                $result = $this->Research_Model->get_data_score_byIntervalDate($kataPenting, $portal, $from, $until, 1);
                $data['result'] = array_filter($result);
                $data['inv'] = TRUE;
                $data['keyword'] = '"'.$text.'"';
                $header['value'] = 4;
            }
            elseif($interval == 2)
            {
                $year = $this->input->post('byYear_year');
                for($i = 0; $i < 12; $i++)
                {
                    $score[$i] = intval($this->Research_Model->get_chart_value_byYear($portal, $interval, $kataPenting, $year, $i+1, $sentiment));
                }

                $result = $this->Research_Model->get_data_score_byYear($portal, $kataPenting, $year);
                $data['result'] = array_filter($result);
                $data['score'] = json_encode($score);
                $data['inv'] = FALSE;

                $m = array("Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember");
                $data['axis'] = json_encode($m);
                $data['keyword'] = '"'.$text.'" in '.$year;
                $header['value'] = 4;
            }
            elseif($interval == 3)
            {
                for($i = 0; $i < count($get_year); $i++)
                {
                    $score[$i] = intval($this->Research_Model->get_chart_value_allyear($portal, $interval, $kataPenting, $get_year[$i], 'year', $sentiment));
                }
                $result = $this->Research_Model->get_data_score_allyear($portal, $kataPenting);
                $data['result'] = array_filter($result);
                $data['score'] = json_encode($score);
                $data['inv'] = FALSE;
                $data['axis'] = json_encode($get_year);
                $data['keyword'] = '"'.$text.'"';
                $header['value'] = 4;
            }

            $data['thead'] = "<th>Date Pubished</th><th>Title</th><th>Important Words</th><th>Portal</th><th>Link</th><th>Comments</th>";
            $data['portal_list'] = $this->Home_Model->getPortalList();
            $data['show_graph'] = 1;
            $data['year_list'] = $get_year;
            $this->load->view('header', $header);
            $this->load->view('trend_comment', $data);
            $this->load->view('footer');
        }
    }
    public function kataPenting($text)
	{
		$text = str_replace("\r\n",'', $text);
        $text = str_replace(".",'', $text);
        $text = str_replace("/",'', $text);
        // $text = str_replace("&",'', $text);
        
        $text = explode(" ", $text);
        
        for ($index = 0; $index < count($text); $index++) 
        {
            if ($this->Textmining_Model->cekStopList($text[$index]) || $this->Textmining_Model->cekKataPentingSpesial($text[$index])) 
            {
                $hasil[$index] = strtolower($this->nazief($text[$index]));
                if(is_null($hasil[$index]) || $hasil[$index] == '' || preg_match('/^[0-9]{1,}$/', $hasil[$index]))
                {
                    unset($hasil[$index]);
                }
            }
        }
        //echo json_encode($hasil);
        return $hasil;
	}

	function nazief($kata) 
    {
        $kataAsal = $kata;
        if(!$this->Textmining_Model->cekKataDasar($kata))
        {
            $kataAsal = $kata;
            $kata = $this->textmining->deleteInflectionSuffixes($kata);
            $kata = $this->textmining->deleteDerivationSuffixes($kata);
            $kata = $this->textmining->deleteDerivationPrefixes($kata);
        }

        if(strlen($kata) < 3 || preg_match('#[0-9]#',$kata))
            return '';
        else
            return $kata;        
    }

    function trend_news()
    {
        $text = $this->input->post('keyword');
        $kataPenting = $this->tools->reconstructArray($this->kataPenting($text));
        $subtitle = implode(', ', $kataPenting);
        for($i = 0; $i<count($kataPenting); $i++)
        {
            $kataPenting[$i] = '%"'.$kataPenting[$i].'"%';
            $kataPenting[$i] = "'".$kataPenting[$i]."'";
        }
        
        $portal = $this->input->post('checkboxPortal');
        $sentiment = $this->input->post('radioSentiment');
        $interval = $this->input->post('radioTime');
        $viewPortal = $this->input->post('radioViewPortal');
        
        //nama portal
        $portal_name = $this->Home_Model->getDetailDataIn('portal_id', 'portals', $portal);
        for($i = 0; $i < count($portal_name); $i++)
        {
            $pname[$i] = $portal_name[$i]['name'];
        }

        //nama sentiment
        if($sentiment == 1)
            $sname = 'Positive Only';
        elseif($sentiment == 2)
            $sname = 'Negative Only';
        else
            $sname = 'No Sentiment';

        if($interval == 2)
        {
            $year = $this->input->post('byYear_year');
            for($i = 0; $i < 12; $i++)
            {
                $result[$i] = $this->Research_Model->get_total_news_byMonth($year, $i+1 ,$portal, $kataPenting, $sentiment);
            }
            $m = array("Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember");
            $data['result'] = $this->Research_Model->get_data_news_trend_withYear($interval, $portal, $kataPenting, $sentiment, $year);
            
            $data['query'] = implode(', ',$pname).' - '.$sname.' - '.'in '.$year;
            $data['axis'] = json_encode($m);
        }
        elseif($interval == 3)
        {
            $get_year = $this->Research_Model->get_year();
            for($i = 0; $i < count($get_year); $i++)
            {
                $result[$i] = $this->Research_Model->get_total_news_byYear($get_year[$i], $portal, $kataPenting, $sentiment);
            }
            $data['result'] = $this->Research_Model->get_data_news_trend($interval, $portal, $kataPenting, $sentiment);
            $data['query'] = implode(', ',$pname).' - '.$sname;
            $data['axis'] = json_encode($get_year);
        }

        $data['thead'] = "<th>Date Pubished</th><th>Title</th><th>Important Words</th><th>Portal</th><th>Link</th><th>Comments</th>";
        $data['score'] = json_encode($result);
        $data['portal_list'] = $this->Home_Model->getPortalList();
        $data['subtitle'] = $subtitle;
        $data['year_list'] = $this->Research_Model->get_year();
        $header['value'] = 6;

        $this->load->view('header', $header);
        $this->load->view('trend_news', $data);
        $this->load->view('footer');
    }
}