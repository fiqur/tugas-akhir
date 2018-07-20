<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home extends CI_Controller
{
	function __construct()
    {
    	parent::__construct();
        $this->load->model('Sentiment_Model', '', TRUE);
        $this->load->model('Home_Model', '', TRUE);
        $this->load->model('Research_Model', '', TRUE);
        $this->load->model('mbarang');
        $this->load->model('mfeedback');
        $this->load->model('Tanggal_Check','', TRUE);
        $this->load->library('simple_html_dom');
        $this->load->model('minsert');
        $this->load->model('Textmining_Model', '', TRUE);
        $this->whatfor = FALSE;
    }

    function data($cat)
    {
        $data['result'] = $this->Home_Model->getAlldata($cat);
        $data['show_score'] = FALSE;
        $data['cat'] = $cat;
        if($cat == 'document')
        {
            $header['value'] = 1;
            $data['thead'] = "<th>Date Pubished</th><th>Title</th><th>Important Words</th><th>Portal</th><th>Link</th><th>Comments</th>";
        }
        else
        {
            $header['value'] = 2;
            $data['thead'] = "<th>Document ID</th><th>View Doc</th><th>Content</th><th>Value</th><th>Sentiment</th>";
            for($i = 0; $i < count($data['result']); $i++)
            {
                $html = "<p>".$data['result'][$i]['document_title']."<p><p>".$data['result'][$i]['date_published']."</p><p>".implode(' - ', json_decode($data['result'][$i]['kataPenting']))."</p>";
                $button = "<a target='_blank' class='btn btn-dark-grey btn-block' href='".$data['result'][$i]['link']."'>Go to link <i class='icon-circle-arrow-right'></i></a>";
                $data['result'][$i]['html'] = $html.''.$button;
            }
        }
    	$this->load->view('header', $header);
    	$this->load->view('home_data', $data);
    	$this->load->view('footer');
    }

    function view_comment($id)
    {
        $data['result'] = $this->Home_Model->getSpecificData('document_id', 'comment', $id);
        $data['show_score'] = TRUE;
        $data['cat'] = 'comment';
        $data['thead'] = "<th>Content</th><th>Value</th><th>Sentiment</th>";
        $sum = $this->Home_Model->getDetailedData('sum(value) as vsum', 'document_id', 'comment', $id);
        $data['sum'] = $sum[0];
        if(is_null($data['sum']['vsum']))
            $data['sum']['vsum'] = 0;
        $header['value'] = 3;
        $this->load->view('header', $header);
        $this->load->view('home_data', $data);
        $this->load->view('footer');
    }

    function research($cat)
    {
        if($cat == 'test')
        {
            $header['value'] = 5;
            $data['show_result'] = FALSE;
            $data['cat'] = $cat;
            $this->load->view('header', $header);
            $this->load->view('home_test', $data);
            $this->load->view('footer');
        }
        if($cat == 'start')
        {
            $header['value'] = 4;
            $data['show_graph'] = 0;
            $data['portal_list'] = $this->Home_Model->getPortalList();
            $data['year_list'] = $this->Research_Model->get_year();
            $this->load->view('header', $header);
            $this->load->view('trend_comment', $data);
            $this->load->view('footer');
        }
    }

    function test_method()
    {
        $text = $this->input->post('text_input');
        var_dump($text);
    }

    function trend_news()
    {
        $get_year = $this->Research_Model->get_year();
        $portal_list = $this->Home_Model->getPortalList();
        $i = 0;
        foreach($portal_list as $list)
        {
            $portal_list[$i] = $list['portal_id'];
            $i++;
        }

        for($i = 0; $i < count($get_year); $i++)
        {
            $result[$i] = $this->Research_Model->get_total_news_byYear($get_year[$i], $portal_list);
        }

        $data['thead'] = "<th>Date Pubished</th><th>Title</th><th>Important Words</th><th>Portal</th><th>Link</th><th>Comments</th>";
        $data['result'] = $this->Home_Model->getAlldata('document');
        $data['score'] = json_encode($result);
        $data['axis'] = json_encode($get_year);
        $data['portal_list'] = $this->Home_Model->getPortalList();
        $data['year_list'] = $get_year;
        $data['subtitle'] = 'Results';
        $data['query'] = 'All Portals - No Sentiment - All Year';
        $header['value'] = 6;

        $this->load->view('header', $header);
        $this->load->view('trend_news', $data);
        $this->load->view('footer');
    }

    function test_textmining()
    {
        $header['value'] = 7;
        $data['get_result'] = 0;
        $this->load->view('header', $header);
        $this->load->view('home_textmining', $data);
        $this->load->view('footer');
    }

    //fungsi crawling
    function addDataInsert(){

        $gender = $this->input->post('gender'); // deklarasi id_transaksi sekaligus nangkep inputan data yang udah disubmit
        $kategori = $this->input->post('kategori'); //sama kayak diatas
        $url_site = $this->input->post('url_site');
        $lapak = $this->input->post('lapak');  //sama kayak diatas

        $html = file_get_html($url_site);
        $harga = $html->find('.c-product-detail-price');
        $url_image=$html->find('img');
        $nama_barang = $html->find('.u-txt--fair');


        $array_urlimg = array();
        foreach($url_image as $urlimage => $value2) {
           $array_urlimg[] = $value2->src;
        }

        $array_barang = array();
        foreach ($nama_barang as $name_product => $value) {
            // print_r($hasil);
            $array_barang[] = $value->plaintext;
            // $value->plaintext;
        }

        foreach ($harga as $price => $value1) {
            $array_barang[] = $value1->plaintext;
            //$value1->plaintext;
        }

        $this->load->model('minsert'); //meload atau memanggil model mtransaski
        $id_barang = $this->minsert->addInsert($gender, $kategori, $url_site, $array_barang[7], $array_urlimg[1], $array_barang[1],$lapak,0); //mengakses fungsi addTransaski di model mtransaski


        $link="?page=";
        $total_score=0;
        for($page=1; $page<21 ; $page++) {


            $url_site1 = $url_site.$link.$page;
            $html = file_get_html($url_site1);
            $ulasan = $html->find('.qa-product-review-content');
            $tanggal_ulasan =$html->find('.qa-product-review-date');

            // foreach($ulasan as $key => $review)
            // {

            //     $data = $this->kataPenting($review->plaintext);
            //     $data = $this->tools->reconstructArray($data);
            //     $data = json_encode($data);
            //     //$kata_penting = implode(' - ', json_decode($data));
            //     $this->minsert->addFeedback($review->plaintext, $id_barang, $data);
            // }

            // foreach($tanggal_ulasan as $key => $date) {
            //     $date->innertext;
            //     $pos = strpos($date, ' ');
            //     $date = substr($date, $pos+68,-24);
            //     $date = explode(' ', $date);
            //     $month = $this->Tanggal_Check->date_check($date[1]);
            //     $newdate = $date[2].'-'.$month.'-'.$date[0];
            //     $date = strtotime($newdate);
            //     $date = date('Y-m-d', $date);
            //     $this->minsert->addTanggal($date);
            // }

            for ($i=0; $i < count($ulasan); $i++) {
                $this->whatfor = TRUE;
                $review = $ulasan[$i];
                $data = $this->kataPenting($review->plaintext);
                $score = $data['score'];

                $total_score = $total_score + $score;
                // var_dump($data);
                // die();

                // $data = $this->tools->reconstructArray($data);
                $data2 = $data['hasil'];
                $data2 = json_encode($data2);
                //$kata_penting = implode(' - ', json_decode($data));
                // $this->minsert->addFeedback($review->plaintext, $id_barang, $data);

                $date = $tanggal_ulasan[$i];
                $date->innertext;
                $pos = strpos($date, ' ');
                $date = substr($date, $pos+68,-24);
                $date = explode(' ', $date);
                $month = $this->Tanggal_Check->date_check($date[1]);
                $newdate = $date[2].'-'.$month.'-'.$date[0];
                $date = strtotime($newdate);
                $date = date('Y-m-d', $date);
                // $this->minsert->addTanggal($date);
                $this->minsert->addData($review->plaintext, $id_barang, $data2, $date, $score);
            }
        }

        $this->minsert->updateInsert($id_barang, $total_score);

        $this->input->post('submit'); //mengakap inputan yang disubmit lewat post
        redirect('home/dashboard'); //setelah selesai redirect ke halaman awal
    }

    //menampilkan database dan pagination
    function dashboard()
    {

        $this->load->database();
        // $tampil_barang = $this->mbarang->tampil_barang();
        // $this->load->library('pagination');
        // $config['base_url'] = base_url().'index.php/home/dashboard/';
        // $config['total_rows'] = $tampil_barang;
        // $config['per_page'] = 12;

        // $config['full_tag_open'] = "<ul class='pagination'>";
        // $config['full_tag_close'] ="</ul>";
        // $config['num_tag_open'] = '<li>';
        // $config['num_tag_close'] = '</li>';
        // $config['cur_tag_open'] = "<li class='disabled'><li class='active'><a href='#'>";
        // $config['cur_tag_close'] = "<span class='sr-only'></span></a></li>";
        // $config['next_tag_open'] = "<li>";
        // $config['next_tagl_close'] = "</li>";
        // $config['prev_tag_open'] = "<li>";
        // $config['prev_tagl_close'] = "</li>";
        // $config['first_tag_open'] = "<li>";
        // $config['first_tagl_close'] = "</li>";
        // $config['last_tag_open'] = "<li>";
        // $config['last_tagl_close'] = "</li>";
        // $config['first_link']=' Pertama ';
        // $config['last_link']='Terakhir  ';
        // $config['next_link']='> ';
        // $config['prev_link']='< ';

        // $from = $this->uri->segment(3);
        // $this->pagination->initialize($config);
        $data_barang['barang'] = $this->mbarang->data_barang();


        //$crawl_feedback['feedback']=$this->mfeedback->tampil_feedback()->result();
        $header['value'] = 'dashboard';
        $this->load->view('header', $header);
        $this->load->view('home_dashboard', $data_barang);
        $this->load->view('footer');
    }


    public function cari()
    {
        //$this->load->view('search');
        // $header['value'] = 'dashboard';
        //$this->load->view('header', $header);
        $this->load->view('home_dashboard');
        //$this->load->view('footer');
    }

    function feedback()
    {
        if(!empty($_GET)){
            $idBarang = $_GET['id_barang'];

            $this->load->database();
            $data_feedback['feedback'] = $this->mfeedback->data_feedback_barang($idBarang);

            $header['value'] = 'feedback';
            $this->load->view('header', $header);
            $this->load->view('data_feedback', $data_feedback);
            $this->load->view('footer');
        }else{
            $this->load->database();
            $data_feedback['feedback'] = $this->mfeedback->data_feedback();

            $header['value'] = 'feedback';
            $this->load->view('header', $header);
            $this->load->view('data_feedback', $data_feedback);
            $this->load->view('footer');
        }
    }

    function viewTrend(){
        if(!empty($_GET)){
            $data['lapak'] = $_GET['select-toko'];
            $data['gender'] = $_GET['select-gender'];
            $data['kategori'] = $_GET['select-kategori'];
            $data['barang'] = $this->Home_Model->getBarang($data);
            $rows1 = array();
            if(!empty($data['barang'])){
                if (!empty($_GET['jenis']) && $_GET['jenis'] == 'tahunan'){
                        $data['trend']=$this->Home_Model->getTrendTahunan($data['barang']);

                        $rows1['time'] = array_reverse($data['trend']['tahun']);
                        $rows1['data'] = array_reverse($data['trend']['jumlah_tahunan']);

                }elseif (!empty($_GET['jenis']) && $_GET['jenis'] == 'bulanan'){
                        $data['trend']=$this->Home_Model->getTrendBulanan($data['barang'], $_GET['tahun']);

                        if($data['trend'] == null){
                            $rows1 = null;
                        }else{
                            $i=0;
                            foreach ($data['trend']['bulan'] as $b){
                                if($b == 1){
                                    $data['trend']['bulan'][$i] = 'Januari';
                                }elseif ($b == 2){
                                    $data['trend']['bulan'][$i] = 'Februari';
                                }elseif ($b == 3){
                                    $data['trend']['bulan'][$i] = 'Maret';
                                }elseif ($b == 4){
                                    $data['trend']['bulan'][$i] = 'April';
                                }elseif ($b == 5){
                                    $data['trend']['bulan'][$i] = 'Mei';
                                }elseif ($b == 6){
                                    $data['trend']['bulan'][$i] = 'Juni';
                                }elseif ($b == 7){
                                    $data['trend']['bulan'][$i] = 'Juli';
                                }elseif ($b == 8){
                                    $data['trend']['bulan'][$i] = 'Agustus';
                                }elseif ($b == 9){
                                    $data['trend']['bulan'][$i] = 'September';
                                }elseif ($b == 10){
                                    $data['trend']['bulan'][$i] = 'Oktober';
                                }elseif ($b == 11){
                                    $data['trend']['bulan'][$i] = 'November';
                                }elseif ($b == 12){
                                    $data['trend']['bulan'][$i] = 'Desember';
                                }

                                $i++;
                            }

                            $rows1['time'] = array_reverse($data['trend']['bulan']);
                            $rows1['data'] = array_reverse($data['trend']['jumlah_bulanan']);
                        }
                }elseif (!empty($_GET['jenis']) && $_GET['jenis'] == 'interval'){
                    $data['trend']=$this->Home_Model->getTrendInterval($data['barang'], $_GET['start'], $_GET['end']);
                    $rows1['time'] = $data['trend']['bulan'];
                    $rows1['data'] = $data['trend']['jumlah_bulanan'];
                }else{
                    $rows1 = null;
                }
            }else{
                $rows1 = null;
            }

            $result = array();
            array_push($result,$rows1);
            print json_encode($result, JSON_NUMERIC_CHECK);
        }
    }

    function trend()
    {
        $this->load->database();
        $data['toko'] = $this->Home_Model->getAllToko();

        $header['value'] = 'trend';
        $this->load->view('header', $header);
        $this->load->view('trend_komentar', $data);
        $this->load->view('footer');
    }

    function ambil_data(){
        $modul=$this->input->post('modul');
        $lapak=$this->input->post('lapak');
        $gender=$this->input->post('gender');

        if($modul=="gender"){
            echo $this->Home_Model->getGender($lapak);
        }
        else if($modul=="kategori"){
            echo $this->Home_Model->getKategori($lapak, $gender);
        }
    }

    function literature()
    {
        $header['value'] = 'literature';
        $this->load->view('header', $header);
        $this->load->view('home_literature');
        $this->load->view('footer');
    }

    function kataPenting($text = NULL, $testing = NULL)
    {
        $stoplists = NULL;
        $imbuhan = NULL;
        $hasil = NULL ;

        $text = str_replace("\r\n",'', $text);
        $text = str_replace(".",' . ', $text);
        $text = str_replace(",",' , ', $text);
        $text = str_replace("/",'', $text);
        $text = str_replace("!",' ! ', $text);
        $text = str_replace("?",' ? ', $text);

        $text = $this->kataBaku($text, $testing);
        if(!is_null($testing))
        {
            $tidakBaku = $text[1];
            $text = $text[0];
        }

        $text = explode(" ", $text);

        for ($index = 0; $index < count($text); $index++)
        {
            $text = str_replace("2", "", $text);

            if($this->deletedWords($text[$index]))
            {
                unset($hasil[$index]);
            }
            elseif($this->Textmining_Model->cekKataPentingSpesial($text[$index]))
            {
                $hasil[$index] = strtolower($this->nazief($text[$index]));
            }
            elseif ($this->Textmining_Model->cekStopList($text[$index]))
            {
                $hasil[$index] = strtolower($this->nazief($text[$index]));
                if(strtolower($text[$index]) != $hasil[$index] && $hasil[$index] != '')
                    $imbuhan[$index] = strtolower($text[$index]).' = '.$hasil[$index];
                if(is_null($hasil[$index]) || $hasil[$index] == '' || preg_match('/^[0-9]{1,}$/', $hasil[$index]))
                {
                    unset($hasil[$index]);
                }
            }

            else
                $stoplists[$index] = strtolower($text[$index]);

        }

        if(is_null($testing))
        {
            $string = $this->tools->reconstructArray($hasil);
            $result = $this->sentiment_analysis($string);
            return $result;
        }
        else
            return array($hasil, $stoplists, $imbuhan, $tidakBaku);
    }

    function sentiment_analysis($sentence)
    {
        $score = array();
        // var_dump($sentence);
        $i = 0;
        foreach($sentence as $data)
        {
            $word_type[$i] = $this->Sentiment_Model->get_word_type($sentence[$i]);
            $i++;
        }
        // var_dump($word_type);

        for($i = 0; $i < count($word_type); $i++)
        {
            //cek verba
            if($this->cektype($word_type[$i]['type'], 'Verba'))
            {
                //cek ada keterangan sebelum verba
                if($i != 0 && $i != count($word_type)-1) // jika tidak di awal kalimat
                {
                    if($this->cektype($word_type[$i-1]['type'], 'Keterangan'))
                    {
                        error_reporting(0);
                        if($this->cektype($word_type[$i+1]['type'], 'Adjektiva'))
                        {
                            $verb_adj = $this->countLogic(intval($word_type[$i]['value']), intval($word_type[$i+1]['value']), 'after');
                            $score[$i] = $this->countLogic(intval($word_type[$i-1]['value']), $verb_adj, 'before');
                            $counted[$i-1] = 'l';
                            $counted[$i+1] = 'l';
                            $i++;
                        }
                        else
                        {
                            $score[$i] = $this->countLogic(intval($word_type[$i-1]['value']), intval($word_type[$i]['value']), 'before');
                            // echo 'verba SEBELUM '.$word_type[$i]['word'].' '.$i.'<br/>';
                        }
                        $counted[$i] = 'l';
                        $counted[$i-1] = 'l';
                    }
                    elseif($this->cektype($word_type[$i+1]['type'], 'Adjektiva'))
                    {
                        $score[$i] = $this->countLogic(intval($word_type[$i]['value']), intval($word_type[$i+1]['value']), 'after');
                        $counted[$i] = 'l';
                        $counted[$i+1] = 'l';
                        $i++;
                    }
                    else
                    {
                        $score[$i] = intvaL($word_type[$i]['value']);
                        $counted[$i] = 's';
                    }
                }

                //cek ada adjektiva sesudah verba
                elseif($i != count($word_type)-1) //jika tidak diakhir kalimat
                {
                    if($this->cektype($word_type[$i+1]['type'], 'Adjektiva'))
                    {
                        echo 'masuk';
                        $score[$i] = $this->countLogic(intval($word_type[$i]['value']), intval($word_type[$i+1]['value']), 'after');
                        $counted[$i] = 'l';
                        $counted[$i+1] = 'l';
                        // echo 'verba SESUDAH '.$word_type[$i]['word'].' '.$i.'<br/>';
                        $i++;
                    }
                    else
                    {
                        $score[$i] = intvaL($word_type[$i]['value']);
                        $counted[$i] = 's';
                    }
                }
                else
                {
                    $score[$i] = intvaL($word_type[$i]['value']);
                    $counted[$i] = 's';
                }
            }

            //cek adjektiva
            elseif($this->cektype($word_type[$i]['type'], 'Adjektiva'))
            {
                //cek ada keterangan sebelum adjektiva
                if($i != 0) // jika tidak di awal kalimat
                {
                    if($this->cektype($word_type[$i-1]['type'], 'Keterangan'))
                    {
                        error_reporting(0);
                        if($this->cektype($word_type[$i+1]['type'], 'Verba'))
                        {
                            $pre_adj = $this->countLogic(intval($word_type[$i-1]['value']), intval($word_type[$i]['value']), 'after');
                            $score[$i] = $this->countLogic(intval($pre_adj), $word_type[$i+1]['value'], 'before');
                            $counted[$i-1] = 'l';
                            $counted[$i+1] = 'l';
                            $i++;
                        }
                        else
                        {
                            $score[$i] = $this->countLogic(intval($word_type[$i-1]['value']), intval($word_type[$i]['value']), 'before');
                            // echo 'verba SEBELUM '.$word_type[$i]['word'].' '.$i.'<br/>';
                        }
                        $counted[$i] = 'l';
                        $counted[$i-1] = 'l';
                    }
                    else
                    {
                        $score[$i] = intvaL($word_type[$i]['value']);
                        $counted[$i] = 's';
                    }
                }
                //cek ada verba sesudah adjektiva
                elseif($i != count($word_type)-1) //jika tidak di akhir kalimat
                {
                    if($this->cektype($word_type[$i+1]['type'], 'Verba'))
                    {
                        $score[$i] = $this->countLogic(intval($word_type[$i]['value']), intval($word_type[$i+1]['value']), 'after');
                        $counted[$i] = 'l';
                        $counted[$i+1] = 'l';
                        // echo 'SESUDAH '.$word_type[$i]['word'].' '.$i.'<br/>';
                        $i++;
                    }
                    else
                    {
                        $score[$i] = intvaL($word_type[$i]['value']);
                        $counted[$i] = 's';
                    }
                }
                else
                {
                    $score[$i] = intvaL($word_type[$i]['value']);
                    $counted[$i] = 's';
                }
            }

            elseif($word_type[$i]['value'] != 0 && !$this->cektype($word_type[$i]['type'], 'Keterangan'))
            {
                $score[$i] = intvaL($word_type[$i]['value']);
                $counted[$i] = 's';
            }

            else
                $counted[$i] = 'x';
        }
        $result['array_score'] = $this->tools->reconstructArray($score);
        $result['valued_index_array'] = $counted;
        // /$default_array = array_fill(0, count($word_type), 'x');

        $result['hasil'] = $sentence;
        $result['array_info'] = $word_type;
        $result['score'] = array_sum($score);

        if(!$this->whatfor)
            return $result['score'];
        else
            return $result;
    }

    function cektype($data, $type)
    {
        if($data == $type)
            return true;
        else
            return false;
    }

    function countLogic($x, $y, $type)
    {
        //positif ketemu positif
        if($x == 1 && $y == 1)
        {
            if($type == 'before')
                $result = 1;
            elseif($type == 'after')
                $result = 1;
        }

        //positif ketemu negatif
        elseif($x == 1 && $y == -1)
        {
            if($type == 'before')
                $result = -1;
            elseif($type == 'after')
                $result = -1;
        }

        //negatif ketemu positif
        elseif($x == -1 && $y == 1)
        {
            if($type == 'before')
                $result = -1;
            elseif($type == 'after')
                $result = -1;
        }

        //negatif ketemu negatif
        elseif($x == -1 && $y == -1)
        {
            if($type == 'before')
                $result = 1;
            elseif($type == 'after')
                $result = -1;
        }

        elseif($x == 0)
            $result = $y;
        elseif($y == 0)
            $result = $x;
        return $result;
    }

    function nazief($kata)
    {
        $this->load->library('textmining');
        if($this->Textmining_Model->cekKataPentingSpesial($kata))
        {
            return $kata;
        }
        elseif($this->Textmining_Model->cekKataDasar($kata))
        {
            return $kata;
        }
        else
        {
            $kata = $this->textmining->deleteInflectionSuffixes($kata);
            $kata = $this->textmining->deleteDerivationSuffixes($kata);
            $kata = $this->textmining->deleteDerivationPrefixes($kata);
            return $kata;
        }
    }

    function kataBaku($text, $testing = NULL)
    {
        $tidakBaku = NULL;
        $text = explode(" ", $text);
        for($i = 0; $i < count($text); $i++)
        {
            $baku[$i] = $this->Sentiment_Model->cekKataBaku($text[$i]);
            if($text[$i] != $baku[$i] && !is_null($testing))
                $tidakBaku[$i] = $text[$i].' = '.$baku[$i];
        }

        $result = implode(" ", $baku);
        if(is_null($testing))
            return $result;
        else
            return array($result, $tidakBaku);
    }

    function deletedWords($text)
    {
        $words = array(
                "pernah"
            );
        if(in_array($text, $words))
            return true;
        else
            return false;
    }

}
