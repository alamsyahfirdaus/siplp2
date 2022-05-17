<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class KelompokMahasiswaModel extends CI_Model {

	private $table 			= 'kelompok_sekolah';
	private $primaryKey		= 'md5(id_kelompok_sekolah)';
	private $columnOrder	= ['kelompok_sekolah.id_kelompok_sekolah', NULL];
	private $columnSearch	= ['kelompok_sekolah.id_kelompok_sekolah', 'tahun_pelaksanaan.tahun_pelaksanaan', 'program_studi.program_studi', 'sekolah_mitra.nama_sekolah', 'pengguna_dpl.nama_lengkap', 'pengguna_gpl.nama_lengkap'];
	private $orderBy		= ['kelompok_sekolah.id_kelompok_sekolah' => 'DESC'];

	private function _setJoin()
	{
		$this->db->join('tahun_pelaksanaan', 'tahun_pelaksanaan.id_tahun_pelaksanaan = kelompok_sekolah.tahun_pelaksanaan_id', 'left');
		$this->db->join('program_studi', 'program_studi.id_program_studi = kelompok_sekolah.program_studi_id', 'left');
		$this->db->join('sekolah_mitra', 'sekolah_mitra.id_sekolah_mitra = kelompok_sekolah.sekolah_mitra_id', 'left');
		$this->db->join('pengguna pengguna_dpl', 'pengguna_dpl.id_pengguna = kelompok_sekolah.id_pengguna_dpl', 'left');
		$this->db->join('pengguna pengguna_gpl', 'pengguna_gpl.id_pengguna = kelompok_sekolah.id_pengguna_gpl', 'left');
	}

	public function _setSelect()
	{
		$this->db->select('kelompok_sekolah.*');
		$this->db->select('tahun_pelaksanaan.tahun_pelaksanaan');
		$this->db->select('program_studi.program_studi');
		$this->db->select('sekolah_mitra.nama_sekolah');
		$this->db->select('pengguna_dpl.nama_lengkap as nama_dpl');
		// $this->db->select('pengguna_dpl.gelar_depan as gelar_depan_dpl');
		// $this->db->select('pengguna_dpl.gelar_belakang as gelar_belakang_dpl');
		$this->db->select('pengguna_gpl.nama_lengkap as nama_gpl');
		// $this->db->select('pengguna_gpl.gelar_depan as gelar_depan_gpl');
		// $this->db->select('pengguna_gpl.gelar_belakang as gelar_belakang_gpl');
	}

	private function _setBuilder()
	{
		$this->_setSelect();
		$this->_setJoin();
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

			// $gelar_depan_dpl 	= $field->gelar_depan_dpl ? $field->gelar_depan_dpl . ' ' : '';
			// $gelar_belakang_dpl = $field->gelar_belakang_dpl ? ', ' . $field->gelar_belakang_dpl : '';
			// $nama_dpl 			= $gelar_depan_dpl . $field->nama_dpl . $gelar_belakang_dpl;

			// $gelar_depan_gpl 	= $field->gelar_depan_gpl ? $field->gelar_depan_gpl . ' ' : '';
			// $gelar_belakang_gpl = $field->gelar_belakang_gpl ? ', ' . $field->gelar_belakang_gpl : '';
			// $nama_gpl 			= $field->id_pengguna_gpl ? $gelar_depan_gpl . $field->nama_gpl . $gelar_belakang_gpl : '-';

			$nama_gpl = $field->id_pengguna_gpl ? $field->nama_gpl : '-';

			$row[]  = '<p style="text-align: center;">'. $no++ .'</p>';
			// $row[]  = '<p style="text-align: center;">'. $field->tahun_pelaksanaan .'</p>';
			$row[]  = '<p style="text-align: left;">'. $field->program_studi .'</p>';
			$row[]  = '<p style="text-align: left;">'. $field->nama_sekolah .'</p>';
			$row[]  = '<p style="text-align: left;">'. $field->nama_dpl .'</p>';
			$row[]  = '<p style="text-align: left;">'. $nama_gpl  .'</p>';
			$row[]  = $this->_getButton($field);

			$data[]	= $row;
		}

		return array(
			'draw' 				=> $this->input->post('draw'),
			'recordsTotal'		=> $this->db->count_all_results($this->table),
			'recordsFiltered' 	=> $this->db->get($this->_setBuilder())->num_rows(),
			'data' 				=> $data, 
		);

	}

	private function _getButton($field)
	{
		$button = '<div style="text-align: center;">';
		$button .= '<div class="btn-group">';
		$button .= BTN_ACTION;
		$button .= '<div class="dropdown-menu dropdown-menu-right" role="menu">';
		$button .= '<a class="dropdown-item" href="'. site_url('master/setgroup/' . md5($field->id_kelompok_sekolah)) .'">Edit</a>';
		$button .= '<div class="dropdown-divider"></div>';
		$button .= '<a class="dropdown-item" href="javascript:void(0)" onclick="delete_data(' . "'" . site_url('master/delete_group/' . md5($field->id_kelompok_sekolah)) . "'" . ')">Hapus</a>';
		$button .= '</div>';
		$button .= '</div>';
		$button .= '</div>';

		return $button;
	}

	public function getData($id_kelompok_sekolah)
	{
		$this->_setSelect();
		$this->_setJoin();

		if (is_array($id_kelompok_sekolah)) {
			$query = $this->db->get_where($this->table, $id_kelompok_sekolah)->row();
		} else {
			$this->db->where($this->primaryKey, $id_kelompok_sekolah);
			$query = $this->db->get($this->table)->row();
		}


		if (isset($query->id_kelompok_sekolah)) {

			// $gelar_depan_dpl 	= $query->gelar_depan_dpl ? $query->gelar_depan_dpl . ' ' : '';
			// $gelar_belakang_dpl = $query->gelar_belakang_dpl ? ', ' . $query->gelar_belakang_dpl : '';
			// $nama_dpl 			= $query->id_pengguna_dpl ? $gelar_depan_dpl . $query->nama_dpl . $gelar_belakang_dpl : '';

			// $gelar_depan_gpl 	= $query->gelar_depan_gpl ? $query->gelar_depan_gpl . ' ' : '';
			// $gelar_belakang_gpl = $query->gelar_belakang_gpl ? ', ' . $query->gelar_belakang_gpl : '';
			// $nama_gpl 			= $query->id_pengguna_gpl ? $gelar_depan_gpl . $query->nama_gpl . $gelar_belakang_gpl : '';

			return array(
				'id_kelompok_sekolah' 	=> $query->id_kelompok_sekolah,
				'tahun_pelaksanaan_id' 	=> $query->tahun_pelaksanaan_id,
				'tahun_pelaksanaan' 	=> $query->tahun_pelaksanaan,
				'program_studi_id' 		=> $query->program_studi_id, 
				'program_studi' 		=> $query->program_studi,
				'sekolah_mitra_id' 		=> $query->sekolah_mitra_id, 
				'sekolah_mitra' 		=> $query->nama_sekolah,
				'id_pengguna_dpl' 		=> $query->id_pengguna_dpl,
				'nama_dpl' 				=> $query->nama_dpl,
				'id_pengguna_gpl' 		=> $query->id_pengguna_gpl,
				'nama_gpl' 				=> $query->nama_gpl,
			);
			
		} else {
			return false;
		}

	}

	public function get_where_not_in($tahun_pelaksanaan_id, $id)
	{
		// Mengambil Kelompok Sekolah Berdasarkan Tahun Pelaksanaan dan Program Studi atau Sekolah Mitra
		
		$program_studi = $this->db->get_where('kelompok_sekolah', [
			'tahun_pelaksanaan_id'	=> $tahun_pelaksanaan_id,
			'program_studi_id'		=> $id
		])->result();

		$sekolah_mitra = $this->db->get_where('kelompok_sekolah', [
			'tahun_pelaksanaan_id'	=> $tahun_pelaksanaan_id,
			'sekolah_mitra_id'		=> $id
		])->result();

		return array(
			'program_studi' => $program_studi,
			'sekolah_mitra' => $sekolah_mitra
		);
		
	}

	public function getResult($tahun_pelaksanaan_id = null)
	{
		$this->_setSelect();
		$this->_setJoin();
		if ($tahun_pelaksanaan_id) {
			$this->db->where('tahun_pelaksanaan_id', $tahun_pelaksanaan_id);
		}
		$this->db->order_by('id_kelompok_sekolah', 'desc');
		return $this->db->get($this->table)->result();
	}

	public function getKelompokMahasiswa($id_kelompok_sekolah, $pengguna_id = null)
	{
		$table 			= 'kelompok_mahasiswa';
		$columnOrder	= ['id_kelompok_mahasiswa', NULL];
		$columnSearch	= ['id_kelompok_mahasiswa', 'no_induk', 'nama_lengkap'];
		$orderBy		= ['id_kelompok_mahasiswa' => 'DESC'];

		if ($pengguna_id) {
			$this->db->where_not_in('pengguna_id', [$pengguna_id]);
		}
		$this->db->join('pengguna pengguna', 'pengguna.id_pengguna = kelompok_mahasiswa.pengguna_id', 'left');
		$this->db->where('md5(kelompok_sekolah_id)', $id_kelompok_sekolah);
		$this->db->order_by('no_induk', 'asc');
		$this->db->from($table);
		$result = $this->include->setDataTables($columnOrder, $columnSearch, $orderBy);

		return array(
			'result' 	=> $this->include->getResult($result),
			'count'	 	=> $this->db->count_all_results($table),
		);
	}

	public function getMahasiswa()
	{
		$table 			= 'pengguna';
		$columnOrder	= ['id_pengguna', NULL];
		$columnSearch	= ['id_pengguna', 'no_induk', 'nama_lengkap'];
		$orderBy		= ['id_pengguna' => 'DESC'];

		$pengguna_id = array();
		foreach ($this->db->get_where('kelompok_mahasiswa')->result() as $key) {
			$pengguna_id[] = $key->pengguna_id;
		}

		if (count($pengguna_id) > 0) {
			$this->db->where_not_in('id_pengguna', $pengguna_id);
		}

		$this->db->where('jenis_pengguna_id', 5);
		$this->db->where('program_studi_id', $this->input->post('program_studi_id'));
		$this->db->order_by('no_induk', 'asc');
		$this->db->from($table);
		$result = $this->include->setDataTables($columnOrder, $columnSearch, $orderBy);

		return array(
			'result' => $this->include->getResult($result),
			'count'  => $this->db->count_all_results($table)
		);
	}

	public function getKelompokSekolah()
	{
		$this->load->model('TahunPelaksanaanModel', 'year');

		$data['tahun_pelaksanaan_id'] = $this->year->getAktif();
		if ($this->session->id_jenis_pengguna == 3) {
			$data['id_pengguna_dpl'] = $this->session->id_pengguna;
		} elseif ($this->session->id_jenis_pengguna == 4) {
			$data['id_pengguna_gpl'] = $this->session->id_pengguna;
		}
		return $this->getData($data);
	}

	public function getSekolahMahasiswa($pengguna_id)
	{
		$query = $this->db->get_where('kelompok_mahasiswa', ['pengguna_id' => $pengguna_id])->row();
		$id_kelompok_sekolah = isset($query->kelompok_sekolah_id) ? md5($query->kelompok_sekolah_id) : 0;
		return $this->getData($id_kelompok_sekolah);

	}

}

/* End of file KelompokMahasiswaModel.php */
/* Location: ./application/models/KelompokMahasiswaModel.php */