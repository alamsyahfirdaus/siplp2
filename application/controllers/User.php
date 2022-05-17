<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		logged_in();
		$this->mall->hak_akses();

		$this->load->model('PenggunaModel', 'user');
		$this->load->model('ProdiModel', 'prodi');
		$this->load->model('SekolahMitraModel', 'sekolah');

	}

	private $folder = 'Pengguna';

	public function index()
	{
		redirect(site_url());
	}

	public function administration()
	{
		$data = array(
			'folder'			=> $this->folder,
			'title' 			=> 'Administrator',
			'jenis_pengguna_id'	=> md5(1),
			'thead'				=> $this->_thead()['admin'],
		);
		$this->include->view('index_pengguna', $data);	
	}

	public function dean()
	{
		// Dekan
	}

	public function lecturer()
	{
		$data = array(
			'folder'			=> $this->folder,
			'title' 			=> 'Dosen',
			'jenis_pengguna_id'	=> md5(3),
			'thead'				=> $this->_thead()['dosen'],
		);

		$this->include->view('index_pengguna', $data);
	}

	public function teacher()
	{
		$data = array(
			'folder'			=> $this->folder,
			'title' 			=> 'Guru Pamong',
			'jenis_pengguna_id'	=> md5(4),
			'thead'				=> $this->_thead()['guru'],
		);
		$this->include->view('index_pengguna', $data);
	}

	public function student()
	{
		$data = array(
			'folder'			=> $this->folder,
			'title' 			=> 'Mahasiswa',
			'jenis_pengguna_id'	=> md5(5),
			'thead'				=> $this->_thead()['mhs'],
		);
		$this->include->view('index_pengguna', $data);
	}

	private function _thead()
	{
		$admin = '<th style="text-align: center;">NIDN</th>';
		$admin .= '<th style="text-align: left;">Nama<span style="color: #ffffff;">_</span>Lengkap</th>';
		$admin .= '<th style="text-align: left;">Email</th>';
		$admin .= '<th style="text-align: left;">Telepon</th>';

		$dosen = '<th style="text-align: center;">NIDN</th>';
		$dosen .= '<th style="text-align: left;">Nama<span style="color: #ffffff;">_</span>Lengkap</th>';
		$dosen .= '<th style="text-align: left;">Jenis<span style="color: #ffffff;">_</span>Kelamin</th>';
		$dosen .= '<th style="text-align: left;">Program<span style="color: #ffffff;">_</span>Studi</th>';

		$guru = '<th style="text-align: center;">NIP/NUPTK</th>';
		$guru .= '<th style="text-align: left;">Nama<span style="color: #ffffff;">_</span>Lengkap</th>';
		$guru .= '<th style="text-align: left;">Jenis<span style="color: #ffffff;">_</span>Kelamin</th>';
		$guru .= '<th style="text-align: left;">Sekolah<span style="color: #ffffff;">_</span>Mitra</th>';
		// $guru .= '<th style="text-align: left;">Program<span style="color: #ffffff;">_</span>Studi</th>';

		$mhs = '<th style="text-align: center;">NIM</th>';
		$mhs .= '<th style="text-align: left;">Nama<span style="color: #ffffff;">_</span>Lengkap</th>';
		$mhs .= '<th style="text-align: left;">Jenis<span style="color: #ffffff;">_</span>Kelamin</th>';
		$mhs .= '<th style="text-align: left;">Program<span style="color: #ffffff;">_</span>Studi</th>';
		$mhs .= '<th style="text-align: center;">Angkatan</th>';
		// $mhs .= '<th style="text-align: left;">Status<span style="color: #ffffff;">_</span>Pendaftaran</th>';

		return array(
			'admin' 	=> $admin,
			'dosen'		=> $dosen,
			'guru'		=> $guru,
			'mhs'		=> $mhs
		);
	}

	public function showUser($jenis_pengguna_id = null)
	{
		$query = $this->db->get_where('jenis_pengguna', ['md5(id_jenis_pengguna)' => $jenis_pengguna_id])->row();

		if (!$query) {
			redirect(site_url());
		}

		$data = $this->user->getDataTables($query->id_jenis_pengguna);
		echo json_encode($data);
	}

	public function add($jenis_pengguna_id = NULL)
	{
		$query = $this->db->get_where('jenis_pengguna', ['md5(id_jenis_pengguna)' => $jenis_pengguna_id])->row();

		if (!$query) {
			redirect(site_url());
		}

		$data = array(
			'folder'			=> $this->folder,
			'title' 			=> $query->jenis_pengguna,
			'header'			=> 'Tambah ' . $query->jenis_pengguna,
			'jenis_pengguna_id'	=> $query->id_jenis_pengguna,
			'jenis_pengguna'	=> $query->jenis_pengguna
		);
		$this->include->view('addedit_pengguna', $data);
	}

	public function edit($id_pengguna = NULL)
	{
		$query = $this->user->getRow($id_pengguna);

		if (!$query) {
			redirect(site_url());
		}

		$data = array(
			'folder'				=> $this->folder,
			'title' 				=> $query->jenis_pengguna,
			'header'				=> 'Edit ' . $query->jenis_pengguna,
			'jenis_pengguna_id'		=> $query->jenis_pengguna_id,
			'jenis_pengguna'		=> $query->jenis_pengguna,
			'row'					=> $query,
			'status_pendaftaran'	=> $query->status_pendaftaran == 1 ? 1 : 2,
		);
		$this->include->view('addedit_pengguna', $data);
	}

	public function savePengguna($id_pengguna)
	{
		$query = $this->user->getRow($id_pengguna);

		if ($query) {
			$no_induk 	= $query->no_induk != $this->input->post('no_induk') ? "|is_unique[pengguna.no_induk]" : "";
			$email 		= $query->email != $this->input->post('email') ? "|is_unique[pengguna.email]" : "";
			$telepon 	= $query->telepon != $this->input->post('telepon') ? "|is_unique[pengguna.telepon]" : "";
		} else {
			$no_induk 	= $this->input->post('no_induk') ? "|is_unique[pengguna.no_induk]" : "";
			$email 		= "|is_unique[pengguna.email]";
			$telepon 	= "|is_unique[pengguna.telepon]";
		}

		$this->form_validation->set_error_delimiters('', '');
		$this->form_validation->set_rules('no_induk', $this->input->post('id_name'), 'trim' . $no_induk);
		$this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email' . $email);
		$this->form_validation->set_rules('telepon', 'Telepon', 'trim|required|numeric' . $telepon);
		$this->form_validation->set_rules('jenis_pengguna_id', 'Jenis Pengguna', 'trim|required|numeric');

		$this->form_validation->set_message('required', '{field} harus diisi.');
		$this->form_validation->set_message('is_unique', '{field} sudah terdaftar.');

		if ($this->form_validation->run() == FALSE) {
			$output = [
			    'status' => FALSE,
			    'errors' => array(
			        'no_induk' 	=> form_error('no_induk'),
			        'email' 	=> form_error('email'),
			        'telepon' 	=> form_error('telepon'),
			    )
			];
		} else {

			$fields = array(
				'no_induk', 
				'nama_lengkap', 
				'jenis_kelamin', 
				'telepon', 
				'email',
				'jenis_pengguna_id',
				'program_studi_id',
			);

			for ($i = 0; $i < count($fields); $i++) {
				if ($this->input->post($fields[$i])) {
					$data[$fields[$i]] = htmlspecialchars($this->input->post($fields[$i]));
				}
			}

			$this->_do_upload();

			if ($this->upload->do_upload('foto')) {
			    if (isset($query->foto_profil)) {
			        unlink(IMAGE . $query->foto_profil);
			    }

			    $data['foto_profil'] = $this->upload->data('file_name');

			} else {
				if ($this->input->post('foto_profil')) {
					if (isset($query->foto_profil)) {
					    unlink(IMAGE . $query->foto_profil);
					}
					$data['foto_profil'] = NULL;
				}
			}

			if ($query) {

				if ($this->input->post('password1')) {
					$data['password'] = sha1($this->input->post('password1'));
				}

				if ($this->input->post('status_pendaftaran')) {
					$status_pendaftaran = $this->input->post('status_pendaftaran') == 1 ? 1 : NULL;
					$data['status_pendaftaran'] = $status_pendaftaran;
				}

				$action 		= $this->db->update('pengguna', $data, ['id_pengguna' => $query->id_pengguna]);
				$pengguna_id 	= $query->id_pengguna;
				$message 		= 'Berhasil Mengubah ' . $this->input->post('jenis_pengguna');

			} else {

				$data['password'] = $this->input->post('password1') ? sha1($this->input->post('password1')) : sha1($this->input->post('email'));

				$data['tanggal_pendaftaran'] 	= date('Y-m-d H:i:s');
				$data['status_pendaftaran'] 	= 1;

				$action 		= $this->db->insert('pengguna', $data);
				$pengguna_id 	= $this->db->insert_id();
				$message 		= 'Berhasil Menambah ' . $this->input->post('jenis_pengguna');

			}

			if ($this->input->post('jenis_pengguna_id') == 4 && $this->input->post('sekolah_mitra_id')) {
				$this->_guru_pamong($pengguna_id);
			} elseif ($this->input->post('jenis_pengguna_id') == 5 && $this->input->post('angkatan')) {
				$this->_mahasiswa($pengguna_id);
			}


			$output = array(
				'status' 		=> $action,
				'message' 		=> $message,
			);
		}

		echo json_encode($output);
	}

	private function _guru_pamong($pengguna_id)
	{
		$query = $this->db->get_where('guru_pamong', ['pengguna_id' => $pengguna_id])->row();

		$data['sekolah_mitra_id'] = $this->input->post('sekolah_mitra_id');

		if ($query) {
			return $this->db->update('guru_pamong', $data, ['id_guru_pamong' => $query->id_guru_pamong]);
		} else {
			$data['pengguna_id'] = $pengguna_id;
			return $this->db->insert('guru_pamong', $data);
		}
	}

	private function _mahasiswa($pengguna_id)
	{
		$query = $this->db->get_where('mahasiswa', ['pengguna_id' => $pengguna_id])->row();

		$data['angkatan'] = $this->input->post('angkatan');

		if ($query) {
			return $this->db->update('mahasiswa', $data, ['id_mahasiswa' => $query->id_mahasiswa]);
		} else {
			$data['pengguna_id'] = $pengguna_id;
			return $this->db->insert('mahasiswa', $data);
		}
	}

	public function delete($id_pengguna = NULL)
	{
		$query = $this->user->getRow($id_pengguna);

		if (!$query) {
			redirect(site_url());
		}

		if (isset($query->foto_profil)) {
		    unlink(IMAGE . $query->foto_profil);
		}

		if ($query->jenis_pengguna_id == 5) {
			if (isset($query->kartu_rencana_studi)) {
			    unlink(IMAGE . $query->kartu_rencana_studi);
			}

			if (isset($query->kwitansi_pembayaran)) {
			    unlink(IMAGE . $query->kwitansi_pembayaran);
			}
		}

		$action = $this->db->delete('pengguna', ['id_pengguna' => $query->id_pengguna]);
		echo json_encode([
			'status' 	=> $action,
			'message'	=> 'Berhasil Menghapus ' . $query->jenis_pengguna,
		]);
	}

	public function detail($id_pengguna = NULL)
	{
		$query = $this->user->getRow($id_pengguna);

		if (!$query) {
			redirect(site_url());
		}

		$data = array(
			'folder'			=> $this->folder,
			'title' 			=> $query->jenis_pengguna,
			'header'			=> 'Detail',
			'row'				=> $query,
			'mahasiswa'			=> $this->user->get_result_mhs(),
		);

		if ($query->jenis_pengguna_id == 5) {
			$this->include->view('detail_mahasiswa', $data);
		} else {
			redirect('user/edit/' . $id_pengguna);
		}
	}

	private function _do_upload()
	{
        $config['upload_path']   = IMAGE;
        $config['allowed_types'] = 'gif|jpg|png|jpeg|bmp|GIF|JPG|PNG|JPEG|BMP|';
        $config['max_size']    	 = 10000;
        $config['max_width']   	 = 10000;
        $config['max_height']  	 = 10000;
        $config['file_name']     = time();
        $this->upload->initialize($config);
	}

}

/* End of file User.php */
/* Location: ./application/controllers/User.php */
