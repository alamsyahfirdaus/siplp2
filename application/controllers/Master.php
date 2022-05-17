<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Master extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		logged_in();
		$this->mall->hak_akses();

		$this->load->model('KelompokMahasiswaModel', 'group');
		$this->load->model('TahunPelaksanaanModel', 'year');
		$this->load->model('ProdiModel', 'prodi');
		$this->load->model('SekolahMitraModel', 'sekolah');
		$this->load->model('PenggunaModel', 'pengguna');

		$this->form_validation->set_error_delimiters('', '');

	}

	private $folder = 'Master';

	public function index()
	{
		redirect(site_url());
	}

	public function department()
	{
		$data = array(
			'folder'			=> $this->folder,
			'title' 			=> 'Program Studi',
		);

		$this->include->view('index_program_studi', $data);
	}

	public function show_department()
	{
		$data = $this->prodi->getDataTables();
		echo json_encode($data);
	}

	public function delete_department($id_program_studi = null)
	{
		$query = $this->db->get_where('program_studi', ['md5(id_program_studi)' => $id_program_studi])->row();
		if ($query) {
			$action = $this->db->delete('program_studi', ['id_program_studi' => $query->id_program_studi]);
			echo json_encode(['status' => $action]);
		} else {
			redirect(site_url());
		}
	}

	public function save_department()
	{
		$query = $this->db->get_where('program_studi', ['md5(id_program_studi)' => $this->input->post('id_program_studi')])->row();

		if ($query) {
			$program_studi 	= $query->program_studi != $this->input->post('program_studi') ? "|is_unique[program_studi.program_studi]" : "";
		} else {
			$program_studi 	= "|is_unique[program_studi.program_studi]";
		}

		$this->form_validation->set_rules('program_studi', 'Program Studi', 'trim|required' . $program_studi);

		$this->form_validation->set_message('required', '{field} harus diisi');
		$this->form_validation->set_message('is_unique', '{field} sudah terdaftar');

		if ($this->form_validation->run() == FALSE) {
			$output = [
			    'status' => FALSE,
			    'errors' => array(
			        'program_studi' => form_error('program_studi'),
			    )
			];
		} else {
			$data = array('program_studi' => htmlspecialchars($this->input->post('program_studi')));

			if ($query) {
				$action  = $this->db->update('program_studi', $data, ['id_program_studi' => $query->id_program_studi]);
				$message = 'Berhasil Mengubah Program Studi';
			} else {
				$action  = $this->db->insert('program_studi', $data);
				$message = 'Berhasil Menambah Program Studi';
			}

			$output = array(
				'status' 	=> $action,
				'message' 	=> $message
			);
		}

		echo json_encode($output);
	}

	public function school()
	{
		$data = array(
			'folder'			=> $this->folder,
			'title' 			=> 'Sekolah Mitra',
		);

		$this->include->view('index_sekolah_mitra', $data);
	}

	public function show_school()
	{
		$data = $this->sekolah->getDataTables();
		echo json_encode($data);
	}

	public function delete_school($id_sekolah_mitra = null)
	{
		$query = $this->db->get_where('sekolah_mitra', ['md5(id_sekolah_mitra)' => $id_sekolah_mitra])->row();
		if ($query) {
			$action = $this->db->delete('sekolah_mitra', ['id_sekolah_mitra' => $query->id_sekolah_mitra]);
			echo json_encode(['status' => $action]);
		} else {
			redirect(site_url());
		}
	}

	public function save_school()
	{
		$query = $this->db->get_where('sekolah_mitra', ['md5(id_sekolah_mitra)' => $this->input->post('id_sekolah_mitra')])->row();

		if ($query) {
			$nama_sekolah 	= $query->nama_sekolah != $this->input->post('nama_sekolah') ? "|is_unique[sekolah_mitra.nama_sekolah]" : "";
		} else {
			$nama_sekolah 	= "|is_unique[sekolah_mitra.nama_sekolah]";
		}

		$this->form_validation->set_rules('nama_sekolah', 'Sekolah', 'trim|required' . $nama_sekolah);
		$this->form_validation->set_rules('alamat_sekolah', 'Alamat', 'trim');

		$this->form_validation->set_message('required', '{field} harus diisi');
		$this->form_validation->set_message('is_unique', '{field} sudah terdaftar');

		if ($this->form_validation->run() == FALSE) {
			$output = [
			    'status' => FALSE,
			    'errors' => array(
			        'nama_sekolah' => form_error('nama_sekolah'),
			    )
			];
		} else {
			$data = array(
				'nama_sekolah' 		=> htmlspecialchars($this->input->post('nama_sekolah')),
				'alamat_sekolah' 	=> htmlspecialchars($this->input->post('alamat_sekolah')),
			);

			if ($query) {
				$action  = $this->db->update('sekolah_mitra', $data, ['id_sekolah_mitra' => $query->id_sekolah_mitra]);
				$message = 'Berhasil Mengubah Sekolah Mitra';
			} else {
				$action  = $this->db->insert('sekolah_mitra', $data);
				$message = 'Berhasil Menambah Sekolah Mitra';
			}

			$output = array(
				'status' 	=> $action,
				'message' 	=> $message
			);
		}

		echo json_encode($output);
	}

	public function year()
	{
		$data = array(
			'folder'			=> $this->folder,
			'title' 			=> 'Tahun Pelaksanaan',
		);

		$this->include->view('index_tahun_pelaksanaan', $data);
	}

	public function show_year()
	{
		$this->load->model('TahunPelaksanaanModel', 'year');
		$data = $this->year->getDataTables();
		echo json_encode($data);
	}

	public function delete_year($id_tahun_pelaksanaan = null)
	{
		$query = $this->db->get_where('tahun_pelaksanaan', ['md5(id_tahun_pelaksanaan)' => $id_tahun_pelaksanaan])->row();
		if ($query) {
			$action = $this->db->delete('tahun_pelaksanaan', ['id_tahun_pelaksanaan' => $query->id_tahun_pelaksanaan]);
			echo json_encode(['status' => $action]);
		} else {
			redirect(site_url());
		}
	}

	public function add_year($id_tahun_pelaksanaan)
	{
		$query = $this->db->get_where('tahun_pelaksanaan', ['md5(id_tahun_pelaksanaan)' => $id_tahun_pelaksanaan])->row();

		if ($query) {
			$tahun_pelaksanaan = $query->tahun_pelaksanaan + 1;
			$data['tahun_pelaksanaan'] = $tahun_pelaksanaan;
			$data['tanggal_mulai'] = ''. $tahun_pelaksanaan .'-09-01';
		} else {
			if ($this->db->get('tahun_pelaksanaan')->num_rows() < 1) {
				$data['tahun_pelaksanaan'] = date('Y');
				$data['tanggal_mulai'] = ''. date('Y') .'-09-01';
			}
		}

		if (isset($data)) {

			$action = $this->db->insert('tahun_pelaksanaan', $data);
			echo json_encode([
				'status' 	=> $action,
				'message'	=> 'Berhasil Menambah Tahun Pelaksanaan'
			]);
		} else {
			redirect(site_url());
		}

	}

	public function update_year($id_tahun_pelaksanaan)
	{
		$query = $this->db->get_where('tahun_pelaksanaan', ['md5(id_tahun_pelaksanaan)' => $id_tahun_pelaksanaan])->row();

		if ($query) {
			if ($query->status_pelaksanaan == 1) {
				$action = $this->db->update('tahun_pelaksanaan', [
					'status_pelaksanaan' 	=> NULL,
					'pendaftaran_mahasiswa' => NULL,
				], ['id_tahun_pelaksanaan' => $query->id_tahun_pelaksanaan]);
				$message = 'Berhasil Menonaktifkan Tahun Pelaksanaan';
			} else {
				$this->db->update('tahun_pelaksanaan', ['status_pelaksanaan' => NULL]);
				$action = $this->db->update('tahun_pelaksanaan', ['status_pelaksanaan' => 1], ['id_tahun_pelaksanaan' => $query->id_tahun_pelaksanaan]);
				$message = 'Berhasil Mengaktifkan Tahun Pelaksanaan';
			}


			echo json_encode([
				'status' 	=> $action,
				'message' 	=> $message,
			]);
		} else {
			redirect(site_url());
		}
	}

	public function pendaftaran_mahasiswa($id_tahun_pelaksanaan)
	{
		$query = $this->db->get_where('tahun_pelaksanaan', ['md5(id_tahun_pelaksanaan)' => $id_tahun_pelaksanaan])->row();

		if ($query) {
			$data 	= array('pendaftaran_mahasiswa' => $query->pendaftaran_mahasiswa == 1 ? NULL : 1);
			$action = $this->db->update('tahun_pelaksanaan', $data, ['id_tahun_pelaksanaan' => $query->id_tahun_pelaksanaan]);
			echo json_encode(['status' => $action]);
		} else {
			redirect(site_url());
		}
	}

	public function change_tanggal_mulai($id_tahun_pelaksanaan)
	{
		$query = $this->db->get_where('tahun_pelaksanaan', ['md5(id_tahun_pelaksanaan)' => $id_tahun_pelaksanaan])->row();

		if ($query && $this->input->post('tanggal_mulai')) {
			$data 	= array('tanggal_mulai' => date('Y-m-d', strtotime($this->input->post('tanggal_mulai'))));
			$action = $this->db->update('tahun_pelaksanaan', $data, ['id_tahun_pelaksanaan' => $query->id_tahun_pelaksanaan]);
			echo json_encode([
				'status' 	=> $action,
				'message'	=> 'Berhasil Mengubah Tanggal Mulai'
			]);
		} else {
			show_404();
		}
	}

	public function group()
	{
		$data = array(
			'folder'			=> $this->folder,
			'title' 			=> 'Kelompok Mahasiswa',
		);

		$this->include->view('index_kelompok_mahasiswa', $data);
	}

	public function show_group()
	{
		// Menampilkan Kelompok Sekolah
		
		$data = $this->group->getDataTables();
		echo json_encode($data);
	}

	public function delete_group($id_kelompok_sekolah = null)
	{
		$query = $this->group->getData($id_kelompok_sekolah);

		if ($query > 0) {
			$this->db->delete('kelompok_sekolah', ['id_kelompok_sekolah' => $query['id_kelompok_sekolah']]);
			echo json_encode(['status' => true]);
		} else {
			show_404();
		}
	}

	public function setgroup($id_kelompok_sekolah = null)
	{
		// Tambah & Edit Kelompok Mahasiswa
		
		$query = $this->group->getData($id_kelompok_sekolah);

		$data = array(
			'folder'				=> $this->folder,
			'title' 				=> 'Kelompok Mahasiswa',
			'header'				=> $query > 0 ? 'Edit' : 'Tambah',
			'row'					=> $query,
			'tahun_pelaksanaan_id'	=> $this->year->getAktif(),
		);

		if (isset($query['id_kelompok_sekolah'])) {
			$data['kelompok_sekolah'] = $this->group->getResult($query['tahun_pelaksanaan_id']);
		}

		$this->form_validation->set_rules('tahun_pelaksanaan_id', 'Tahun Pelaksanaan', 'trim|required|numeric');
		$this->form_validation->set_rules('program_studi_id', 'Program Studi', 'trim|required|numeric');
		$this->form_validation->set_rules('sekolah_mitra_id', 'Sekolah Mitra', 'trim|required|numeric');
		$this->form_validation->set_rules('id_pengguna_dpl', 'DPL', 'trim|required|numeric');
		$this->form_validation->set_rules('id_pengguna_gpl', 'GPL', 'trim|numeric');

		if ($this->form_validation->run() == FALSE) {
			$this->include->view('addedit_kelompok_mahasiswa', $data);
		} else {
			$this->_save_group(@$query['id_kelompok_sekolah']);
		}
	}

	private function _save_group($id_kelompok_sekolah = null)
	{
		$fields = $this->db->list_fields('kelompok_sekolah');

		for ($i=0; $i < count($fields); $i++) {
			if ($i >= 1 && $this->input->post($fields[$i])) {
				$data[$fields[$i]] = htmlspecialchars($this->input->post($fields[$i]));
			}
		}

		if ($id_kelompok_sekolah) {
			$this->db->update('kelompok_sekolah', $data, ['id_kelompok_sekolah' => $id_kelompok_sekolah]);
			$kelompok_sekolah_id 	= $id_kelompok_sekolah;
			$flashdata 				= 'Berhasil Mengubah Kelompok Mahasiswa';
		} else {
			$this->db->insert('kelompok_sekolah', $data);
			$kelompok_sekolah_id 	= $this->db->insert_id();
			$flashdata 				= 'Berhasil Menambah Kelompok Mahasiswa, Silahkan Tambahkan Mahasiswa!';
		}

		$this->session->set_flashdata('success', '<div class="alert alert-success alert-dismissible" style="font-weight: bold;">'. $flashdata .'</div>');
		redirect('master/setgroup/' . md5($kelompok_sekolah_id));
	}

	public function get_group($tahun_pelaksanaan_id = null)
	{
		$query = $this->group->getResult($tahun_pelaksanaan_id);

		if (count($query) > 0) {
			foreach ($query as $key) {
				$data[] = array(
					'id_kelompok_sekolah' 	=> md5($key->id_kelompok_sekolah), 
					'kelompok_sekolah' 		=> $key->program_studi .' - '. $key->nama_sekolah, 
				);
			}

			echo json_encode($data);
		}
	}

	public function show_setgroup($id_kelompok_sekolah = null)
	{
		$get = $this->group->getData($id_kelompok_sekolah);

		$tables = array(
			'Tahun Pelaksanaan' => 'tahun_pelaksanaan', 
			'Program Studi' 	=> 'program_studi', 
			'Sekolah Mitra' 	=> 'sekolah_mitra', 
			'DPL' 				=> 'pengguna', 
			'GPL' 				=> 'pengguna'
		);

		$rows = array();

		$number = 0;

		foreach ($tables as $key => $value) {
			$id = $number + 1;

			$rows[] = array_merge([$this->db->list_fields('kelompok_sekolah')[$id], $key, $value], $this->db->list_fields($value));

			$number++;
		}

		$fields = $rows;

		$data 	= array();

		$start = 0;

		$post = array();

		foreach ($fields as $key) {

			$id = $start + 1;

			$row 	= array();

			if ($this->input->post($key[0])) {
				$post[] = $this->input->post($key[0]);
			}

			$required 	= $key[0] != 'id_pengguna_gpl' ? '<span style="color: #dc3545; font-weight: bold;">*</span>' : '';
			$text 		= $key[0] == 'id_pengguna_dpl' || $key[0] == 'id_pengguna_gpl' ? $key[5] : $key[4];

			$form = '<div class="form-group">';
			$form .= '<label for="'. md5($key[0]) .'">'. $key[1] . $required .'</label>';
			$form .= '<select name="'. md5($key[0]) .'" id="'. md5($key[0]) .'" class="form-control" style="width: 100%;" onchange="'. $key[0] .'();">';
			$form .= '<option value="">Pilih '. $key[1] .'</option>';

			if ($key[0] == 'sekolah_mitra_id') {
				$query = $this->sekolah->get_sekolah_mitra($this->input->post('tahun_pelaksanaan_id'), $this->input->post('program_studi_id'), md5(@$get['sekolah_mitra_id']));
			} elseif ($key[0] == 'id_pengguna_dpl') {
				$query = $this->pengguna->get_dpl($this->input->post('tahun_pelaksanaan_id'), $this->input->post('program_studi_id'), md5(@$get['id_pengguna_dpl']));
			} elseif ($key[0] == 'id_pengguna_gpl') {
				$query = $this->pengguna->get_gpl($this->input->post('tahun_pelaksanaan_id'), $this->input->post('sekolah_mitra_id'), md5(@$get['id_pengguna_gpl']));
			} else {
				$query 	= $this->db->order_by($key[3], 'desc')->get($key[2])->result_array();
			}

			foreach ($query as $field) {
				$selected = $field[$key[3]] == $this->input->post($key[0]) ? 'selected=""' : '';
				$form .= '<option value="'. $field[$key[3]] .'" '. $selected .'>'. $field[$text] .'</option>';
			}

			$form .= '</select>';
			$form .= '</div>';
			$form .= '<script>$(function() {$("#'. md5($key[0]) .'").select2()});</script>';

			$row[] = $form; 


			$data[]	= $row;

			$id++;
		}

		$output =  array(
			'draw' 				=> $this->input->post('draw'),
			'recordsTotal'		=> count($fields),
			'recordsFiltered' 	=> count($data),
			'data' 				=> $data, 
			'post' 				=> count($post) >= 4 ? 1 : 0,
		);

		echo json_encode($output);
	}

	public function show_studentgroup($id_kelompok_sekolah)
	{
		// Menampilkan Kelompok Mahasiswa

		$query 	= $this->group->getKelompokMahasiswa($id_kelompok_sekolah);
		$data 	= array();
		$start 	= $this->input->post('start');
		$no  	= $start > 0 ? $start + 1 : 1;
		foreach ($query['result'] as $field) {
			$start++;
			$row 	= array();
			$row[]  = '<p style="text-align: center;">'. $no++ .'</p>';
			$row[]  = '<p style="text-align: left;">'. $field->no_induk .'</p>';
			$row[]  = '<p style="text-align: left;"><a href="'. site_url('user/detail/' . md5($field->id_pengguna)) .'">'. $field->nama_lengkap .'</a></p>';
			$row[] 	= '<p style="text-align: center;"><button type="button" onclick="delete_data(' . "'" . site_url('master/delete_studentgroup/' . md5($field->id_kelompok_mahasiswa)) . "'" . ')" class="btn btn-default btn-sm" style="font-weight: bold;" onclick=""><i class="fas fa-user-times"></i></button></p>';

			$data[]	= $row;
		}

		$output =  array(
			'draw' 				=> $this->input->post('draw'),
			'recordsTotal'		=> $query['count'],
			'recordsFiltered' 	=> count($data),
			'data' 				=> $data,
		);

		echo json_encode($output);
	}

	public function delete_studentgroup($id_kelompok_mahasiswa)
	{
		# Menghapus Mahasiswa dari Kelompok

		$query = $this->db->get_where('kelompok_mahasiswa', ['md5(id_kelompok_mahasiswa)' => $id_kelompok_mahasiswa])->row();

		if ($query) {
			$this->db->delete('kelompok_mahasiswa', ['id_kelompok_mahasiswa' => $query->id_kelompok_mahasiswa]);
			$output = array(
				'status' 	=> true,
				'message'	=> 'Berhasil Menghapus Mahasiswa Dari Kelompok'
			);
			echo json_encode($output);
		} else {
			show_404();
		}
	}

	public function get_student()
	{
		$query 	= $this->group->getMahasiswa();
		$data 	= array();
		$start 	= $this->input->post('start');
		$no  	= $start > 0 ? $start + 1 : 1;
		foreach ($query['result'] as $field) {
			$start++;
			$row 	= array();
			$row[]  = '<p style="text-align: center;">'. $no++ .'</p>';
			$row[]  = '<p style="text-align: left;">'. $field->no_induk .'</p>';
			$row[]  = '<p style="text-align: left;">'. $field->nama_lengkap .'</p>';
			$row[] 	= '<p style="text-align: center;"><button type="button" class="btn btn-default btn-sm" style="font-weight: bold;" onclick="save_mahasiswa(' . $field->id_pengguna . ')"><i class="fas fa-user-plus"></i></button></p>';	

			$data[]	= $row;
		}

		$output = array(
			'draw' 				=> $this->input->post('draw'),
			'recordsTotal'		=> $query['count'],
			'recordsFiltered' 	=> count($data),
			'data' 				=> $data, 
		);

		echo json_encode($output);
	}

	public function save_mahasiswa()
	{
		$this->form_validation->set_rules('kelompok_sekolah_id', 'Kelompok Sekolah', 'trim|required|numeric');
		$this->form_validation->set_rules('pengguna_id', 'Mahasiswa', 'trim|required|numeric');

		if ($this->form_validation->run() == TRUE) {
			$data = array(
				'kelompok_sekolah_id'  => $this->input->post('kelompok_sekolah_id'),
				'pengguna_id'		   => $this->input->post('pengguna_id')	 
			);

			$this->db->insert('kelompok_mahasiswa', $data);

			$output = array(
				'status' 	=> true,
				'message' 	=> 'Berhasil Menambahkan Mahasiswa'
			);

			echo json_encode($output);
		} else {
			echo json_encode(['status' => false]);
		}

	}

	public function show_register()
	{
		$query 	= $this->pengguna->get_register();
		$data 	= array();
		$start 	= $this->input->post('start');
		$no  	= $start > 0 ? $start + 1 : 1;
		foreach ($query['result'] as $field) {
			$start++;
			$row 	= array();


			$row[]  = '<p style="text-align: center;">'. $no++ .'</p>';

			$mahasiswa = array(
				'NIM' => $field->no_induk,
				'Nama<span style="color: #FFFFFF;">_</span>Mahasiswa' => $this->include->null($field->nama_lengkap),
				'Jenis<span style="color: #FFFFFF;">_</span>Kelamin' => $this->include->jenis_kelamin($field->jenis_kelamin),
				'Email' => $this->include->null($field->email),
				'Telepon' => $this->include->null($field->telepon),
				'Program<span style="color: #FFFFFF;">_</span>Studi' => $this->include->null($field->program_studi),
				'Angkatan' => $this->include->null($field->angkatan),
				'Tanggal<span style="color: #FFFFFF;">_</span>Pendaftaran' => $this->include->date($field->tanggal_pendaftaran),
			);

			$profile = '<table class="table" style="width: 100%;">';
			$mhs = 1;
			foreach ($mahasiswa as $key => $value) {
				$css = $mhs == 1 ? 'border-top: none; padding-top: 0px;' : '';

				$profile .= '<tr>';
				$profile .= '<td style="width: 20%; padding-left: 0px; '. $css .'">'. $key .'</td>';
				$profile .= '<td style="width: 5%; '. $css .'">:</td>';
				$profile .= '<td style="padding-right: 0px; '. $css .'">'. $value .'</td>';
				$profile .= '</tr>';

				$mhs++;
			}
			$profile .= '</table>';

			$row[]  = $profile;

			$kartu_rencana_studi = $field->kartu_rencana_studi ? '<img src="'. site_url(IMAGE . $field->kartu_rencana_studi) .'" alt="" style="width: 100%; max-height: 275px; display: block;">' : '-';

			$row[]  = '<div style="text-align: left;">'. $kartu_rencana_studi .'</div';

			$kwitansi_pembayaran = $field->kwitansi_pembayaran ? '<img src="'. site_url(IMAGE . $field->kwitansi_pembayaran) .'" alt="" style="width: 100%; max-height: 275px; display: block;">' : '-';

			$row[]  = '<div style="text-align: left;">'. $kwitansi_pembayaran .'</div';

			$button = '<div style="text-align: center;">';
			$button .= '<div class="btn-group">';
			$button .= BTN_ACTION;
			$button .= '<div class="dropdown-menu dropdown-menu-right" role="menu">';
			$button .= '<a class="dropdown-item" href="javascript:void(0)" onclick="terima_pendaftaran(' . "'" . md5($field->id_pengguna) . "'" . ');">Terima Pendaftaran</a>';
			$button .= '<div class="dropdown-divider"></div>';
			$button .= '<a class="dropdown-item" href="javascript:void(0)" onclick="delete_data(' . "'" . site_url('user/delete/' . md5($field->id_pengguna)) . "'" . ')">Hapus</a>';
			$button .= '</div>';
			$button .= '</div>';
			$button .= '</div>';

			$row[]  = $button;

			$data[]	= $row;
		}

		$output = array(
			'draw' 				=> $this->input->post('draw'),
			'recordsTotal'		=> $query['count'],
			'recordsFiltered' 	=> $query['rows'],
			'data' 				=> $data, 
		);

		echo json_encode($output);
	}

	public function terima_pendaftaran($id_pengguna = null)
	{
		# PENDAFTARAN MAHASISWA
		
		$query = $this->pengguna->getRow($id_pengguna);

		if (!$query) {
			redirect(site_url());
		}

		$action = $this->db->update('pengguna', ['status_pendaftaran' => 1], ['id_pengguna' => $query->id_pengguna]);
		echo json_encode([
			'status' 	=> $action,
			'message' 	=> 'Berhasil Menerima Pendaftaran Mahasiswa'
		]);
	}

	public function show_activity($pengguna_id)
	{
		$this->load->model('KegiatanMahasiswaModel', 'activity');
		$data = $this->activity->getDataTables($pengguna_id);
		echo json_encode($data);
	}

}

/* End of file Master.php */
/* Location: ./application/controllers/Master.php */
