<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MahasiswaPembimbingModel extends CI_Model {

	private $table 			= 'kelompok_mahasiswa';
	private $columnOrder	= ['id_kelompok_mahasiswa', NULL];
	private $columnSearch	= ['id_kelompok_mahasiswa', 'no_induk', 'nama_lengkap', 'email', 'telepon'];
	private $orderBy		= ['id_kelompok_mahasiswa' => 'DESC'];

	private function _setBuilder($kelompok_sekolah_id = null)
	{
		$this->db->join('pengguna', 'pengguna.id_pengguna = kelompok_mahasiswa.pengguna_id', 'left');
		$this->db->where('kelompok_sekolah_id', $kelompok_sekolah_id);
		$this->db->where('jenis_pengguna_id', 5);
		$this->db->order_by('no_induk', 'asc');
		$this->db->from($this->table);
		$this->include->setDataTables($this->columnOrder, $this->columnSearch, $this->orderBy);
	}

	public function getDataTables($kelompok_sekolah_id)
	{
		$query 	= $this->include->getResult($this->_setBuilder($kelompok_sekolah_id));
		$data 	= array();
		$start 	= $this->input->post('start');
		$no  	= $start > 0 ? $start + 1 : 1;
		foreach ($query as $field) {
			$start++;
			$row 	= array();

			$email = $field->email ? $field->email : '-';
			$telepon = $field->telepon ? $field->telepon : '-';

			$row[]  = '<p style="text-align: center;">'. $no++ .'</p>';
			$row[]  = '<p style="text-align: center;">'. $field->no_induk .'</p>';
			$row[]  = '<p style="text-align: left;">'. $field->nama_lengkap .'</p>';
			$row[]  = '<p style="text-align: left;">'. $this->include->jenis_kelamin($field->jenis_kelamin) .'</p>';
			$row[]  = '<p style="text-align: left;">'. $email .'</p>';
			$row[]  = '<p style="text-align: left;">'. $telepon .'</p>';
			$row[]  = $this->_getButton($field);

			$data[]	= $row;
		}

		return array(
			'draw' 				=> $this->input->post('draw'),
			'recordsTotal'		=> $this->db->count_all_results($this->table),
			'recordsFiltered' 	=> count($data),
			'data' 				=> $data, 
		);

	}

	private function _getButton($field)
	{
		$button = '<div style="text-align: center;">';
		$button .= '<div class="btn-group">';
		$button .= BTN_ACTION;
		$button .= '<div class="dropdown-menu dropdown-menu-right" role="menu">';
		$button .= '<a class="dropdown-item" href="'. site_url('teacher/activity/' . md5($field->id_pengguna)) .'">Kegiatan</a>';
		$button .= '<div class="dropdown-divider"></div>';
		$button .= '<a class="dropdown-item" href="'. site_url('teacher/examination/' . md5($field->id_pengguna)) .'">Penilaian</a>';
		$button .= '</div>';
		$button .= '</div>';
		$button .= '</div>';

		return $button;
	}

}

/* End of file MahasiswaPembimbingModel.php */
/* Location: ./application/models/MahasiswaPembimbingModel.php */