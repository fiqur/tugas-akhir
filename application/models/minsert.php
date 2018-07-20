<?php if(!defined('BASEPATH'))exit('Hacking Attemp : Jangan ganggu ane bro, ane juga lagi nyoba ngehack -proses-'); // ga usah peduliin
 
class Minsert extends CI_model{ // nama kelas normalnya huruf pertama pake kapital, walaupun nama filenya ngga
 
 
        function addInsert($gender, $kategori,$url_site,$harga,$url_img, $nama_barang,$lapak,$total_score){ // buat fungsi namannya add transaksi          
            $data = array( //array yang buat masukin data
                        'url_img'=>$url_img,
                        'nama_barang'=>$nama_barang,
                        'harga' => $harga,
                        'url_site'=>$url_site,
                        'gender'=>$gender, // yang ditangkep dimasukin
                        'kategori'=>$kategori, // yang ditangkep dimasukin              
                        'lapak'=>$lapak,
                        'total_score'=>$total_score
            );
            $this->db->insert('barang',$data); // ini inti prosenya, yaitu masukin arrray data ke tabel transaksi
            return $this->db->insert_id();
        }

        function updateInsert($id_barang,$total_score){ // buat fungsi namannya add transaksi          
            $data = array( //array yang buat masukin data
                        // 'url_img'=>$url_img,
                        // 'nama_barang'=>$nama_barang,
                        // 'harga' => $harga,
                        // 'url_site'=>$url_site,
                        // 'gender'=>$gender, // yang ditangkep dimasukin
                        // 'kategori'=>$kategori, // yang ditangkep dimasukin              
                        // 'lapak'=>$lapak,
                        'total_score'=>$total_score
            );
            $this->db->where('id_barang', $id_barang);
            $this->db->update('barang', $data); 
            return TRUE;
        }

        // function addFeedback($feedback_product, $id_barang,$kata_penting){ // buat fungsi namannya add transaksi
          
        //     $data1 = array( //array yang buat masukin data
        //                 'feedback_product'=>$feedback_product,
        //                 'id_barang'=>$id_barang,
        //                 'kata_penting'=>$kata_penting

        //         );
        //     $this->db->insert('feedback',$data1); // ini inti prosenya, yaitu masukin arrray data ke tabel transaksi
        // }
        function addData($feedback_product, $id_barang, $kata_penting, $tanggal, $score){
             $data1 = array( //array yang buat masukin data
                        'feedback_product'=>$feedback_product,
                        'id_barang'=>$id_barang,
                        'kata_penting'=>$kata_penting,
                        'tanggal'=>$tanggal,
                        'score'=>$score
                );
             $this->db->insert('feedback',$data1);
        }

        // function addTanggal($tanggal){ // buat fungsi namannya add transaksi
          
        //     $data1 = array( //array yang buat masukin data
        //         'tanggal'=>$tanggal
        //     );
        //     $this->db->insert('feedback',$data1); // ini inti prosenya, yaitu masukin arrray data ke tabel transaksi
        // }

}