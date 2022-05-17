<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Teacher extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		logged_in();
		$this->mall->hak_akses();

		$this->load->model('TahunPelaksanaanModel', 'year');
		$this->load->model('PenggunaModel', 'user');
		$this->load->model('MahasiswaPembimbingModel', 'student');
		$this->load->model('KelompokMahasiswaModel', 'group');
		$this->load->model('PenilaianPembimbingModel', 'penilaian');

	}

	public function index()
	{
		$data = array('title' => 'Mahasiswa');
		$this->include->topnav('index_mahasiswa_pembimbing', $data);
	}

	public function show_student()
	{
		$query = $this->group->getKelompokSekolah();
		$kelompok_sekolah_id = $query ? $query['id_kelompok_sekolah'] : 0;
		$data = $this->student->getDataTables($kelompok_sekolah_id);
		echo json_encode($data);
	}

	private function _get_kelompok_mahasiswa($pengguna_id = null)
	{
		$kelompok_sekolah	 = $this->group->getKelompokSekolah();
		$kelompok_sekolah_id = $kelompok_sekolah['id_kelompok_sekolah'];
		$kelompok_mahasiswa  = $this->group->getKelompokMahasiswa(md5($kelompok_sekolah_id), $pengguna_id);
		return $kelompok_mahasiswa['result'];
	}

	public function activity($pengguna_id = null)
	{
		$pengguna = $this->user->getRow($pengguna_id);

		if (empty($pengguna->id_pengguna)) {
			redirect('teacher');
		}

		$data = array(
			'folder' 				=> 'Mahasiswa',
			'title' 				=> 'Kegiatan',
			'pengguna_id'			=> $pengguna_id,
			'nim'					=> $pengguna->no_induk,
			'nama_mahasiswa'		=> $pengguna->nama_lengkap,
			'kelompok_mahasiswa'	=> $this->_get_kelompok_mahasiswa($pengguna->id_pengguna),
		);

		$this->include->topnav('index_kegiatan_mahasiswa', $data);
	}

	public function show_activity($pengguna_id)
	{
		$this->load->model('KegiatanMahasiswaModel', 'activity');
		$data = $this->activity->getDataTables($pengguna_id);
		echo json_encode($data);
	}

	public function examination($pengguna_id = null)
	{
		$pengguna  = $this->user->getRow($pengguna_id);

		if (empty($pengguna->id_pengguna)) {
			redirect('teacher');
		}

		$data = array(
			'folder' 				=> 'Mahasiswa',
			'title' 				=> 'Penilaian',
			'pengguna_id'			=> $pengguna_id,
			'nim'					=> $pengguna->no_induk,
			'nama_mahasiswa'		=> $pengguna->nama_lengkap,
			'program_studi_id'		=> $pengguna->program_studi_id,
			'kelompok_mahasiswa'	=> $this->_get_kelompok_mahasiswa($pengguna->id_pengguna),

		);

		$this->include->topnav('index_penilaian_pembimbing', $data);
	}

	public function show_examination($pengguna_id, $program_studi_id)
	{	
		$query 	= $this->penilaian->get_penilaian($program_studi_id);
		$data 	= array();
		$start 	= $this->input->post('start');
		$no  	= $start > 0 ? $start + 1 : 1;
		foreach ($query['result'] as $field) {
			$start++;
			$row 	= array();

			$hasil_penilaian = $this->db->join('detail_penilaian', 'detail_penilaian.id_detail_penilaian = hasil_penilaian.detail_penilaian_id', 'left')->get_where('hasil_penilaian', [
				'penilaian_id' 			=> $field->id_penilaian,
				'md5(id_pengguna_mhs)' 	=> $pengguna_id,
				'id_pengguna_penilai' 	=> $this->session->id_pengguna,
				'selesai' 				=> 1
			])->num_rows();

			$status = $hasil_penilaian ? '<i class="fas fa-check-double" style="color: #28A745;"></i>' : '<i class="fas fa-times" style="color: #DC3545;"></i>';

			$row[]  = '<p style="text-align: center;">'. $no++ .'</p>';
			$row[]  = '<p style="text-align: left;">'. $field->penilaian .'</p>';
			$row[]  = '<p style="text-align: center;">'. $status .'</p>';
			$row[]  = '<p style="text-align: left;"><a href="'. site_url('teacher/rating/' . $pengguna_id . '/' . md5($field->id_penilaian)) .'" type="button" class="btn btn-default btn-sm"><i class="fas fa-edit"></i></a></p>';

			$data[]	= $row;
		}

		$data = array(
			'draw' 				=> $this->input->post('draw'),
			'recordsTotal'		=> $query['count'],
			'recordsFiltered' 	=> count($data),
			'data' 				=> $data, 
		);

		echo json_encode($data);

	}

	public function rating($pengguna_id = null, $penilaian_id = null)
	{
		$pengguna   = $this->user->getRow($pengguna_id);
		$penilaian 	= $this->db->get_where('penilaian', ['md5(id_penilaian)' => $penilaian_id])->row();

		if (!$pengguna || !$penilaian) {
			redirect('teacher');
		}

		$kelompok_sekolah 		= $this->group->getKelompokSekolah();
		$tahun_pelaksanaan_id 	= $kelompok_sekolah['tahun_pelaksanaan_id'];
		$program_studi_id 		= $kelompok_sekolah['program_studi_id'];
		$penilaian_mahasiswa  	= $this->penilaian->get_penilaian(md5($program_studi_id))['result'];

		$data = array(
			'folder'				=> 'Kegiatan',
			'title'					=> 'Penilaian',
			'nim' 					=> $pengguna->no_induk,
			'nama_mahasiswa' 		=> $pengguna->nama_lengkap,
			'penilaian'				=> $penilaian->penilaian,
			'penilaian_id'			=> $penilaian->id_penilaian,
			'id_pengguna_mhs'		=> $pengguna->id_pengguna,
			'id_pengguna_penilai'	=> $this->session->id_pengguna,
			'tahun_pelaksanaan_id'	=> $tahun_pelaksanaan_id,
			'kelompok_mahasiswa'	=> $this->_get_kelompok_mahasiswa(),
			'penilaian_mahasiswa'	=> $penilaian_mahasiswa

		);

		$this->include->topnav('index_pengisian_instrumen', $data);
	}

	public function show_rating()
	{
		$data = $this->penilaian->getDataTables();
		echo json_encode($data);
	}

	public function save_skor()
	{
		$this->form_validation->set_rules('detail_penilaian_id', '', 'trim|required|numeric');
		$this->form_validation->set_rules('skor', '', 'trim|required|numeric');

		if ($this->form_validation->run() == FALSE) {
			redirect(site_url());
		} else {
			
			$fields = $this->db->list_fields('hasil_penilaian');

			for ($i=0; $i < count($fields); $i++) {
				if ($i >= 1 && $this->input->post($fields[$i])) {
					$data[$fields[$i]] = htmlspecialchars($this->input->post($fields[$i]));
				}
			}

			$query = $this->db->get_where('hasil_penilaian', ['id_hasil_penilaian' => $this->input->post('id_hasil_penilaian')])->row();

			if ($query) {
				$this->db->update('hasil_penilaian', $data, ['id_hasil_penilaian' => $query->id_hasil_penilaian]);
			} else {
				$this->db->insert('hasil_penilaian', $data);
			}

			echo json_encode(['status' => TRUE]);

		}
	}

	public function update_selesai($pengguna_id, $penilaian_id)
	{
		$this->form_validation->set_rules('id_hasil_penilaian', '', 'trim|required');

		if ($this->form_validation->run() == FALSE) {
			redirect(site_url());
		} else {
			$id_hasil_penilaian = explode(',', $this->input->post('id_hasil_penilaian'));

			foreach ($id_hasil_penilaian as $id) {
				$this->db->update('hasil_penilaian', ['selesai' => 1], ['id_hasil_penilaian' => $id]);
			}

			$this->session->set_flashdata('success', '<div class="alert alert-success alert-dismissible" style="font-weight: bold;">Berhasil Menyimpan Penilaian Mahasiswa</div>');
			redirect('teacher/rating/' . $pengguna_id . '/' . $penilaian_id);
		}
	}

}

/* End of file Teacher.php */
/* Location: ./application/controllers/Teacher.php */
