<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class NilaiAkhirModel extends CI_Model {

	private $table 			= 'hasil_penilaian';
	private $columnOrder	= ['id_hasil_penilaian', NULL];
	private $columnSearch	= ['id_hasil_penilaian', 'no_induk', 'nama_lengkap', 'program_studi'];
	private $orderBy		= ['id_hasil_penilaian' => 'DESC'];

	private function _setJoin()
	{
		$this->db->join('pengguna', 'pengguna.id_pengguna = hasil_penilaian.id_pengguna_mhs', 'left');
		$this->db->join('program_studi', 'program_studi.id_program_studi = pengguna.program_studi_id', 'left');
	}

	private function _setWhere()
	{
		$this->db->where('tahun_pelaksanaan_id', $this->input->post('tahun_pelaksanaan_id'));
		if ($this->input->post('program_studi_id')) {
			$this->db->where('md5(program_studi_id)', $this->input->post('program_studi_id'));
		}
		$this->db->order_by('no_induk', 'asc');
		$this->db->group_by('id_pengguna_mhs');
	}

	private function _setBuilder()
	{
		$this->_setJoin();
		$this->_setWhere();
		$this->db->from($this->table);
		$this->include->setDataTables($this->columnOrder, $this->columnSearch, $this->orderBy);
	}

	public function getDataTables()
	{
		$query 	= $this->include->getResult($this->_setBuilder());
		$data 	= array();
		$start 	= $this->input->post('start');
		$no  	= $start > 0 ? $start + 1 : 1;
		foreach ($query as $field) {
			$start++;
			$row 	= array();

			$nilai  = $this->nilai_akhir($field->id_pengguna_mhs);

			$row[]  = '<p style="text-align: center;">'. $no++ .'</p>';
			$row[]  = '<p style="text-align: center;">'. $field->no_induk .'</p>';
			$row[]  = '<p style="text-align: left;">'. $field->nama_lengkap .'</p>';
			$row[]  = '<p style="text-align: left;">'. $field->program_studi .'</p>';
			$row[]  = '<p style="text-align: center;">'. $nilai['skor'] .'</p>';
			$row[]  = '<p style="text-align: center;">'. $nilai['angka'] .'</p>';
			$row[]  = '<p style="text-align: center;">'. $nilai['huruf'] .'</p>';
			$row[]  = $this->_getButton($field);

			$data[]	= $row;
		}

		return array(
			'draw' 				=> $this->input->post('draw'),
			'recordsTotal'		=> $this->db->count_all_results($this->table),
			'recordsFiltered' 	=> $this->db->get($this->_setBuilder())->num_rows(),
			'data' 				=> $data,
			'id_pengguna_mhs'	=> $this->_get_mahasiswa(),
		);
	}

	private function _getButton($field)
	{
		$button = '<div style="text-align: center;">';
		$button .= '<div class="btn-group">';
		$button .= BTN_ACTION;
		$button .= '<div class="dropdown-menu dropdown-menu-right" role="menu">';
		$button .= '<a class="dropdown-item" href="'. site_url('evaluation/report/' . md5($field->id_pengguna_mhs)) .'">Detail</a>';
		$button .= '</div>';
		$button .= '</div>';
		$button .= '</div>';

		return $button;
	}

	public function nilai_akhir($pengguna_id)
	{
		$query = $this->db->join('detail_penilaian', 'detail_penilaian.id_detail_penilaian = hasil_penilaian.detail_penilaian_id', 'left')->where('id_pengguna_mhs', $pengguna_id)->group_by('penilaian_id')->where('selesai', 1)->group_by('id_pengguna_penilai')->get('hasil_penilaian')->result();

		foreach ($query as $row) {
			$penilaian 			= $this->db->get_where('penilaian', ['id_penilaian' => $row->penilaian_id])->row();
			$hasil_penilaian 	= $this->db->join('detail_penilaian', 'detail_penilaian.id_detail_penilaian = hasil_penilaian.detail_penilaian_id', 'left')->select('count(detail_penilaian_id) as dpi')->select_sum('skor')->where('penilaian_id', $row->penilaian_id)->where('selesai', 1)->get('hasil_penilaian')->row();

			$penilaian_id[] = $row->penilaian_id;

			$skor_aktual[] 	= $hasil_penilaian->skor;
			$skor_ideal[] 	= $hasil_penilaian->dpi * $penilaian->skala;
		}

		$skor_akhir 	= array_sum($skor_aktual) / array_sum($skor_ideal) * 100;
		$nilai			= $this->include->konversi_nilai($skor_akhir);

		return array(
			'skor'	=> round($skor_akhir),
			'angka' => $nilai['angka'],
			'huruf' => $nilai['huruf'],
		);
	}

	private function _get_mahasiswa()
	{
		$this->_setJoin();
		$this->_setWhere();
		$this->db->order_by('no_induk', 'asc');
		$query = $this->db->get($this->table)->result();
		$id_pengguna_mhs = array();
		foreach ($query as $key) {
			$id_pengguna_mhs[] = $key->id_pengguna_mhs;
		}

		return count($id_pengguna_mhs) > 0 ? $id_pengguna_mhs : null;
	}

}

/* End of file NilaiAkhirModel.php */
/* Location: ./application/models/NilaiAkhirModel.php */