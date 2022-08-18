<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		logged_in();
		$this->mall->hak_akses();

		$this->load->model('PenggunaModel', 'user');
		$this->load->model('KelompokMahasiswaModel', 'group');
	}

	public function index()
	{
		$this->load->model('TahunPelaksanaanModel', 'year');
		$this->load->model('KegiatanMahasiswaModel', 'activity');
		$this->load->model('ProdiModel', 'prodi');
		$pengguna = $this->user->getRow(md5($this->session->id_pengguna));
		$tahun_pelaksanaan = $this->year->getRow();
		$space 	  = '<span style="color: #FFFFFF;">_</span>';


		$data['title'] 		= 'Beranda';
		$data['pengguna'] 	= $pengguna;

		if ($this->session->id_jenis_pengguna == 1) {
			$data['tahun_pelaksanaan']	= $tahun_pelaksanaan;
			$data['dosen'] = $this->db->get_where('pengguna', [
				'jenis_pengguna_id'		=> 3,
				'status_pendaftaran'	=> 1,
			])->num_rows();
			$data['guru_pamong'] = $this->db->get_where('pengguna', [
				'jenis_pengguna_id'		=> 4,
				'status_pendaftaran'	=> 1,
			])->num_rows();
			$data['mahasiswa'] = $this->db->get_where('pengguna', [
				'jenis_pengguna_id'		=> 5,
				'status_pendaftaran'	=> 1,
			])->num_rows();
			$this->include->view('index_home', $data);
		} elseif ($this->session->id_jenis_pengguna == 2) {
			echo '<a href="'. site_url('logout') .'">HALAMAN TIDAK DITEMUKAN</a>';
		} elseif ($this->session->id_jenis_pengguna == 5) {

			$query = $this->group->getSekolahMahasiswa($pengguna->id_pengguna);

			$data['kelompok_sekolah'] = array(
				'Tahun'. $space .'Pelaksanaan' 				=> isset($query['tahun_pelaksanaan']) ? $query['tahun_pelaksanaan'] : $this->include->null(@$tahun_pelaksanaan->tahun_pelaksanaan),
				'Program'. $space .'Studi' 					=> @$query['program_studi'] ? $query['program_studi'] : $this->include->null(@$pengguna->program_studi),
				'Sekolah'. $space .'Mitra' 					=> $this->include->null(@$query['sekolah_mitra']),
				'Dosen'. $space .'Pembimbing'. $space .'Lapangan'. $space .'(DPL)' 	=> $this->include->null(@$query['nama_dpl']),
				'Guru'. $space .'Pembimbing'. $space .'Lapangan'. $space .'(GPL)' 	=> $this->include->null(@$query['nama_gpl']),

			);	

			$data['mahasiswa'] = $this->user->find_registrasi($pengguna->id_pengguna);
			
			$this->include->topnav('index_pembimbing_dan_mahasiswa', $data);
		} else {
			$where['tahun_pelaksanaan_id'] = @$tahun_pelaksanaan->id_tahun_pelaksanaan;

			if ($this->session->id_jenis_pengguna == 3) {
				$where['id_pengguna_dpl'] = $this->session->id_pengguna;
			} elseif ($this->session->id_jenis_pengguna == 4) {
				$where['id_pengguna_gpl'] = $this->session->id_pengguna;
			}

			$query = $this->group->getData($where);

			
			$nama_dpl   = isset($query['nama_dpl']) ? '<a href="javascript:void(0)" onclick="lihat_pembimbing(' . "'" . md5($query['id_pengguna_dpl']) . "'" . ')">'. $query['nama_dpl'] .'</a>' : '-';
			$nama_gpl   = isset($query['nama_gpl']) ? '<a href="javascript:void(0)" onclick="lihat_pembimbing(' . "'" . md5($query['id_pengguna_gpl']) . "'" . ')">'. $query['nama_gpl'] .'</a>' : '-';

			$data['kelompok_sekolah'] = array(
				'Tahun'. $space .'Pelaksanaan' 				=> isset($query['tahun_pelaksanaan']) ? $query['tahun_pelaksanaan'] : $this->include->null(@$tahun_pelaksanaan->tahun_pelaksanaan),
				'Program'. $space .'Studi' 					=> $this->include->null(@$query['program_studi']),
				'Sekolah'. $space .'Mitra' 					=> $this->include->null(@$query['sekolah_mitra']),
				'Dosen'. $space .'Pembimbing'. $space .'Lapangan'. $space .'(DPL)' 	=> $this->session->id_jenis_pengguna == 4 ? $nama_dpl : $this->include->null(@$query['nama_dpl']),
				'Guru'. $space .'Pembimbing'. $space .'Lapangan'. $space .'(GPL)' 	=> $this->session->id_jenis_pengguna == 3 ? $nama_gpl : $this->include->null(@$query['nama_gpl']),

			);

			$data['id_kelompok_sekolah'] 	= @$query['id_kelompok_sekolah'];
			$data['guru_pamong'] 			= $this->user->get_gpl(@$query['tahun_pelaksanaan_id'], @$query['sekolah_mitra_id'], md5(@$query['id_pengguna_gpl']));
			$data['id_pengguna_gpl'] 		= @$query['id_pengguna_gpl'];
			$data['sekolah_mitra_id'] 		= @$query['sekolah_mitra_id'];

			$data['kelompok_mahasiswa'] 	= $this->activity->get_kegiatan_mahasiswa(@$query['id_kelompok_sekolah']);

			$this->include->topnav('index_pembimbing_dan_mahasiswa', $data);
		}
	}

	public function get_pembimbing($id_pengguna = null)
	{
		$query = $this->user->getRow($id_pengguna);

		if (!$query) {
			show_404();
		}

		$nama_lengkap = explode(' ', @$query->nama_lengkap);
		$foto_profile = isset($nama_lengkap[0]) ? substr(strtoupper($nama_lengkap[0]), 0, 1) : '';
		$foto_profile .= isset($nama_lengkap[1]) ? substr(strtoupper($nama_lengkap[1]), 0, 1) : '';

		if ($query->jenis_pengguna_id == 3) {
			$txt_id = 'NIDN';
		} elseif ($query->jenis_pengguna_id == 4) {
			$txt_id = 'NIP/NUPTK';
		} elseif ($query->jenis_pengguna_id == 5) {
			$txt_id = 'NIM';
		}


		$data = array(
			'txt_id'		=> $txt_id,
			'no_induk'		=> $this->include->null(@$query->no_induk),
			'nama_lengkap' 	=> $this->include->null(@$query->nama_lengkap),
			'jenis_kelamin' => $this->include->jenis_kelamin(@$query->jenis_kelamin),
			'gender' 		=> @$query->jenis_kelamin,
			'email' 		=> $this->include->null(@$query->email),
			'telepon' 		=> $this->include->null(@$query->telepon),
			'foto_profil' 	=> isset($query->foto_profil) ? '<img class="" src="'. site_url(IMAGE . $query->foto_profil) .'" alt="" style="max-width: 125px;">' : '<span class="foto_pengguna" style="width: 120px; height: 120px; font-size: 36px;">'. $foto_profile .'</span>',
		);

		if ($query->jenis_pengguna_id == 5) {
			$data['program_studi_id'] = @$query->program_studi_id;
			$data['angkatan']  		  = @$query->angkatan;
		} else {
			$data['modal_title'] = $query->jenis_pengguna_id == 3 ? 'Profile DPL' : 'Profile GPL';
		}

		echo json_encode($data);

	}

	public function edit_gpl($id_kelompok_sekolah = null)
	{
		$kelompok_sekolah 	= $this->group->getData($id_kelompok_sekolah);
		$guru_pamong 		= $this->user->getRow($this->input->post('id_pengguna_gpl'));

		if (!$kelompok_sekolah && !$guru_pamong) {
			redirect(site_url());
		}

		$this->db->update('kelompok_sekolah', ['id_pengguna_gpl' => $guru_pamong->id_pengguna], ['id_kelompok_sekolah' => $kelompok_sekolah['id_kelompok_sekolah']]);
		$this->session->set_flashdata('success', '<div class="alert alert-success alert-dismissible" style="font-weight: bold;">Berhasil Mengubah GPL</div>');
		redirect('home');
	}

	public function upload_foto($id_pengguna = null)
	{
		$query = $this->user->getRow($id_pengguna);

		if (!$query) {
			redirect(site_url());
		}

		$this->_do_upload();

		if ($this->upload->do_upload('foto')) {
		    if (isset($query->foto_profil)) {
		        unlink(IMAGE . $query->foto_profil);
		    }

		    $data['foto_profil'] = $this->upload->data('file_name');
		    if ($this->input->post('redirect')) {
			    $flashdata 	= 'Berhasil Mengubah Foto Mahasiswa';
		    } else {
			    $flashdata 	= 'Berhasil Mengubah Foto Profile';
		    }
		} else {
			if ($this->input->post('foto_profil')) {
				if (isset($query->foto_profil)) {
				    unlink(IMAGE . $query->foto_profil);
				}
				$data['foto_profil'] = NULL;
				$flashdata 	= 'Berhasil Menghapus Foto Profile';
			}
		}

		$this->db->update('pengguna', $data, ['id_pengguna' => $query->id_pengguna]);
		if (isset($flashdata)) {
			$this->session->set_flashdata('success', '<div class="alert alert-success alert-dismissible" style="font-weight: bold;">'. $flashdata .'</div>');
		}
		if ($this->input->post('redirect')) {
			redirect($this->input->post('redirect'));
		} else {
			redirect('home');
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

	public function save_pengguna()
	{
		$query = $this->user->getRow($this->input->post('id_pengguna'));

		if ($query) {
			$no_induk 	= $query->no_induk != $this->input->post('no_induk') ? "|is_unique[pengguna.no_induk]" : "";
			$email 		= $query->email != $this->input->post('email') ? "|is_unique[pengguna.email]" : "";
			$telepon 	= $query->telepon != $this->input->post('telepon') ? "|is_unique[pengguna.telepon]" : "";

			if ($query->jenis_pengguna_id == 3) {
				$txt_id = 'NIDN';
			} elseif ($query->jenis_pengguna_id == 4) {
				$txt_id = 'NIP/NUPTK';
			} elseif ($query->jenis_pengguna_id == 5) {
				$txt_id = 'NIM';
			}

		} else {
			$no_induk 	= $this->input->post('no_induk') ? "|is_unique[pengguna.no_induk]" : "";
			$email 		= "|is_unique[pengguna.email]";
			$telepon 	= "|is_unique[pengguna.telepon]";
			$txt_id 	= 'NIP/NUPTK';
		}

		$this->form_validation->set_error_delimiters('', '');
		$this->form_validation->set_rules('no_induk', $txt_id, 'trim' . $no_induk);
		$this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email' . $email);
		$this->form_validation->set_rules('telepon', 'Telepon', 'trim|required|numeric' . $telepon);

		$this->form_validation->set_message('required', '{field} harus diisi');
		$this->form_validation->set_message('is_unique', '{field} sudah terdaftar');

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
				'program_studi_id'
			);

			for ($i = 0; $i < count($fields); $i++) {
				if ($this->input->post($fields[$i])) {
					$data[$fields[$i]] = htmlspecialchars($this->input->post($fields[$i]));
				}
			}

			if ($query) {

				if ($this->input->post('password1')) {
					$data['password'] = sha1($this->input->post('password1'));
				}

				$action  = $this->db->update('pengguna', $data, ['id_pengguna' => $query->id_pengguna]);

				if ($query->jenis_pengguna_id == 5) {
					$this->db->update('mahasiswa', ['angkatan' => $this->input->post('angkatan')], ['id_mahasiswa' => $query->id_mahasiswa]);
					$message = 'Berhasil Mengubah Data Mahasiswa';
				} else {
					$message = $query->jenis_pengguna_id == 3 ? 'Berhasil Mengubah Profile Pengguna' : 'Berhasil Mengubah Profile GPL'; '';
				}


			} else {

				$data['password'] = $this->input->post('password1') ? sha1($this->input->post('password1')) : sha1($this->input->post('email'));

				$data['jenis_pengguna_id'] 		= 4;
				$data['tanggal_pendaftaran'] 	= date('Y-m-d H:i:s');
				$data['status_pendaftaran'] 	= 1;

				$action 		= $this->db->insert('pengguna', $data);
				$message 		= 'Berhasil Menambah GPL';

				if ($action > 0 && $this->input->post('sekolah_mitra_id')) {
					$this->db->insert('guru_pamong', [
						'pengguna_id' 		=> $this->db->insert_id(),
						'sekolah_mitra_id'	=> $this->input->post('sekolah_mitra_id'),
					]);
				}

			}

			$output = array(
				'status' 		=> $action,
				'message' 		=> $message,
			);
		}

		echo json_encode($output);
	}

	public function update_password($id_pengguna = null)
	{
		$query = $this->user->getRow($id_pengguna);

		if (!$query) {
			show_404();
		}

		$this->form_validation->set_error_delimiters('', '');
		$this->form_validation->set_rules('password_sekarang', 'Password Sekarang', 'trim|required');
		$this->form_validation->set_rules('password_baru', 'Password Baru', 'trim|required');
		$this->form_validation->set_rules('konfirmasi_password', 'Konfirmasi Password', 'trim|required');
		$this->form_validation->set_message('required', '{field} harus diisi');

		$password = $query->password != sha1($this->input->post('password_sekarang')) ? FALSE : TRUE;

		if ($this->form_validation->run() == FALSE) {
			$output = [
			    'status' => FALSE,
			    'errors' => array(
			        'password_sekarang' 	=> form_error('password_sekarang'),
			        'password_baru' 		=> form_error('password_baru'),
			        'konfirmasi_password' 	=> form_error('konfirmasi_password'),
			    )
			];
		} elseif ($password == FALSE) {
			$output = [
			    'status' => FALSE,
			    'errors' => array(
			        'password_sekarang' => 'Password Sekarang salah',
			    )
			];
		} else {

			$this->db->update('pengguna', ['password' => sha1($this->input->post('password_baru'))], ['id_pengguna' => $query->id_pengguna]);

			$output = array(
				'status' 		=> $password,
				'message' 		=> 'Berhasil Mengubah Password',
			);
		}

		echo json_encode($output);
	}

	public function update_register($id_pengguna = null)
	{
		$query  = $this->user->getStudent($id_pengguna);

		if (!$query) {
			redirect(site_url());
		}

		$this->_do_upload();

		$fields = array(
			'kartu_rencana_studi', 
			'kwitansi_pembayaran', 
		);

		for ($i = 0; $i < count($fields); $i++) {
			if ($this->upload->do_upload($fields[$i])) {
				if (isset($query[$fields[$i]])) {
				    unlink(IMAGE . $query[$fields[$i]]);
				}
				$data[$fields[$i]] = $this->upload->data('file_name');
			} else {
				if ($query[$fields[$i]] == null) {
					$errors[$fields[$i]] = 'Format File tidak valid';
				}
			}
		}

		if (isset($data)) {
			$action = $this->db->update('mahasiswa', $data, ['id_mahasiswa' => $query['id_mahasiswa']]);
			if ($action) {
				$get = $this->user->getRow($id_pengguna);
				if ($get->kartu_rencana_studi && $get->kwitansi_pembayaran) {
					$output['message'] = 'Berhasil Menyelesaikan Pendaftaran';
				} elseif ($get->kartu_rencana_studi) {
					$output['message'] = 'Berhasil Mengapload Kartu Rencana Studi';
				} elseif ($get->kwitansi_pembayaran) {
					$output['message'] = 'Berhasil Mengapload Kwitansi Pembayaran';
				} 
			}
		}

		if (isset($errors)) {
			$output['errors'] = $errors;
		}

		echo json_encode($output);
	}

	public function printactivity($pengguna_id = null)
	{
		$this->load->model('KegiatanMahasiswaModel', 'kmm');
		$query = $this->kmm->findKegiatanByMhs($pengguna_id);

		if (empty($query->id_kelompok_mahasiswa)) {
			redirect(site_url());
		}

		$content = '<h2 style="text-align: center;">Kegiatan Mahasiswa PLP 2</h2>';
		$content .= '<table class="table" cellpadding="5">';
		$content .= '<tr>';
		$content .= '<td style="font-weight: bold; width: 20%;">NIM</td>';
		$content .= '<td style="width: 5%;">:</td>';
		$content .= '<td style="width: 25%;">'. $query->nim .'</td>';
		$content .= '<td style="font-weight: bold;">Tahun Pelaksanaan</td>';
		$content .= '<td>:</td>';
		$content .= '<td>'. $query->tahun_pelaksanaan .'</td>';
		$content .= '</tr>';
		$content .= '<tr>';
		$content .= '<td style="font-weight: bold; width: 20%;">Nama Mahasiswa</td>';
		$content .= '<td style="width: 5%;">:</td>';
		$content .= '<td style="width: 25%;">'. $query->nama_lengkap .'</td>';
		$content .= '<td style="font-weight: bold;">Program Studi</td>';
		$content .= '<td>:</td>';
		$content .= '<td>'. $query->program_studi .'</td>';
		$content .= '</tr>';
		$content .= '</table>';
		$content .= '<br>';
		$content .= '<table class="table" border="1" cellspacing="0" cellpadding="5" style="width: 100%;">';
		$content .= '<tr>';
		$content .= '<th style="width: 5%;">No</th>';
		$content .= '<th>Tanggal</th>';
		$content .= '<th>Kegiatan</th>';
		$content .= '<th style="width: 15%;">Dokumentasi</th>';
		$content .= '</tr>';
		$number = 1;
		foreach ($this->db->get_where('kegiatan_mahasiswa', ['pengguna_id' => $query->pengguna_id])->result() as $row) {

			$tanggal  	 = $row->tanggal ? $this->include->datetime($row->tanggal) : '-';
			$kegiatan 	 = $row->kegiatan ? $row->kegiatan : '-';
			$dokumentasi = $row->dokumentasi ? '<img src="'. site_url(IMAGE . $row->dokumentasi) .'" alt="" style="width: 175px; height: 100px;">' : '-';

			$content .= '<tr>';
			$content .= '<td style="text-align: center;">'. $number++ .'</td>';
			$content .= '<td style="padding-left: 6px;">'. $tanggal .'</td>';
			$content .= '<td style="padding-left: 6px;">'. $kegiatan .'</td>';
			$content .= '<td style="text-align: center; padding-left: 6px;">'. $dokumentasi .'</td>';
			$content .= '</tr>';

		}
		$content .= '</table>';
		$content .= '<script>window.print();</script>';
		$content .= '<style media="print">@page {size: auto; margin: 24px;}</style>';

		echo $content;
	}

}

/* End of file Home.php */
/* Location: ./application/controllers/Home.php */
