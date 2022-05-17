<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class CustomLibrary
{
	protected $ci;

	public function __construct()
	{
        $this->ci =& get_instance();
        date_default_timezone_set('Asia/Jakarta');
	}

	public function view($content, $data = NULL)
	{
		$section = array('content' => $this->ci->load->view('content/' . $content, $data, TRUE));
		return $this->ci->load->view('section/page', $section);
	}

	public function auth($content, $data = NULL)
	{
		$section = array('content' => $this->ci->load->view('auth/' . $content, $data, TRUE));
		return $this->ci->load->view('auth/template', $section);
	}

	public function topnav($content, $data = NULL)
	{
		$section = array('content' => $this->ci->load->view('content/' . $content, $data, TRUE));
		return $this->ci->load->view('section/top_nav', $section);
	}

	# DataTables

	public function setDataTables($col_order, $col_search, $order_by)
	{
		$i = 0;
		foreach ($col_search as $row) {
			if(@$_POST['search']['value']) {

				if($i === 0) {
					$this->ci->db->group_start();
					$this->ci->db->like($row, $_POST['search']['value']);
				} else {
					$this->ci->db->or_like($row, $_POST['search']['value']);
				}

				if(count($col_search) - 1 == $i)
					$this->ci->db->group_end();
			}
			$i++;
		}
		if(@$_POST['order']) {
			$this->ci->db->order_by($col_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
		} else if(@$order_by) {
			$this->ci->db->order_by(key($order_by), $order_by[key($order_by)]);
		}
	}

	private function _getPaging()
	{
		if($this->ci->input->post('length') != -1)
		$this->ci->db->limit($this->ci->input->post('length'), $this->ci->input->post('start'));
		
		// $limit = $this->ci->input->post('length') + 1 + $this->ci->input->post('start');
		// $this->ci->db->limit($limit);
	}

	private $resultSet;

	public function getResult($bulider)
	{
		$this->_getPaging();
		$this->resultSet = $bulider;
		return $this->ci->db->get()->result();
	}

	#End DataTables

	public function datetime($date)
	{
	    if ($date) {
	        $datetime = $date;
	    } else {
	    	return '-';
	    }

	    $moths = array("Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember");

	    $year 		= substr($datetime, 0, 4);
	    $month 		= substr($datetime, 5, 2);
	    $date  	 	= substr($datetime, 8, 2);
	    $hour   	= substr($datetime, 11, 2);
	    $minute   	= substr($datetime, 14, 2);
	    $second   	= substr($datetime, 17, 2);
	    $substr	= substr($date, 0, 1) == 0 ? substr($date, 1) : $date;

	    if ($hour) {
		    $result  = $substr . " " . $moths[(int) $month - 1] . " " . $year . " " . $hour . ":" . $minute . ":" . $second;
	    } else {
		    $result  = $substr . " " . $moths[(int) $month - 1] . " " . $year;
	    }

	    return ($result);
	}

	public function date($datetime)
	{
		if ($datetime) {
		    $date = $datetime;
		} else {
			return '-';
		}

	    $moths = array("Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember");

	    $year 	= substr($date, 0, 4);
	    $moth 	= substr($date, 5, 2);
	    $date 	= substr($date, 8, 2);

	    $substr	= substr($date, 0, 1) == 0 ? substr($date, 1) : $date;

	    $result = $substr . " " . $moths[(int) $moth - 1] . " " . $year;
	    return ($result);
	}

	public function null($value)
	{
		return $value ? $value : '-';
	}

	public function image($image)
	{
		return $image ? $image : 'blank.png';
	}

	public function rupiah($value)
	{
		return  '<p style="text-align: right;">' . "Rp" . number_format($value, 0, ",", ".") . '</p>';
	}

	public function nama_gelar($id_pengguna)
	{
		$query = $this->ci->db->get_where('pengguna', ['id_pengguna' => $id_pengguna])->row();

		// $gelar_depan 		= $query->gelar_depan ? $query->gelar_depan . ' ' : '';
		// $gelar_belakang 	= $query->gelar_belakang ? ', ' . $query->gelar_belakang : '';
		// $nama_lengkap 		= $query->id_pengguna ? $gelar_depan . $query->nama_lengkap . $gelar_belakang : '';

		return $this->null($query->nama_lengkap);
	}

	public function konversi_nilai($skor)
	{
		if ($skor >= 85) {
			return array(
				'angka' => '4,00',
				'huruf'	=> 'A',
			);
		} elseif ($skor >= 80) {
			return array(
				'angka' => '3,70',
				'huruf'	=> 'A-',
			);
		} elseif ($skor >= 75) {
			return array(
				'angka' => '3,30',
				'huruf'	=> 'B+',
			);
		} elseif ($skor >= 70) {
			return array(
				'angka' => '3,00',
				'huruf'	=> 'B',
			);
		} elseif ($skor >= 65) {
			return array(
				'angka' => '2,70',
				'huruf'	=> 'B-',
			);
		} elseif ($skor >= 60) {
			return array(
				'angka' => '2,30',
				'huruf'	=> 'C+',
			);
		} elseif ($skor >= 55) {
			return array(
				'angka' => '2,00',
				'huruf'	=> 'C',
			);
		} elseif ($skor >= 50) {
			return array(
				'angka' => '1,70',
				'huruf'	=> 'C-',
			);
		} elseif ($skor >= 40) {
			return array(
				'angka' => '1,00',
				'huruf'	=> 'D',
			);
		} else {
			return array(
				'angka' => '0,00',
				'huruf'	=> 'E',
			);
		}
	}

	public function days($day)
	{
		switch ($day) {
	        case '0':
	            return "Minggu";
	            break;
	        case '1':
	            return "Senin";
	            break;
	        case '2':
	            return "Selasa";
	            break;
	        case '3':
	            return "Rabu";
	            break;
	        case '4':
	            return "Kamis";
	            break;
	        case '5':
	            return "Jumat";
	            break;
	        case '6':
	            return "Sabtu";
	            break;
	        default:
	            return "-";
	            break;
	    }
	}

	public function jenis_kelamin($jk)
	{
		if ($jk == 'L') {
		  $jenis_kelamin = 'Laki-Laki';
		} elseif ($jk == 'P') {
		  $jenis_kelamin = 'Perempuan';
		} else {
		  $jenis_kelamin = '-';
		}

		return $jenis_kelamin;
	}

}

/* End of file CustomLibrary.php */
/* Location: ./application/libraries/CustomLibrary.php */
