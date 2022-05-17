<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Student extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		logged_in();
		$this->mall->hak_akses();

		$this->load->model('PenggunaModel', 'user');
		$this->load->model('TahunPelaksanaanModel', 'year');

	}

	public function index()
	{
		$query 	= $this->user->getRow(md5($this->session->id_pengguna));

		if (!$query) {
			redirect(site_url());
		}

		$kegiatan_mahasiswa = $this->db->get_where('kegiatan_mahasiswa', ['md5(id_kegiatan_mahasiswa)' => $this->input->post('id_kegiatan_mahasiswa')])->row();

		$tanggal1 = $kegiatan_mahasiswa ? array($kegiatan_mahasiswa->tanggal => $this->include->date($kegiatan_mahasiswa->tanggal)) : [];
		$tanggal2 = $this->year->getTanggalPelaksanaan($query->id_pengguna);
		$tanggal  = $kegiatan_mahasiswa ? array_merge($tanggal1, $tanggal2) : $tanggal2;


		$data 	= array(
			'title' 				=> 'Kegiatan',
			'row'					=> $query,
			'id_mahasiswa'			=> $this->input->post('id_mahasiswa'),
			'id_kegiatan_mahasiswa'	=> $this->input->post('id_kegiatan_mahasiswa'),
			'tanggal'				=> $tanggal,
			'kegiatan_mahasiswa'	=> $kegiatan_mahasiswa,
		);

		$this->include->topnav('index_kegiatan_mahasiswa', $data);
	}

	public function resume_pembekalan($id_pengguna = null)
	{
		$query 	= $this->user->getRow($id_pengguna);

		if (!$query) {
			redirect(site_url());
		}

		$this->db->update('mahasiswa', ['resume_pembekalan' => htmlspecialchars($this->input->post('resume_pembekalan'))], ['id_mahasiswa' => $query->id_mahasiswa]);
		$this->session->set_flashdata('success', '<div class="alert alert-success alert-dismissible" style="font-weight: bold;">Berhasil Menyimpan Resume Pembekalan</div>');
		redirect('student');
	}

	public function show_kegiatan($pengguna_id)
	{
		$this->load->model('KegiatanMahasiswaModel', 'activity');
		$data = $this->activity->getDataTables($pengguna_id);
		echo json_encode($data);
	}

	public function save_kegiatan($id_pengguna = null)
	{
		$query 	= $this->user->getRow($id_pengguna);

		if (!$query) {
			redirect(site_url());
		}

		$this->form_validation->set_rules('tanggal', 'Tanggal', 'trim|required');
		$this->form_validation->set_rules('kegiatan', 'Kegiatan', 'trim|required');

		if ($this->form_validation->run() == FALSE) {
			redirect(site_url());
		} else {
			$data = array(
				'tanggal' 		=> htmlspecialchars($this->input->post('tanggal')),
				'kegiatan' 		=> htmlspecialchars($this->input->post('kegiatan')),
			);

			$kegiatan_mahasiswa = $this->db->get_where('kegiatan_mahasiswa', ['md5(id_kegiatan_mahasiswa)' => $this->input->post('id_kegiatan_mahasiswa')])->row();

			$this->_do_upload();

			if ($this->upload->do_upload('foto')) {
			    if (isset($kegiatan_mahasiswa->dokumentasi)) {
			        unlink(IMAGE . $kegiatan_mahasiswa->dokumentasi);
			    }

			    $data['dokumentasi'] = $this->upload->data('file_name');
			}

			if ($kegiatan_mahasiswa) {
				$action = $this->db->update('kegiatan_mahasiswa', $data, ['id_kegiatan_mahasiswa' => $kegiatan_mahasiswa->id_kegiatan_mahasiswa]);
				$flashdata = 'Berhasil Mengubah Kegiatan';
			} else {
				$data['pengguna_id'] = $query->id_pengguna;
				$action  = $this->db->insert('kegiatan_mahasiswa', $data);
				$flashdata = 'Berhasil Menambah Kegiatan';
			}

			if ($this->db->affected_rows()) {
				$this->session->set_flashdata('success', '<div class="alert alert-success alert-dismissible" style="font-weight: bold;">'. $flashdata .'</div>');
			}

			redirect('student');
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

	public function delete_kegiatan($id_kegiatan_mahasiswa = null)
	{
		$query = $this->db->get_where('kegiatan_mahasiswa', ['md5(id_kegiatan_mahasiswa)' => $id_kegiatan_mahasiswa])->row();

		if (!$query) {
			show_404();
		}

		$action = $this->db->delete('kegiatan_mahasiswa', ['id_kegiatan_mahasiswa' => $query->id_kegiatan_mahasiswa]);
		echo json_encode(['refresh' => $action]);
	}

}

/* End of file Student.php */
/* Location: ./application/controllers/Student.php */
