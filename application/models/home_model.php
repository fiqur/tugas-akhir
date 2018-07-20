<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home_model extends MY_Model
{
	function getAllData($cat)
    {
    	if($cat == 'document')
    	{
    		$this->db->join('document_links', 'document_links.link_id = document.link_id');
    		$this->db->join('portals', 'document.portal_id = portals.portal_id');
    	}
        if($cat == 'comment')
        {
            $this->db->join('document', 'document.document_id = comment.document_id');
            $this->db->join('document_links', 'document_links.link_id = document.link_id');
        }
        $query = $this->db->get($cat);
        return $query->result_array();
    }
    function getSpecificData($field, $table, $key)
    {
        $this->db->where($field, $key);
        $query = $this->db->get($table);
        return $query->result_array();
    }
    function getPortalList()
    {
        $query = $this->db->get('portals');
        return $query->result_array();
    }
    function getDetailedData($field, $whereField, $table, $key)
    {
        $this->db->select($field);
        $this->db->where($whereField, $key);
        $query = $this->db->get($table);
        return $query->result_array();
    }
    function getDetailDataIn($field, $table, $array)
    {
        $this->db->where_in($field, $array);
        $query = $this->db->get($table);
        return $query->result_array();
    }
    function getAllToko()
    {
        $this->db->distinct();
        $this->db->select('lapak');
        $query = $this->db->get('barang');
        foreach ($query->result() as $row)
        {
            $data[] = $row->lapak;
        }
        return $data;
    }
    function getGender($lapak)
    {
        $gender = '';
        $this->db->distinct();
        $this->db->select('gender');
        $this->db->from('barang');
        $this->db->where('lapak', $lapak);
        $query = $this->db->get();
        $gender.="<option value='0'>-- Pilih Gender --</option>";
        foreach ($query->result_array() as $data ){
            $gender.= "<option value='$data[gender]'>$data[gender]</option>";
        }

        return $gender;
    }
    function getKategori($lapak, $gender)
    {
        $kategori = '';
        $this->db->distinct();
        $this->db->select('kategori');
        $this->db->from('barang');
        $this->db->where('lapak', $lapak);
        $this->db->where('gender', $gender);
        $query = $this->db->get();
        $kategori.="<option value='0'>-- Pilih Kategori --</option>";
        foreach ($query->result_array() as $data ){
            $kategori.= "<option value='$data[kategori]'>$data[kategori]</option>";
        }

        return $kategori;
    }
    function getBarang($param){
        $this->db->distinct();
        $this->db->select('id_barang');
        $this->db->from('barang');
        $this->db->where('lapak', $param['lapak']);
        $this->db->where('gender', $param['gender']);
        $this->db->where('kategori', $param['kategori']);
        $query = $this->db->get();

        if (!empty($query->result())){
            foreach ($query->result() as $row)
            {
                $data[] = $row->id_barang;
            }
        }else{
            $data = NULL;
        }
        return $data;
    }

    function getTrendTahunan($idBarang){

//      foreach ($idBarang as $key => $value){
//            $this->db->select('YEAR(feedback.tanggal) AS tahun, barang.nama_barang, SUM(feedback.score) AS jumlah_tahunan');
//            $this->db->from('feedback');
//            $this->db->join('barang', 'feedback.id_barang = barang.id_barang');
//            $this->db->where('feedback.id_barang', $value);
//            $this->db->group_by('YEAR(feedback.tanggal)');
//            $this->db->order_by('feedback.tanggal', 'desc');
//            $this->db->limit(5);
//            $query = $this->db->get();
//
//            if (!empty($query->result())){
//                foreach ($query->result() as $row)
//                {
//                    $data['nama_barang'][$key] = $row->nama_barang;
//                    $data['tahun'][$key][] = $row->tahun;
//                    $data['jumlah_tahunan'][$key][] = $row->jumlah_tahunan;
//                }
//            }else{
//                $data = NULL;
//            }
//        }

//        var_dump($data);
//        die();

        $this->db->select('YEAR(tanggal) AS tahun, SUM(score) AS jumlah_tahunan');
        $this->db->from('feedback');
        $this->db->where_in('id_barang', $idBarang);
        $this->db->group_by('YEAR(tanggal)');
        $this->db->order_by('tanggal', 'desc');
        $this->db->limit(5);
        $query = $this->db->get();

        if (!empty($query->result())){
            foreach ($query->result() as $row)
            {
                $data['tahun'][] = $row->tahun;
                $data['jumlah_tahunan'][] = $row->jumlah_tahunan;
            }
        }else{
            $data = NULL;
        }

        return $data;
    }

    function getTrendBulanan($idBarang, $tahun){
        $this->db->select("MONTH(tanggal) AS bulan, SUM(score) AS jumlah_bulanan");
        $this->db->from('feedback');
        $this->db->where('YEAR(tanggal)', $tahun);
        $this->db->where_in('id_barang', $idBarang);
        $this->db->group_by('MONTH(tanggal)');
        $this->db->order_by('MONTH(tanggal)', 'desc');
        $query = $this->db->get();

        if (!empty($query->result())){
            foreach ($query->result() as $row)
            {
                $data['bulan'][] = $row->bulan;
                $data['jumlah_bulanan'][] = $row->jumlah_bulanan;
            }
        }else{
            $data = NULL;
        }

        return $data;
    }

    function getTrendInterval($idBarang, $start, $end){
        $this->db->select('YEAR(tanggal) AS tahun, SUM(score) AS jumlah_bulanan, CONCAT(YEAR(tanggal),'.',MONTH(tanggal)) AS bulan', FALSE);
        $this->db->from('feedback');
        $this->db->where('tanggal >=', $start);
        $this->db->where('tanggal <=', $end);
        $this->db->where_in('id_barang', $idBarang);
        $this->db->group_by('YEAR(tanggal), MONTH(tanggal)');
        $query = $this->db->get();

//        var_dump($query->result());
//        die();

        if (!empty($query->result())){
            foreach ($query->result() as $row)
            {
                if($row->bulan == $row->tahun . '1'){
                    $data['bulan'][] = 'Januari ' . $row->tahun;
                }elseif ($row->bulan == $row->tahun . '2'){
                    $data['bulan'][] = 'Februari ' . $row->tahun;
                }elseif ($row->bulan == $row->tahun . '3'){
                    $data['bulan'][] = 'Maret ' . $row->tahun;
                }elseif ($row->bulan == $row->tahun . '4'){
                    $data['bulan'][] = 'April ' . $row->tahun;
                }elseif ($row->bulan == $row->tahun . '5'){
                    $data['bulan'][] = 'Mei ' . $row->tahun;
                }elseif ($row->bulan == $row->tahun . '6'){
                    $data['bulan'][] = 'Juni ' . $row->tahun;
                }elseif ($row->bulan == $row->tahun . '7'){
                    $data['bulan'][] = 'Juli ' . $row->tahun;
                }elseif ($row->bulan == $row->tahun . '8'){
                    $data['bulan'][] = 'Agustus ' . $row->tahun;
                }elseif ($row->bulan == $row->tahun . '9'){
                    $data['bulan'][] = 'September ' . $row->tahun;
                }elseif ($row->bulan == $row->tahun . '10'){
                    $data['bulan'][] = 'Oktober ' . $row->tahun;
                }elseif ($row->bulan == $row->tahun . '11'){
                    $data['bulan'][] = 'November ' . $row->tahun;
                }elseif ($row->bulan == $row->tahun . '12'){
                    $data['bulan'][] = 'Desember ' . $row->tahun;
                }

                $data['jumlah_bulanan'][] = $row->jumlah_bulanan;
            }
        }else{
            $data = NULL;
        }

        return $data;
    }
}
