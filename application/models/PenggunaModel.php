<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class PenggunaModel extends CI_Model {

	private $table 			= 'pengguna';
	private $columnOrder	= ['id_pengguna', NULL];
	private $columnSearch	= [
		'id_pengguna', 
		'no_induk',
		'nama_lengkap',
		'jenis_kelamin',
		'telepon',
		'email',
		'program_studi',
		'nama_sekolah'
	];
	private $orderBy		= ['id_pengguna' => 'DESC'];

	private function _setJoin()
	{
		$this->db->join('jenis_pengguna', 'jenis_pengguna.id_jenis_pengguna = pengguna.jenis_pengguna_id', 'left');
		$this->db->join('program_studi', 'program_studi.id_program_studi = pengguna.program_studi_id', 'left');
		$this->db->join('guru_pamong', 'guru_pamong.pengguna_id = pengguna.id_pengguna', 'left');
		$this->db->join('sekolah_mitra', 'sekolah_mitra.id_sekolah_mitra = guru_pamong.sekolah_mitra_id', 'left');
		$this->db->join('mahasiswa', 'mahasiswa.pengguna_id = pengguna.id_pengguna', 'left');
	}

	private function _setBuilder($jenis_pengguna_id)
	{
		$this->_setJoin();
		$this->db->where('jenis_pengguna_id', $jenis_pengguna_id);
		$this->db->where('status_pendaftaran', 1);
		$this->db->group_by('pengguna.id_pengguna');
		$this->db->order_by('pengguna.no_induk', 'asc');
		$this->db->from($this->table);
		$this->include->setDataTables($this->columnOrder, $this->columnSearch, $this->orderBy);
	}

	public function getDataTables($jenis_pengguna_id)
	{
		$query 	= $this->include->getResult($this->_setBuilder($jenis_pengguna_id));
		$data 	= array();
		$start 	= $this->input->post('start');
		$no  	= $start > 0 ? $start + 1 : 1;
		foreach ($query as $field) {
			$start++;
			$row 	= array();

			$no_induk				= $field->no_induk ? $field->no_induk : '-';
			$jenis_kelamin 			= $this->include->jenis_kelamin($field->jenis_kelamin);
			$program_studi  		= $field->program_studi ? $field->program_studi : '-';
			$sekolah_mitra  		= $field->nama_sekolah ? $field->nama_sekolah : '-';
			$status_pendaftaran  	= isset($field->status_pendaftaran) == 1 ? 'Aktif' : 'Menunggu<span style="color: #ffffff;">_</span>Verifikasi';
			$angkatan  				= $field->angkatan ? $field->angkatan : '-';

			// $gelar_depan 	= $field->gelar_depan ? $field->gelar_depan . ' ' : '';
			// $gelar_belakang = $field->gelar_belakang ? ', ' . $field->gelar_belakang : '';

			$row[]  = '<p style="text-align: center;">'. $no++ .'</p>';
			$row[]  = '<p style="text-align: center;">'. $no_induk .'</p>';
			$row[]	= '<p style="text-align: left;">'. $field->nama_lengkap .'</p>';

			$email = $field->email ? $field->email : '-';
			$telepon = $field->telepon ? $field->telepon : '-';

			// $row[]	= '<p style="text-align: left;">'. $gelar_depan . $field->nama_lengkap . $gelar_belakang .'</p>';

			if ($field->jenis_pengguna_id == 1) {
				// Administrator

				$row[]	= '<p style="text-align: left;">'. $email .'</p>';
				$row[]	= '<p style="text-align: left;">'. $telepon .'</p>';

			} elseif ($field->jenis_pengguna_id == 2) {
				// Dekan
				
			} elseif ($field->jenis_pengguna_id == 3) {
				// Dosen
				
				$row[]	= '<p style="text-align: left;">'. $jenis_kelamin .'</p>';
				$row[]	= '<p style="text-align: left;">'. $program_studi .'</p>';
				
			} elseif ($field->jenis_pengguna_id == 4) {
				// Guru Pamong

				$row[]	= '<p style="text-align: left;">'. $jenis_kelamin .'</p>';
				$row[]	= '<p style="text-align: left;">'. $sekolah_mitra .'</p>';

				// $row[]	= '<p style="text-align: left;">'. $program_studi .'</p>';
				
			} elseif ($field->jenis_pengguna_id == 5) {
				// Mahasiswa
				
				$row[]	= '<p style="text-align: left;">'. $jenis_kelamin .'</p>';
				$row[]	= '<p style="text-align: left;">'. $program_studi .'</p>';
				$row[]	= '<p style="text-align: center;">'. $angkatan .'</p>';
				// $row[]	= '<p style="text-align: left;">'. $status_pendaftaran .'</p>';
			}
			
			$row[]  = $this->_getButton($field);

			$data[]	= $row;
		}

		$setData = [
			'draw' 				=> $this->input->post('draw'),
			'recordsTotal'		=> $this->db->count_all_results($this->table),
			'recordsFiltered' 	=> $this->db->get($this->_setBuilder($jenis_pengguna_id))->num_rows(),
			'data' 				=> $data,
		];

		return $setData;
	}

	private function _getButton($field)
	{
		$button = '<div style="text-align: center;">';
		$button .= '<div class="btn-group">';
		$button .= BTN_ACTION;
		$button .= '<div class="dropdown-menu dropdown-menu-right" role="menu">';

		if ($field->jenis_pengguna_id == 5) {
			$button .= '<a class="dropdown-item" href="'. site_url('user/detail/' . md5($field->id_pengguna)) .'">Detail</a>';
			$button .= '<div class="dropdown-divider"></div>';
		}

		$button .= '<a class="dropdown-item" href="'. site_url('user/edit/' . md5($field->id_pengguna)) .'">Edit</a>';

		if ($field->jenis_pengguna_id != 5) {
			$button .= '<div class="dropdown-divider"></div>';
			if ($field->foto_profil) {
				$button .= '<a class="dropdown-item" href="javascript:void(0)" onclick="foto_profile(' . "'" . md5($field->id_pengguna) . "'" . ')">Lihat Foto</a>';
			} else {
				$button .= '<a class="dropdown-item" href="javascript:void(0)" onclick="upload_image(' . "'" . md5($field->id_pengguna) . "'" . ');">Upload Foto</a>';
			}
		} else {

		}
		 
		if ($this->session->id_pengguna != $field->id_pengguna) {
			$button .= '<div class="dropdown-divider"></div>';
			$button .= '<a class="dropdown-item" href="javascript:void(0)" onclick="delete_data(' . "'" . site_url('user/delete/' . md5($field->id_pengguna)) . "'" . ')">Hapus</a>';
		}

		// if ($this->session->id_pengguna == $field->id_pengguna) {
		// 	if (isset($field->foto_profil)) {
		// 		$button .= '<a class="dropdown-item" href="javascript:void(0)" onclick="delete_image(' . "'" . md5($field->id_pengguna) . "'" . ');">Hapus Foto</a>';
		// 	} else {
		// 		$button .= '<a class="dropdown-item" href="javascript:void(0)" onclick="upload_image(' . "'" . md5($field->id_pengguna) . "'" . ');">Upload Foto</a>';
		// 	}
		// }

		$button .= '</div>';
		$button .= '</div>';
		$button .= '</div>';

		$button .= '<input type="hidden" id="jenis_pengguna_id_'. md5($field->id_pengguna) .'" value="'. $field->jenis_pengguna_id .'">';
		$button .= '<input type="hidden" id="email_'. md5($field->id_pengguna) .'" value="'. $field->email .'">';
		$button .= '<input type="hidden" id="telepon_'. md5($field->id_pengguna) .'" value="'. $field->telepon .'">';
		$button .= '<input type="hidden" id="foto_profil_'. md5($field->id_pengguna) .'" value="'. $field->foto_profil .'">';
		$button .= '<input type="hidden" id="image_'. md5($field->id_pengguna) .'" value="'.  site_url(IMAGE . $field->foto_profil) .'">';

		return $button;
	}

	public function getRow($id_pengguna)
	{
		$this->_setJoin();
		$this->db->where('md5(id_pengguna)', $id_pengguna);
		return $this->db->get($this->table)->row();
	}

	public function get_result_mhs()
	{
		$this->_setJoin();
		$this->db->where('jenis_pengguna_id', 5);
		return $this->db->get($this->table)->result();
	}

	private function _getKelompokSekolah($tahun_pelaksanaan_id, $id)
	{
		$this->load->model('KelompokMahasiswaModel');
		return $this->KelompokMahasiswaModel->get_where_not_in($tahun_pelaksanaan_id, $id);
	}

	public function get_dpl($tahun_pelaksanaan_id = null, $program_studi_id = null, $id_pengguna_dpl = null)
	{
		$kelompok_sekolah 	= $this->_getKelompokSekolah($tahun_pelaksanaan_id, $program_studi_id)['program_studi'];
		$pengguna 			= $this->getRow($id_pengguna_dpl);

		if (count($kelompok_sekolah) > 0) {

			$pengguna_id = array();
			foreach ($kelompok_sekolah as $key) {
				$pengguna_id[] = $key->id_pengguna_dpl;
			}

			$this->db->where_not_in('id_pengguna', $pengguna_id);
		}

		$this->db->where('jenis_pengguna_id', 3);

		if ($program_studi_id) {
			$this->db->where('program_studi_id', $program_studi_id);
		}

		if (isset($pengguna->id_pengguna) && @$pengguna->program_studi_id == $program_studi_id) {
			$this->db->or_where('id_pengguna', $pengguna->id_pengguna);
		}

		return $this->db->get($this->table)->result_array();
	}

	public function get_gpl($tahun_pelaksanaan_id = null, $sekolah_mitra_id = null, $id_pengguna_gpl = null)
	{
		$kelompok_sekolah 	= $this->_getKelompokSekolah($tahun_pelaksanaan_id, $sekolah_mitra_id)['sekolah_mitra'];
		$pengguna 			= $this->getRow($id_pengguna_gpl);

		$pengguna_id = array();

		foreach ($kelompok_sekolah as $key) {
			if ($key->id_pengguna_gpl) {
				$pengguna_id[] = $key->id_pengguna_gpl;
			}
		}

		if (count($pengguna_id) > 0) {
			$this->db->where_not_in('id_pengguna', $pengguna_id);
		}
		
		$this->db->join('guru_pamong', 'guru_pamong.pengguna_id = pengguna.id_pengguna', 'left');

		$this->db->where('jenis_pengguna_id', 4);

		if ($sekolah_mitra_id) {
			$this->db->where('sekolah_mitra_id', $sekolah_mitra_id);
		}

		if (isset($pengguna->id_pengguna) && @$pengguna->sekolah_mitra_id == $sekolah_mitra_id) {
			$this->db->or_where('id_pengguna', $pengguna->id_pengguna);
		}

		return $this->db->get($this->table)->result_array();

	}

	public function find_registrasi($pengguna_id)
	{
		// CEK APAKAH MAHASISWA SUDAH MENYELESAIKAN PENDAFTARAN ATAU BELUM
		
		$this->db->join('pengguna', 'pengguna.id_pengguna = mahasiswa.pengguna_id', 'left');
		$this->db->where('pengguna_id', $pengguna_id);
		$this->db->where('angkatan', null);
		$this->db->where('jenis_kelamin', null);
		$this->db->where('telepon', null);
		$this->db->where('program_studi_id', null);
		return $this->db->get('mahasiswa')->num_rows();
	}

	public function getStudent($pengguna_id)
	{
		return $this->db->get_where('mahasiswa', ['md5(pengguna_id)' => $pengguna_id])->row_array();
	}

	public function _query_register()
	{
		$this->_setJoin();
		$this->db->where('jenis_pengguna_id', 5);
		$this->db->where('status_pendaftaran', NULL);
		$this->db->where('program_studi_id !=', NULL);
		$this->db->group_by('pengguna.id_pengguna');
		$this->db->order_by('pengguna.id_pengguna', 'asc');
		$this->db->from($this->table);
		$this->include->setDataTables($this->columnOrder, $this->columnSearch, $this->orderBy);
	}

	public function get_register()
	{
		return array(
			'result' => $this->include->getResult($this->_query_register()),
			'count'	 => $this->db->count_all_results($this->table),
			'rows'	 => $this->db->get($this->_query_register())->num_rows()
		);
	}

}

/* End of file PenggunaModel.php */
/* Location: ./application/models/PenggunaModel.php */