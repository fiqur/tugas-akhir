<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Textmining_Document extends CI_Controller 
{
	function __construct() 
    {
    	parent::__construct();
        $this->load->model('Document_Model', '', TRUE);
        
        //detik
        $this->border = $this->Document_Model->select_db('*', 'portals', 'name', 'portal_id');
    }

    function print_preview($data = NULL)
    {
        echo '<pre>';
        print_r($data);
        echo '</pre>';
    }

    function optimize_code()
    {
        set_time_limit(86400);
        ini_set('memory_limit', '-1');
    }

    function index()
    {
        $content = $this->Document_Model->select_db('document_content, document_id', 'document', 'document_id >', 132);
        $limit = count($content);
        for($i = 0; $i < $limit; $i++)
        {
            $data[$i] = $this->kataPenting($content[$i]['document_content']);
            $data[$i] = $this->tools->reconstructArray($data[$i]);
            echo json_encode($data[$i]).'<br/><br/>';
            $this->Document_Model->add_kataPenting(json_encode($data[$i]), $content[$i]['document_id']);
        }
        // $this->print_preview($content);
    }

    function test_textmining()
    {
        $text = $this->input->post('text_input');

        if($this->input->post('checkboxComment') == 1)
        {
            $this->session->set_userdata('behaviour', 'comment');
            
            $this->session->set_userdata('text', $text);
            redirect('textmining_comment/test_textmining');
        }
        else
        {
            $this->session->set_userdata('behaviour', 'text');
            
            $result = $this->kataPenting($text, TRUE);

            $header['value'] = 7;
            $data['result'] = $result[0];
            $data['stoplist'] = $result[1];
            $data['imbuhan'] = $result[2];
            $data['text'] = $text;
            $data['get_result'] = 1;

            $this->load->view('header', $header);
            $this->load->view('home_textmining', $data);
            $this->load->view('footer');
        }
    }

	public function kataPenting($text, $testing = NULL)
	{
		$text = str_replace("\r\n",'', $text);
        $text = str_replace("!",'', $text);
        $text = str_replace(".",'', $text);
        $text = str_replace(",",'', $text);
        $text = str_replace("/",'', $text);
        $text = str_replace('-', ' ', $text);
        $text = str_replace('--', ' ', $text);
        $text = str_replace('"', '', $text);
        $text = str_replace('============', '', $text);
        // $text = str_replace("&",'', $text);
        
        $text = explode(" ", $text);
        $stoplists = NULL;
        $imbuhan = NULL;
        for ($index = 0; $index < count($text); $index++) 
        {
            if ($this->Document_Model->cekStopList($text[$index])) 
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
            {
                $stoplists[$index] = strtolower($text[$index]);
            }
        }
        if(is_null($testing))
            return $hasil;
        else
            return array($hasil, $stoplists, $imbuhan);
	}

	function nazief($kata) 
    {
        $kataAsal = $kata;
        if(!$this->Document_Model->cekKataDasar($kata))
        {
            $kataAsal = $kata;
            $kata = str_replace('(', '', $kata);
            $kata = str_replace(')', '', $kata);

            $kata = $this->deleteInflectionSuffixes($kata);
            // echo '1. '.$kata;
            if(!$this->Document_Model->cekKataDasar($kata))
            {
                $kata = $this->deleteDerivationSuffixes($kata);
                // echo ' 2. '.$kata;
            }
            if(!$this->Document_Model->cekKataDasar($kata))
            {
                $kata = $this->deleteDerivationPrefixes($kata);
                // echo ' 3. '.$kata;
            }
        }

        if(strlen($kata) < 3 || preg_match('/^[0-9]{1,}$/',$kata))
            return '';
        else
        {
            // echo $kata.'<br/>';
            return $kata;
        }
    }

    function deleteInflectionSuffixes($kata) 
    {
        $kataAsal = $kata;
        if (preg_match('/([km]u|nya|[kl]ah|pun)$/i', $kata)) 
        {
            $__kata = preg_replace('/(nya|[kl]ah|pun)$/i', '', $kata);
            if (preg_match('/([klt]ah|pun)$/i', $kata))
            {
                if (preg_match('/([km]u|nya)$/i', $kata))
                {
                    $__kata__ = preg_replace('/([km]u|nya)$/i', '', $kata);                    
                    return $__kata__;
                }
            }
            return $__kata;
        }
        return $kataAsal;      
    }
    
    function deleteDerivationSuffixes($kata) 
    {
        $kataAsal = $kata;
        if (preg_match('/(i|an)$/i', $kata)) 
        {
            
            $__kata = preg_replace('/(i|an)$/i', '', $kata);
            if ($this->Document_Model->cekKataDasar($__kata))
            {
                return $__kata;
            }
            
            if (preg_match('/(kan)$/i', $kata)) 
            {
                $__kata__ = preg_replace('/(kan)$/i', '', $kata);
                if ($this->Document_Model->cekKataDasar($__kata__)) 
                {
                    return $__kata__;
                }
            }
            if ($this->checkPrefixDisallowedSuffixes($kata)) 
            {
                return $kataAsal;
            }
        }
        return $kataAsal;
    }
    
    function deleteDerivationPrefixes($kata) 
    {
        $kataAsal = $kata;
        // Jika di-,ke-,se-
        if (preg_match('/^(di|[ks]e)/i', $kata)) 
        {
            $__kata = preg_replace('/^(di|[ks]e)/i', '', $kata);
            
            if ($this->Document_Model->cekKataDasar($__kata)) 
            {
                return $__kata;
            }
            
            $__kata__ = $this->deleteDerivationSuffixes($__kata);
            if ($this->Document_Model->cekKataDasar($__kata__)) 
            {
                return $__kata__;
            }
            
            if (preg_match('/^(diper)/i', $kata)) 
            {
                $__kata = preg_replace('/^(diper)/i', '', $kata);
                if ($this->Document_Model->cekKataDasar($__kata)) 
                {
                    return $__kata;
                }
                
                $__kata__ = $this->deleteDerivationSuffixes($__kata);
                if ($this->Document_Model->cekKataDasar($__kata__)) 
                {
                    return $__kata__;
                }
                
                $__kata = preg_replace('/^(diper)/i', 'r', $kata);
                if ($this->Document_Model->cekKataDasar($__kata)) 
                {
                    return $__kata; // Jika ada balik
                }
                
                $__kata__ = $this->deleteDerivationSuffixes($__kata);
                if ($this->Document_Model->cekKataDasar($__kata__)) 
                {
                    return $__kata__;
                }
            }
        }
        
        if (preg_match('/^([tmbp]e)/i', $kata)) 
        { 
            
            if (preg_match('/^(te)/i', $kata)) 
            { 
                if (preg_match('/^(terr)/i', $kata)) 
                {
                    return $kata;
                }
                
                if (preg_match('/^(ter)[aiueo]/i', $kata)) 
                {
                    $__kata = preg_replace('/^(ter)/i', '', $kata);
                    if ($this->Document_Model->cekKataDasar($__kata)) 
                    {
                        return $__kata;
                    }
                    
                    $__kata__ = $this->deleteDerivationSuffixes($__kata);
                    if ($this->Document_Model->cekKataDasar($__kata__)) 
                    {
                        return $__kata__;
                    }
                }
                
                if (preg_match('/^(ter[^aiueor]er[aiueo])/i', $kata)) 
                {
                    $__kata = preg_replace('/^(ter)/i', '', $kata);
                    if ($this->Document_Model->cekKataDasar($__kata)) 
                    {
                        return $__kata;
                    }
                    
                    $__kata__ = $this->deleteDerivationSuffixes($__kata);
                    if ($this->Document_Model->cekKataDasar($__kata__)) 
                    {
                        return $__kata__;
                    }
                }
                
                if (preg_match('/^(ter[^aiueor]er[^aiueo])/i', $kata)) 
                {
                    $__kata = preg_replace('/^(ter)/i', '', $kata);
                    if ($this->Document_Model->cekKataDasar($__kata))
                    {
                        return $__kata; // Jika ada balik
                    }
                    
                    $__kata__ = $this->deleteDerivationSuffixes($__kata);
                    if ($this->Document_Model->cekKataDasar($__kata__)) 
                    {
                        return $__kata__;
                    }
                }
                
                if (preg_match('/^(ter[^aiueor][^(er)])/i', $kata)) 
                {
                    $__kata = preg_replace('/^(ter)/i', '', $kata);
                    if ($this->Document_Model->cekKataDasar($__kata)) 
                    {
                        return $__kata;
                    }
                    
                    $__kata__ = $this->deleteDerivationSuffixes($__kata);
                    if ($this->Document_Model->cekKataDasar($__kata__)) 
                    {
                        return $__kata__;
                    }
                }
                
                if (preg_match('/^(te[^aiueor]er[aiueo])/i', $kata)) 
                {
                    return $kata;
                }
                
                if (preg_match('/^(te[^aiueor]er[^aiueo])/i', $kata)) 
                {
                    $__kata = preg_replace('/^(te)/i', '', $kata);
                    if ($this->Document_Model->cekKataDasar($__kata)) 
                    {
                        return $__kata; // Jika ada balik
                    }
                    
                    $__kata__ = $this->deleteDerivationSuffixes($__kata);
                    if ($this->Document_Model->cekKataDasar($__kata__)) 
                    {
                        return $__kata__;
                    }
                }
            }
            
            if (preg_match('/^(me)/i', $kata)) 
            {
                if (preg_match('/^(meng)[aiueokghq]/i', $kata)) 
                {
                    $__kata = preg_replace('/^(meng)/i', '', $kata);
                    if ($this->Document_Model->cekKataDasar($__kata)) 
                    {
                        return $__kata;
                    }
                    
                    $__kata__ = $this->deleteDerivationSuffixes($__kata);
                    if ($this->Document_Model->cekKataDasar($__kata__)) 
                    {
                        return $__kata__;
                    }
                    
                    $__kata = preg_replace('/^(meng)/i', 'k', $kata);
                    if ($this->Document_Model->cekKataDasar($__kata)) 
                    {
                        return $__kata;
                    }
                    
                    $__kata__ = $this->deleteDerivationSuffixes($__kata);
                    if ($this->Document_Model->cekKataDasar($__kata__)) 
                    {
                        return $__kata__;
                    }
                }

                if (preg_match('/^(meny)/i', $kata)) 
                {
                    $__kata = preg_replace('/^(meny)/i', 's', $kata);
                    if ($this->Document_Model->cekKataDasar($__kata)) 
                    {
                        return $__kata;
                    }
                    
                    $__kata__ = $this->deleteDerivationSuffixes($__kata);
                    if ($this->Document_Model->cekKataDasar($__kata__)) 
                    {
                        return $__kata__;
                    }
                }
                
                if (preg_match('/^(mem)[bfpv]/i', $kata)) 
                { // 3.
                    $__kata = preg_replace('/^(mem)/i', '', $kata);

                    if ($this->Document_Model->cekKataDasar($__kata)) 
                    {
                        return $__kata; // Jika ada balik
                    }
                    
                    $__kata__ = $this->deleteDerivationSuffixes($__kata);
                    
                    if ($this->Document_Model->cekKataDasar($__kata__)) 
                    {
                        return $__kata__;
                    }
                    
                    $__kata = preg_replace('/^(mem)/i', 'p', $kata);
                    if ($this->Document_Model->cekKataDasar($__kata)) 
                    {
                        return $__kata; // Jika ada balik
                    }
                    
                    $__kata__ = $this->deleteDerivationSuffixes($__kata);
                    if ($this->Document_Model->cekKataDasar($__kata__)) 
                    {
                        return $__kata__;
                    }

                    $__kata = preg_replace('/^(mempek)/i', 'k', $kata);
                    if ($this->Document_Model->cekKataDasar($__kata)) 
                    {
                        return $__kata; // Jika ada balik
                    }
                    
                    $__kata__ = $this->deleteDerivationSuffixes($__kata);
                    if ($this->Document_Model->cekKataDasar($__kata__)) 
                    {
                        return $__kata__;
                    }
                }
                
                if (preg_match('/^(men)[cdjsz]/i', $kata)) 
                {
                    $__kata = preg_replace('/^(men)/i', '', $kata);
                    if ($this->Document_Model->cekKataDasar($__kata)) 
                    {
                        return $__kata; // Jika ada balik
                    }
                    
                    $__kata__ = $this->deleteDerivationSuffixes($__kata);
                    if ($this->Document_Model->cekKataDasar($__kata__)) 
                    {
                        return $__kata__;
                    }
                }
                
                if (preg_match('/^(me)/i', $kata)) 
                {
                    $__kata = preg_replace('/^(me)/i', '', $kata);
                    if ($this->Document_Model->cekKataDasar($__kata)) 
                    {
                        return $__kata; // Jika ada balik
                    }
                    
                    $__kata__ = $this->deleteDerivationSuffixes($__kata);
                    if ($this->Document_Model->cekKataDasar($__kata__)) 
                    {
                        return $__kata__;
                    }
                    
                    $__kata = preg_replace('/^(men)/i', 't', $kata);
                    if ($this->Document_Model->cekKataDasar($__kata)) 
                    {
                        return $__kata; // Jika ada balik
                    }
                    
                    $__kata__ = $this->deleteDerivationSuffixes($__kata);
                    if ($this->Document_Model->cekKataDasar($__kata__)) 
                    {
                        return $__kata__;
                    }

                    $__kata = preg_replace('/^(mem)/i', 'p', $kata);
                    if ($this->Document_Model->cekKataDasar($__kata)) 
                    {
                        return $__kata; // Jika ada balik
                    }
                    
                    $__kata__ = $this->deleteDerivationSuffixes($__kata);
                    if ($this->Document_Model->cekKataDasar($__kata__)) 
                    {
                        return $__kata__;
                    }
                }
            }

            if (preg_match('/^(be)/i', $kata)) 
            {
                if (preg_match('/^(ber)[aiueo]/i', $kata)) 
                {
                    $__kata = preg_replace('/^(ber)/i', '', $kata);
                    if ($this->Document_Model->cekKataDasar($__kata)) 
                    {
                        return $__kata;
                    }
                    
                    $__kata = preg_replace('/^(ber)/i', 'r', $kata);
                    if ($this->Document_Model->cekKataDasar($__kata)) 
                    {
                        return $__kata; // Jika ada balik
                    }
                    
                    $__kata__ = $this->deleteDerivationSuffixes($__kata);
                    if ($this->Document_Model->cekKataDasar($__kata__)) 
                    {
                        return $__kata__;
                    }
                }

                if (preg_match('/(ber)[^aiueo]/i', $kata)) 
                { // 2.
                    $__kata = preg_replace('/(ber)/i', '', $kata);
                    if ($this->Document_Model->cekKataDasar($__kata)) 
                    {
                        return $__kata;
                    }
                    
                    $__kata__ = $this->deleteDerivationSuffixes($__kata);
                    if ($this->Document_Model->cekKataDasar($__kata__)) {
                        return $__kata__;
                    }
                }
                if (preg_match('/^(be)[k]/i', $kata)) 
                {
                    $__kata = preg_replace('/^(be)/i', '', $kata);
                    if ($this->Document_Model->cekKataDasar($__kata)) 
                    {
                        return $__kata; // Jika ada balik
                    }
                    
                    $__kata__ = $this->deleteDerivationSuffixes($__kata);
                    if ($this->Document_Model->cekKataDasar($__kata__)) 
                    {
                        return $__kata__;
                    }
                }
            }
            
            if (preg_match('/^(pe)/i', $kata)) 
            {
                if (preg_match('/^(peng)[aiueokghq]/i', $kata)) 
                {
                    $__kata = preg_replace('/^(peng)/i', '', $kata);
                    if ($this->Document_Model->cekKataDasar($__kata)) 
                    {
                        return $__kata; // Jika ada balik
                    }
                    
                    $__kata__ = $this->deleteDerivationSuffixes($__kata);
                    if ($this->Document_Model->cekKataDasar($__kata__)) 
                    {
                        return $__kata__;
                    }
                }

                if (preg_match('/^(peny)/i', $kata)) 
                {
                    $__kata = preg_replace('/^(peny)/i', 's', $kata);
                    if ($this->Document_Model->cekKataDasar($__kata)) 
                    {
                        return $__kata; // Jika ada balik
                    }
                    
                    $__kata__ = $this->deleteDerivationSuffixes($__kata);
                    if ($this->Document_Model->cekKataDasar($__kata__)) 
                    {
                        return $__kata__;
                    }
                }
                
                if (preg_match('/^(pem)[bfpv]/i', $kata)) 
                {
                    $__kata = preg_replace('/^(pem)/i', '', $kata);
                    if ($this->Document_Model->cekKataDasar($__kata)) 
                    {
                        return $__kata; // Jika ada balik
                    }

                    $__kata__ = $this->deleteDerivationSuffixes($__kata);
                    if ($this->Document_Model->cekKataDasar($__kata__)) 
                    {
                        return $__kata__;
                    }
                }
                
                if (preg_match('/^(pen)[cdjsz]/i', $kata)) 
                {
                    $__kata = preg_replace('/^(pen)/i', '', $kata);
                    if ($this->Document_Model->cekKataDasar($__kata)) 
                    {
                        return $__kata; // Jika ada balik
                    }
                    
                    $__kata__ = $this->deleteDerivationSuffixes($__kata);
                    if ($this->Document_Model->cekKataDasar($__kata__)) 
                    {
                        return $__kata__;
                    }
                    
                    $__kata = preg_replace('/^(pem)/i', 'p', $kata);
                    if ($this->Document_Model->cekKataDasar($__kata)) 
                    {
                        return $__kata; // Jika ada balik
                    }
                }

                if (preg_match('/^(pen)[aiueo]/i', $kata)) 
                {
                    $__kata = preg_replace('/^(pen)/i', 't', $kata);
                    if ($this->Document_Model->cekKataDasar($__kata)) 
                    {
                        return $__kata; // Jika ada balik
                    }
                    
                    $__kata__ = $this->deleteDerivationSuffixes($__kata);
                    if ($this->Document_Model->cekKataDasar($__kata__)) 
                    {
                        return $__kata__;
                    }
                }
                
                if (preg_match('/^(per)/i', $kata)) 
                {
                    $__kata = preg_replace('/^(per)/i', '', $kata);
                    if ($this->Document_Model->cekKataDasar($__kata)) 
                    {
                        return $__kata; // Jika ada balik
                    }
                    
                    $__kata__ = $this->deleteDerivationSuffixes($__kata);
                    if ($this->Document_Model->cekKataDasar($__kata__)) 
                    {
                        return $__kata__;
                    }
                    
                    $__kata = preg_replace('/^(per)/i', 'r', $kata);
                    if ($this->Document_Model->cekKataDasar($__kata)) 
                    {
                        return $__kata; // Jika ada balik
                    }
                    
                    $__kata__ = $this->deleteDerivationSuffixes($__kata);
                    if ($this->Document_Model->cekKataDasar($__kata__)) 
                    {
                        return $__kata__;
                    }
                }
                
                if (preg_match('/^(pe)/i', $kata)) 
                {
                    $__kata = preg_replace('/^(pe)/i', '', $kata);
                    if ($this->Document_Model->cekKataDasar($__kata)) 
                    {
                        return $__kata; // Jika ada balik
                    }
                    
                    $__kata__ = $this->deleteDerivationSuffixes($__kata);
                    if ($this->Document_Model->cekKataDasar($__kata__)) 
                    {
                        return $__kata__;
                    }
                }
            }

            if (preg_match('/^(memper)/i', $kata)) 
            {
                $__kata = preg_replace('/^(memper)/i', '', $kata);
                if ($this->Document_Model->cekKataDasar($__kata)) 
                {
                    return $__kata; // Jika ada balik
                }
                
                $__kata__ = $this->deleteDerivationSuffixes($__kata);
                if ($this->Document_Model->cekKataDasar($__kata__)) 
                {
                    return $__kata__;
                }
                
                $__kata = preg_replace('/^(memper)/i', 'r', $kata);
                if ($this->Document_Model->cekKataDasar($__kata)) 
                {
                    return $__kata; // Jika ada balik
                }
                
                $__kata__ = $this->deleteDerivationSuffixes($__kata);
                if ($this->Document_Model->cekKataDasar($__kata__)) 
                {
                    return $__kata__;
                }
            }
        }
        
        /* --- Cek Ada Tidaknya Prefik/Awalan ------ */
        if (preg_match('/^(di|[kstbmp]e)/i', $kata) == FALSE) 
        {
            return $kataAsal;
        }
        
    }
    
    function checkPrefixDisallowedSuffixes($kata) 
    {
        // be- dan -i
        if (preg_match('/^(be)[[:alpha:]]+(i)$/i', $kata)) 
        {
            return true;
        }
        
        // di- dan -an
        if (preg_match('/^(di)[[:alpha:]]+(an)$/i', $kata)) 
        {
            return true;
        }
        
        // ke- dan -i,-kan
        if (preg_match('/^(ke)[[:alpha:]]+(i|kan)$/i', $kata)) 
        {
            return true;
        }
        
        // me- dan -an
        if (preg_match('/^(me)[[:alpha:]]+(an)$/i', $kata)) 
        {
            return true;
        }
        
        // se- dan -i,-kan
        if (preg_match('/^(se)[[:alpha:]]+(i|kan)$/i', $kata)) 
        {
            return true;
        }
        
        return FALSE;
    }

    function trim_content()
    {
        $content = $this->Document_Model->select_db('document_content, document_id', 'document');
        $limit = count($content);
        for($i = 0; $i < $limit; $i++)
        {
            //remove all symbols
            $trimmed_text[$i] = preg_replace('/[^\p{L}\p{N}\s]/u', ' ', $content[$i]['document_content']);
            //remove space at first sentence
            $trimmed_text[$i] = trim($trimmed_text[$i]);
            //remove double space
            $trimmed_text[$i] = preg_replace('/\s+/', ' ',$trimmed_text[$i]);

            //update dbase
            $this->Document_Model->update_content($trimmed_text[$i], $content[$i]['document_id']);
        }
        var_dump($trimmed_text);
    }

    //AGGREGATION PROCESS
    function aggregate_documents()
    {
        //jpnn document.id > 132
        $content = $this->Document_Model->select_db('kataPenting', 'document', 'document_id >', 132);
        $limit = count($content);

        $dataArray = $array = $this->Document_Model->select_db('aggregation_word', 'aggregation');
        $i = 0;
        foreach($dataArray as $da)
        {
            $array[$i] = $da['aggregation_word'];
            $i++;
        }
        $array = json_encode($array);

        $data_array[0] = '';
        for($i = 0; $i < $limit; $i++)
        {
            $array = json_decode($array);
            $data_array = array_unique(array_merge($data_array, $array));

            if($i+1 != $limit)
                $array = $content[$i+1]['kataPenting'];
            else
                break;
        }
        // sort($data_array);

        //tambah aggregation dari portal baru
        $data_array = $this->tools->reconstructArray($data_array);
        $j = 0;
        for($i = 0; $i < count($data_array); $i++)
        {
            //jpnn aggergation > 3228
            if($i > 3228)
            {
                $result_array[$j] = $data_array[$i];
                $j++;
            }
        }
        echo '<pre>';
        print_r($data_array);
        echo '</pre>';
        // $this->Document_Model->insert_aggregationWords('aggregation', $result_array);
    }

    function viewKataPenting()
    {
        $i = 0;
        $content = $this->Document_Model->select_db('kataPenting', 'document');
        foreach($content as $x)
        {
            echo $i.' = '.$x['kataPenting'].'<br/>';
            $i++;
        }
    }
    function testMerge()
    {
        //count spesific data on array
        $z = "saya suka makan 'say'";
        // $z = array('saya', 'saya', 'suka');
        // $counts = array_count_values($z);
        var_dump($z);
        var_dump(substr_count($z, "'say'"));
    }

    function count_aggregation_value()
    {
        $content = $this->Document_Model->select_db('document_id, kataPenting', 'document');
        $aggr = $this->Document_Model->select_db('aggregation_id, aggregation_word', 'aggregation');
        $j = 0;
        $array = array();
        $this->optimize_code();

        foreach($content as $x)
        {
            $i = 0;
            foreach($aggr as $y)
            {
                // $count[$i] = substr_count($x['kataPenting'], '"'.$y['aggregation_word'].'"');
                $array[$i]['document_id'] = $x['document_id'];
                $array[$i]['value'] = substr_count($x['kataPenting'], '"'.$y['aggregation_word'].'"');
                $array[$i]['aggregation_id'] = $y['aggregation_id'];
                $i++;
            }
            $this->Document_Model->insert_aggregationValue($array);
            $j++;
        }
        echo $j;
    }

    function update_count_aggregation_value()
    {
        $old_content    = $this->Document_Model->select_db('document_id, kataPenting', 'document', 'document_id <', 132);
        $new_content    = $this->Document_Model->select_db('document_id, kataPenting', 'document', 'document_id >', 132);
        $all_aggr       = $this->Document_Model->select_db('aggregation_id, aggregation_word', 'aggregation');
        $new_aggr       = $this->Document_Model->select_db('aggregation_id, aggregation_word', 'aggregation', 'aggregation_id >', 3228);
        $array = array();
        $this->optimize_code();
        var_dump($old_content);
        
        /*//tambah aggregation value hanya berdasarkan document baru dari semua aggregasi
        foreach($new_content as $x)
        {
            $i = 0;
            foreach($all_aggr as $y)
            {
                // $count[$i] = substr_count($x['kataPenting'], '"'.$y['aggregation_word'].'"');
                $array[$i]['document_id'] = $x['document_id'];
                $array[$i]['value'] = substr_count($x['kataPenting'], '"'.$y['aggregation_word'].'"');
                $array[$i]['aggregation_id'] = $y['aggregation_id'];
                $i++;
            }
            $this->Document_Model->insert_aggregationValue($array);
            $j++;
        }
        */

        /*//tambah aggregation value berdasarkan document lama dari aggregasi terbaru
        foreach($old_content as $x)
        {
            $i = 0;
            foreach($new_aggr as $y)
            {
                // $count[$i] = substr_count($x['kataPenting'], '"'.$y['aggregation_word'].'"');
                $array[$i]['document_id'] = $x['document_id'];
                $array[$i]['value'] = substr_count($x['kataPenting'], '"'.$y['aggregation_word'].'"');
                $array[$i]['aggregation_id'] = $y['aggregation_id'];
                $i++;
            }
            $this->Document_Model->insert_aggregationValue($array);
            $j++;
        }
        */
    }

    function count_cValue()
    {
        $document_id = $this->Document_Model->select_db('document_id', 'document');
        // $get_total_value = $this->Document_Model->sumValue($document_id[1]['document_id']);
        foreach($document_id as $data)
        {
            $get_total_value = $this->Document_Model->sumValue($data['document_id']);
            $get_positive_value = $this->Document_Model->sumPositiveValue($data['document_id']);
            $get_negative_value = $this->Document_Model->sumNegativeValue($data['document_id']);
            if(is_null($get_total_value))
                $get_total_value = 0;
            elseif(is_null($get_positive_value))
                $get_positive_value = 0;
            elseif(is_null($get_negative_value))
                $get_negative_value = 0;
            echo $data['document_id'].' =  Total: '.$get_total_value.', Positif: '.$get_positive_value.', Negatif: '.$get_negative_value.'<br/>';
            $this->Document_Model->update_value($data['document_id'], $get_total_value, $get_positive_value, $get_negative_value);
        }
    }

    function summarize()
    {
        echo date("Y-m-d H:i:s").'<br/>';
        $i = 0;
        $this->optimize_code();

        //aggregation value JPNN > 426096
        $aggregation = $this->Document_Model->select_db('*', 'aggregation_value', 'value_id >=', 539572);
        foreach($aggregation as $aggr)
        {
            if($aggr['value_id'] == 544111)
                break;
            $cvalue = $this->Document_Model->get_cvalue('c_value', $aggr['document_id']);
            $posValue = $this->Document_Model->get_cvalue('positive_value', $aggr['document_id']);
            $negValue = $this->Document_Model->get_cvalue('negative_value', $aggr['document_id']);

            $summarize_value = $aggr['value'] * $cvalue;
            $summarize_positive_value = $aggr['value'] * $posValue;
            $summarize_negative_value = $aggr['value'] * $negValue;
            echo $aggr['value_id'].'<br/>';
            // echo $aggr['value_id'].' ___ '.$aggr['value'].' * '.$cvalue.' = '.$summarize_value.'<br/>';
            // echo $aggr['value_id'].' ___ '.$aggr['value'].' * '.$posValue.' = '.$summarize_positive_value.'<br/>';
            // echo $aggr['value_id'].' ___ '.$aggr['value'].' * '.$negValue.' = '.$summarize_negative_value.'<br/><br/>';
            $this->Document_Model->update_sumAggr($summarize_value, $summarize_positive_value, $summarize_negative_value, $aggr['value_id']);
        }
        echo '<br/>'.date("Y-m-d H:i:s");
    }

    function testing()
    {
        $this->db->where('value_id', '110919');
        $query = $this->db->get('aggregation_value');
        $data1 = $query->row_array();
        var_dump($data1);

        $this->db->where('document_id', $data1['document_id']);
        $query = $this->db->get('document');
        $data2 = $query->row_array();
        var_dump($data2);

        $summ = $data1['value'] * $data2['c_value'];
        var_dump($summ);
    }
}