<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Tanggal_Check extends CI_Model
{

    function date_check($bulan)
    {
        $nama_bulan = array('', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember');
        return array_search($bulan, $nama_bulan);
    }
}