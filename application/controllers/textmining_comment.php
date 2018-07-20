<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Textmining_Comment extends CI_Controller 
{
    function __construct() 
    {
        parent::__construct();
        $this->load->model('Textmining_Model', '', TRUE);
        $this->load->model('Sentiment_Model', '', TRUE);
        $this->load->model('Comment_Model','', TRUE);
        $this->load->library('textmining');
        $this->whatfor = FALSE;
    }

    function index()
    {
        $this->whatfor = FALSE;
        //Objektif
        // $string = "saya ga mengecam jamkesmas dan buruknya kinerja";
        // $string = "kalau dihapus insentifnya, harga mobil LCGC jadi melambung naik dong. Yang untung yang sudah membeli mobil tersebut. Harganya jadi ikut mahal";

        //Positif
        // $string = "kami berbahagia dengan hadir jamkesmas, sehingga kami yang penghasilannya pas-pasan tidak khawatir dengan biaya pengobatan.";
        // $string = "SETUJU !!!! HAPUS SAJA !!!! GAK NGARUH KE LAPISAN BAWAH, YANG BELI MOBILNYA JUGA LEBIH BANYAK LAPISAN MENENGAH KE ATAS KOQ !!!! LAPISAN BAWAH, MANA MIKIRIN BELI MOBIL, BISA MAKAN MAKANAN SEHAT SAJA SUDAH BERSYUKUR.";
        // $string = "Asseekk,kalau dicabut berarti ada pengalihan Investasi dari Indonesia ke thai,malay atau Vietnam...mantab makin banyak lowongan kerja buat saudara2 kita di negara2 tersebut.";
        // $string = "Di desa cemagi, kecamatan mengwi, hubungan harmonis antara masyarakat dan usaha pariwisata di kawasan setempat telah terjalin cukup lama. Salah satu program di tahun 2011 ini adalah program kursus bahasa inggris gratis kepada para pelajar di desa cemanggi. Kursus ini diikuti oleh pelajar SD. Kursus ini di gelar di kantor Perbekel Desa Cemagi. Respon positif dan antusias pelajar sangat terlihat meskipun saat itu akhir pekan. Para pelajar tampak bersemangat menyimak arahan dari para pembimbing berkompeten di bidangnya. Koordinator program ini mengatakan, para siswa SD tersebut merupakan sebagian dari 150 siswa SD, SMP, SMA yang mengikuti kursus. Penyelenggaraan kursus tahun ini memasuki tahun ke dua atau level ke dua.";
        // $string = "Siswa-siswi kota denpasar kembali menunjukan keunggulannya dalam bidang pendidikan. Setelah siswa-siswi SMA berhasil meraih nilai UN tertinggi nasional, kini tingkat SMP, siswa-siswi denpasar kembali meraih nilai tertinggi nasional. Berdasarkan pengumuman hasil ujian nasional tingkat SMP yang diumumkan sabtu lalu, siswa SMPN 3 denpasar atas nama I Made Aditya Pramartha memperoleh nilai tertinggi 39,10. Sedangkan Putu Inda Pratiwi dari SMPN 10 dan I.B. Ari Sudewa, siswa SMP saraswati 1 denpasar, berhasil meraih peringkat ke-3 besar berdasarkan nilai akhir";
        
        //Negatif
        // $string = "Dari pemeriksaan inspektorat Kabupaten Bangli, ditemukan 50 orang pejabat yang didominasi kepala sekolah membuat rekomendasi bodong untuk meloloskan pegawai honorer disekolahnya. Akibat adanya pemeriksaan tersebut, mereka memilih menarik surat yang telah direkomendassikan sebelumnya. Hal ini disampaikan kepala inspektorat kabupaten bangle Drs. I Gede Suryawan, MM, rabu kemarin di bangli. Dari pemeriksaan inspektorat, kasek tersebut dengan polos mengakui surat yang dibuatnya itu adalah palsu agar bisa meloloskan pegawai pengabdian di sekolahnya dalam penjaringan CPNS Hal ini terkait dengan ancaman Bupati Made Gianyar bakal memasalahkan mereka yang tidak mau berkata jujur secara hukum dan administrasi. Suryawan mengatakan pihaknya telah melakukan verifkasi ke lapangan setelah menerima perintah bupati";
        // $string = "Preketekk... ngapain sj lu slama ini, ikut pemerintahn 10th ngak ada karya nyata.. org indonesia tdk pernah berdaya di negerinya sendiri, justru disuruh player di timur tengah .. dasar ada maunya.. buruk muka cermin dibanting";
        // $string = "Maraknya Grab Boy yang berkedok menjadi penjual gelang lilit kulit di kawasan pariwisata kuta mulai meresahkan. Selain mengganggu ketertiban umum, tindakan mereka juga tergolong tindak kriminal yang dikhawatirkan mencoreng citra pariwisata. Demikian diungkapkan anggota DPRD Badung asal legian I Wayan Puspa Negara, minggu kemarin. Menurut Puspa, selain memaksa dan mengganggu wisatawan yang sedang menikmati liburannya, juga melakukan aksi pencopetan. Modus operandinya anak-anak ini adalah menawakan gelang ikat kulit kepada wisman. Mereka mengerumuni wisatawan, khususnya wisatawan mancanegara, lalu diantara mereka ada yang memelas atau memaksa wisatawan untuk membeli. Di antara mereka juga ada yang membawa silet kemudian menorehkan tas wisman untuk kemudian mengambil barang-barang berharga";
        
        // $string = "Bismillah..... Apapun kampanye dan hinaan pihak Prabowo, Saya tetap pilih Jokowi-JK jadi Presiden RI 2014-2019. Jokowi-JK memang tidak banyak uang, tidak mampu beli rakyat, hanya dari rakyat biasa. Tapi Jokowi-JK jujur, merakyat, bekerja keras untuk rakyat, tak sombong, rendah hati, tidak pemarah, tak banyak orasi, tak banyak janji. Saya dan keluarga besar tetap pilih Jokowi-JK. Semoga Jokowi-JK mendapatkan dukungan dan dipilih oleh rakyat Indonesia minimal 65%. Semoga Alloh SWT mengabulkan. Amin";
        // $string = "Rhoma tidak hu malu..ternyata haus kekuasaan...dibayar berapa kamu...kemarin pingin jadi presiden sekarang bilang prabowo terbaik.....KELAUT AJA..";
        // $string = "Benar T E R L A L U benar";
        // echo $string;
        // $kata = $this->kataPenting($string);

        $this->textmining->optimize_code();
        $pos = $neg = $obj = 0;
        //ambil data JPNN
        $data = $this->Comment_Model->getAllComment_where('comment_id >', '592');
        for($i = 0; $i < count($data); $i++)
        {
            echo $data[$i]['content'].'<br/><br/>';
            $result[$i] = $this->kataPenting($data[$i]['content']);
            $this->Comment_Model->updateComment('value', $result[$i], $data[$i]['comment_id']);
            if($result[$i] > 0)
                $pos++;
            elseif($result[$i] < 0)
                $neg++;
            else
                $obj++;
            echo $result[$i].'<br/>-----------------------------------------------------------------------------<br/>';
        }
        echo 'Positif = '.$pos.'<br/>';
        echo 'Negatif = '.$neg.'<br/>';
        echo 'Netral = '.$obj.'<br/>';

        // $result = $this->kataPenting($string);
        // var_dump($result);
    }

    function result()
    {
        $this->whatfor = TRUE;
        $string = $this->input->post('text_input');
        $result = $this->kataPenting($string);

        $header['value'] = 5;
        $data['show_result'] = TRUE;
        $data['array_info'] = $result['array_info'];
        $data['array_score'] = $result['array_score'];
        $data['valued_index_array'] = $result['valued_index_array'];
        $data['score'] = $result['score'];
        $data['string'] = $string;

        // var_dump($result);
        // die();
        // echo count($result['array_score']).' '.count($result['array_info']);
        $this->load->view('header', $header);
        $this->load->view('home_test', $data);
        $this->load->view('footer');
        // var_dump($result);
    }

    function test_textmining()
    {
        if($this->session->userdata('behaviour') == 'comment')
        {
            $text = $this->session->userdata('text');
            $result = $this->kataPenting($text, 1);
            $header['value'] = 7;
            $data['result'] = $result[0];
            $data['stoplist'] = $result[1];
            $data['imbuhan'] = $result[2];
            $data['tidakBaku'] = $result[3];
            $data['text'] = $text;
            $data['get_result'] = 1;

            $this->load->view('header', $header);
            $this->load->view('home_textmining', $data);
            $this->load->view('footer');
        }
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

    function nazief($kata) 
    {
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
                        $score[$i] = $this->countLogic(intval($word_type[$i]['value']), intval($word_type[$i+1]['value']), 'before');
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

                        // pre+adj+adj
                        elseif ($this->cektype($word_type[$i+1]['type'], 'Adjektiva')) {
                            $pre_adj_adj = $this->countLogic(intval($word_type[$i+1]['value']), intval($word_type[$i]['value']), 'after');
                            $score[$i] = $this->countLogic(intval($pre_adj_adj), $word_type[$i-1]['value'], 'before');
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

                    elseif($this->cektype($word_type[$i+1]['type'], 'Adjektiva'))
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