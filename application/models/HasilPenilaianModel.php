<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class HasilPenilaianModel extends CI_Model {

	private $table 			= 'hasil_penilaian';
	private $columnOrder	= ['id_hasil_penilaian', NULL];
	private $columnSearch	= ['id_hasil_penilaian', 'penilaian', 'skor', 'pengguna_mhs.no_induk', 'pengguna_mhs.nama_lengkap', 'program_studi', 'pengguna_penilai.no_induk', 'pengguna_penilai.nama_lengkap'];
	private $orderBy		= ['id_hasil_penilaian' => 'DESC'];

	private function _setSelect()
	{
		$this->db->select('hasil_penilaian.*');
		$this->db->select('penilaian.*');
		$this->db->select('program_studi.program_studi');
		$this->db->select('pengguna_mhs.no_induk as nim');
		$this->db->select('pengguna_mhs.nama_lengkap as nama_mhs');
		$this->db->select('pengguna_penilai.no_induk as no_induk_penilai');
		$this->db->select('pengguna_penilai.nama_lengkap as nama_penilai');
		$this->db->select('pengguna_penilai.jenis_pengguna_id as jpi_penilai');
	}

	private function _setJoin()
	{
		$this->db->join('detail_penilaian', 'detail_penilaian.id_detail_penilaian = hasil_penilaian.detail_penilaian_id', 'left');
		$this->db->join('penilaian', 'penilaian.id_penilaian = detail_penilaian.penilaian_id', 'left');
		$this->db->join('program_studi', 'program_studi.id_program_studi = penilaian.program_studi_id', 'left');
		$this->db->join('pengguna pengguna_mhs', 'pengguna_mhs.id_pengguna = hasil_penilaian.id_pengguna_mhs', 'left');
		$this->db->join('pengguna pengguna_penilai', 'pengguna_penilai.id_pengguna = hasil_penilaian.id_pengguna_penilai', 'left');
	}

	private function _setWhere($hasil_penilaian)
	{
		$this->db->where('tahun_pelaksanaan_id', $hasil_penilaian['tahun_pelaksanaan_id']);
		$this->db->where('selesai', 1);
		$this->db->where('md5(id_pengguna_mhs)', $hasil_penilaian['id_pengguna_mhs']);
		$this->db->group_by('penilaian_id');
		$this->db->group_by('id_pengguna_mhs');
		$this->db->group_by('id_pengguna_penilai');
	}

	private function _setBuilder($hasil_penilaian)
	{
		$this->_setSelect();
		$this->_setJoin();
		$this->_setWhere($hasil_penilaian);
		$this->db->from($this->table);
		$this->include->setDataTables($this->columnOrder, $this->columnSearch, $this->orderBy);
	}

	public function getDataTables($hasil_penilaian)
	{
		$query 	= $this->include->getResult($this->_setBuilder($hasil_penilaian));
		$data 	= array();
		$start 	= $this->input->post('start');
		$no  	= $start > 0 ? $start + 1 : 1;
		foreach ($query as $field) {
			$start++;
			$row 	= array();

			$row[]  = '<p style="text-align: center;">'. $no++ .'</p>';
			$row[]  = '<p style="text-align: left;">'. $field->penilaian .'</p>';
			$row[]  = '<p style="text-align: left;">'. $field->nama_penilai .'</p>';
			$row[]  = '<p style="text-align: center;">'. $this->_total_skor($field->id_penilaian, $field->id_pengguna_mhs, $field->id_pengguna_penilai, $field->skala) .'</p>';

			$data[]	= $row;
		}

		return array(
			'draw' 				=> $this->input->post('draw'),
			'recordsTotal'		=> $this->db->count_all_results($this->table),
			'recordsFiltered' 	=> $this->db->get($this->_setBuilder($hasil_penilaian))->num_rows(),
			'data' 				=> $data, 
		);
	}

	private function _total_skor($penilaian_id, $id_pengguna_mhs, $id_pengguna_penilai, $skala)
	{
		$this->db->join('detail_penilaian', 'detail_penilaian.id_detail_penilaian = hasil_penilaian.detail_penilaian_id', 'left');
		$this->db->where('penilaian_id', $penilaian_id);
		$this->db->where('id_pengguna_mhs', $id_pengguna_mhs);
		$this->db->where('id_pengguna_penilai', $id_pengguna_penilai);
		$query = $this->db->get('hasil_penilaian')->result();

		$skor_aktual = 0;
		foreach ($query as $key) {
			$skor_aktual += $key->skor;
		}

		$skor_ideal = count($query) * $skala;
		$total_skor = $skor_aktual / $skor_ideal * 100;

		return round($total_skor);
	}

}

/* End of file HasilPenilaianModel.php */
/* Location: ./application/models/HasilPenilaianModel.php */