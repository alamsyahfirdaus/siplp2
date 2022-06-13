<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Evaluation extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		logged_in();
		$this->mall->hak_akses();
		$this->load->model('KomponenPenilaianModel', 'komponen');
		$this->load->model('NilaiAkhirModel', 'score');
		$this->load->model('TahunPelaksanaanModel', 'year');
		$this->load->model('ProdiModel', 'prodi');
	}

	private $folder = 'Penilaian';

	public function index()
	{
		redirect(site_url());
	}

	public function instrument()
	{
		$data = array(
			'folder'			=> $this->folder,
			'title' 			=> 'Instrumen Penilaian',
		);

		$this->include->view('index_penilaian', $data);
	}

	public function show_penilaian()
	{
		$this->load->model('PenilaianModel', 'penilaian');
		$data = $this->penilaian->getDataTables();
		echo json_encode($data);
	}

	public function setinstrument($id_penilaian = null)
	{
		$query = $this->db->get_where('penilaian', ['md5(id_penilaian)' => $id_penilaian])->row();

		$data = array(
			'folder'			=> $this->folder,
			'title' 			=> 'Instrumen Penilaian',
			'header' 			=> $query ? 'Edit' : 'Tambah',
			'row'				=> $query,
			'detail_penilaian'	=> $this->komponen->detail_penilaian($id_penilaian),
		);

		$this->form_validation->set_rules('penilaian', 'Instrumen Penilaian', 'trim|required|alpha_numeric_spaces');
		$this->form_validation->set_rules('program_studi_id', 'Program Studi', 'trim|required|numeric');
		$this->form_validation->set_rules('jenis_pengguna_id', 'Penilai', 'trim|required|numeric');
		$this->form_validation->set_rules('skala', 'Skala', 'trim|required|numeric');

		if ($this->form_validation->run() == FALSE) {
			$this->include->view('addedit_penilaian', $data);
		} else {
			$this->_save_penilaian(@$query->id_penilaian);
		}

	}

	private function _save_penilaian($id_penilaian = null)
	{
		$fields = array('program_studi_id', 'jenis_pengguna_id', 'penilaian', 'petunjuk', 'skala');

		for ($i=0; $i < count($fields); $i++) {
			if ($this->input->post($fields[$i])) {
				$data[$fields[$i]] = htmlspecialchars($this->input->post($fields[$i]));
			}
		}

		if ($id_penilaian) {
			$this->db->update('penilaian', $data, ['id_penilaian' => $id_penilaian]);
			$id 		= $id_penilaian;
			$flashdata 	= 'Berhasil Mengubah Instrumen Penilaian';
		} else {
			$this->db->insert('penilaian', $data);
			$id 		= $this->db->insert_id();
			$flashdata 	= 'Berhasil Menambah Instrumen Penilaian, Silahkan Tambahkan Komponen Penilaian!';
		}

		$this->session->set_flashdata('success', '<div class="alert alert-success alert-dismissible" style="font-weight: bold;">'. $flashdata .'</div>');
		redirect('evaluation/setinstrument/' . md5($id));
	}

	public function delete_penilaian($id_penilaian = null)
	{
		$query = $this->db->get_where('penilaian', ['md5(id_penilaian)' => $id_penilaian])->row();

		if ($query) {
			$this->db->delete('penilaian', ['id_penilaian' => $query->id_penilaian]);
			echo json_encode(['status' => true]);
		} else {
			show_404();
		}
	}

	public function show_komponen($id_penilaian)
	{
		$data = $this->komponen->getDataTables($id_penilaian);
		echo json_encode($data);
	}

	public function save_komponen($id_penilaian)
	{
		if ($this->input->post('isi')) {
			$this->form_validation->set_rules('aspek', 'Aspek', 'trim|required|alpha_numeric_spaces');
		} elseif ($this->input->post('aspek_id')) {
			$this->form_validation->set_rules('indikator', 'Indikator', 'trim|required|alpha_numeric_spaces');
		} elseif ($this->input->post('indikator_id')) {
			$this->form_validation->set_rules('pernyataan', 'Pernyataan', 'trim|required');
		} else {
			$this->form_validation->set_rules('pernyataan', 'Pernyataan', 'trim|required');
		}

		if ($this->input->post('aspek')) {
			$id_detail_penilaian = $this->input->post('id_aspek');
			$komponen 			 = $this->input->post('aspek');
		} elseif ($this->input->post('indikator')) {
			$id_detail_penilaian = $this->input->post('id_indikator');
			$komponen 			 = $this->input->post('indikator');
		} elseif ($this->input->post('pernyataan')) {
			$id_detail_penilaian = $this->input->post('id_pernyataan');
			$komponen 			 = $this->input->post('pernyataan');
		}

		if ($this->form_validation->run() == TRUE) {

			if ($id_detail_penilaian) {
				$this->db->update('detail_penilaian', ['komponen' => $komponen], ['id_detail_penilaian' => $id_detail_penilaian]);
				$flashdata = 'Berhasil Mengubah Komponen Penilaian';
			} else {
				$data = array(
					'penilaian_id' 	=> $id_penilaian,
					'komponen'		=> htmlspecialchars($komponen),
				);

				if ($this->input->post('isi')) {
					$data['isi'] = $this->input->post('isi');
				} elseif ($this->input->post('aspek_id')) {
					$data['isi'] = $this->input->post('aspek_id');
				} elseif ($this->input->post('indikator_id')) {
					$data['isi'] = $this->input->post('indikator_id');
				} else {
					$data['isi'] = NULL;
				}

				$this->db->insert('detail_penilaian', $data);
				$flashdata = 'Berhasil Menambah Komponen Penilaian';
			}

			$this->session->set_flashdata('success', '<div class="alert alert-success alert-dismissible" style="font-weight: bold;">'. $flashdata .'</div>');
		}

		redirect('evaluation/setinstrument/' . md5($id_penilaian));

	}

	public function delete_komponen($id_detail_penilaian = NULL)
	{
		$query = $this->db->get_where('detail_penilaian', ['md5(id_detail_penilaian)' => $id_detail_penilaian])->row();

		if ($query) {

			$indikator = $this->db->get_where('detail_penilaian', [
				'penilaian_id' 	=> $query->penilaian_id,
				'isi' 			=> $query->id_detail_penilaian,
			])->result();

			if (count($indikator) > 0) {

				foreach ($indikator as $key) {

					$pernyataan = $this->db->get_where('detail_penilaian', [
						'penilaian_id'	=> $key->penilaian_id,
						'isi' 			=> $key->id_detail_penilaian,
					])->result();

					if (count($pernyataan)) {

						foreach ($pernyataan as $row) {
							$this->db->delete('detail_penilaian', ['id_detail_penilaian' => $row->id_detail_penilaian]);
						}

					}

					$this->db->delete('detail_penilaian', ['id_detail_penilaian' => $key->id_detail_penilaian]);
				}

			}
			
			$this->db->delete('detail_penilaian', ['id_detail_penilaian' => $query->id_detail_penilaian]);

			$detail_penilaian = $this->db->get_where('detail_penilaian', ['penilaian_id' => $query->penilaian_id])->num_rows();

			$output = array(
				'status' 	=> TRUE,
				'message' 	=> 'Berhasil Menghapus Komponen Penilaian'
			);

			if ($detail_penilaian < 1) {
				$output['refresh'] = TRUE;
			}

			echo json_encode($output);

		} else {
			redirect(site_url());
		}
	}

	public function score()
	{
		$data = array(
			'folder'				=> $this->folder,
			'title' 				=> 'Hasil Penilaian',
			'tahun_pelaksanaan_id'	=> $this->year->getAktif(),
			'program_studi'			=> $this->prodi->getData(),
		);

		$this->include->view('index_hasil_penilaian', $data);

	}

	public function show_score()
	{
		$data = $this->score->getDataTables();
		echo json_encode($data);
	}

	public function report($id_pengguna_mhs = NULL)
	{
		$query = $this->_getKelompokMhs($id_pengguna_mhs);

		if (!$query) {
			redirect('evaluation/score');
		}

		$data = array(
			'folder'			=> $this->folder,
			'title' 			=> 'Hasil Penilaian',
			'row'				=> $query,
			'id_pengguna_mhs'	=> $id_pengguna_mhs,
		);

		$this->include->view('detail_hasil_penilaian', $data);

	}

	public function show_report($tahun_pelaksanaan_id, $id_pengguna_mhs)
	{
		$hasil_penilaian = array(
			'tahun_pelaksanaan_id' => $tahun_pelaksanaan_id, 
			'id_pengguna_mhs'	   => $id_pengguna_mhs
		);

		$this->load->model('HasilPenilaianModel', 'report');
		$data = $this->report->getDataTables($hasil_penilaian);
		echo json_encode($data);
	}

	private function _getKelompokMhs($pengguna_id)
	{
		$mahasiswa = $this->db->get_where('pengguna', ['md5(id_pengguna)' => $pengguna_id])->row();

		if ($mahasiswa) {

			$this->db->join('kelompok_sekolah', 'kelompok_sekolah.id_kelompok_sekolah = kelompok_mahasiswa.kelompok_sekolah_id', 'left');
			$this->db->join('program_studi', 'program_studi.id_program_studi = kelompok_sekolah.program_studi_id', 'left');
			$this->db->join('sekolah_mitra', 'sekolah_mitra.id_sekolah_mitra = kelompok_sekolah.sekolah_mitra_id', 'left');
			$this->db->where('pengguna_id', $mahasiswa->id_pengguna);
			$query = $this->db->get('kelompok_mahasiswa')->row();

			return array(
				'nim'		 			=> $mahasiswa->no_induk, 
				'nama_mhs'				=> $mahasiswa->nama_lengkap,
				'tahun_pelaksanaan_id'	=> $query->tahun_pelaksanaan_id,
				'program_studi'			=> $query->program_studi,
				'sekolah_mitra'			=> $query->nama_sekolah,
				'nama_dpl'				=> $this->include->nama_gelar($query->id_pengguna_dpl),
				'nama_gpl'				=> $this->include->nama_gelar($query->id_pengguna_gpl),
			);
			
		} else {
			return FALSE;
		}

	}

	public function print()
	{
		$fields = array('tahun_pelaksanaan_id', 'program_studi_id', 'id_pengguna_mhs');

		for ($i=0; $i < count($fields); $i++) { 
			$this->form_validation->set_rules(''. $fields[$i] .'', '', 'trim|required');
		}

		if ($this->form_validation->run() == FALSE) {
			redirect('evaluation/score');
		} else {

			$tahun_pelaksanaan 	= $this->year->getRow($this->input->post('tahun_pelaksanaan_id'));
			$program_studi 		= $this->prodi->getData($this->input->post('program_studi_id'));

			$pengguna_id = array();
			foreach (explode(',', $this->input->post('id_pengguna_mhs')) as $id_pengguna_mhs) {
				$pengguna_id[] = $id_pengguna_mhs;
			}

			$query = $this->db->select('p.id_pengguna, p.no_induk as nim, p.nama_lengkap')->join('mahasiswa m', 'p.id_pengguna = m.pengguna_id', 'left')->where_in('p.id_pengguna', $pengguna_id)->order_by('p.no_induk', 'asc')->get('pengguna p')->result();

			$content = '<h2 style="text-align: center;">Hasil Penilaian PLP 2</h2>';
			$content .= '<table class="table" cellpadding="5">';
			$content .= '<tr>';
			$content .= '<td style="font-weight: bold;">Tahun Pelaksanaan</td>';
			$content .= '<td>:</td>';
			$content .= '<td>'. $tahun_pelaksanaan->tahun_pelaksanaan .'</td>';
			$content .= '</tr>';
			$content .= '<tr>';
			$content .= '<td style="font-weight: bold;">Program Studi</td>';
			$content .= '<td>:</td>';
			$content .= '<td>'. $program_studi->program_studi .'</td>';
			$content .= '</tr>';
			$content .= '</table>';
			$content .= '<br>';
			$content .= '<table class="table" border="1" cellspacing="0" cellpadding="5" style="width: 100%;">';
			$content .= '<tr>';
			$content .= '<th style="width: 5%;">No</th>';
			$content .= '<th>NIM</th>';
			$content .= '<th>Nama Mahasiswa</th>';
			$content .= '<th>Sekolah Mitra</th>';
			$content .= '<th style="width: 10%;">Nilai</th>';
			$content .= '</tr>';
			$number = 1;
			foreach ($query as $row) {
				$sekolah_mitra 		= $this->_getKelompokMhs(md5($row->id_pengguna))['sekolah_mitra'];
				$nilai_mahasiswa  	= $this->score->nilai_akhir($row->id_pengguna)['huruf'];

				$content .= '<tr>';
				$content .= '<td style="text-align: center;">'. $number++ .'</td>';
				$content .= '<td style="padding-left: 6px;">'. $row->nim .'</td>';
				$content .= '<td style="padding-left: 6px;">'. $row->nama_lengkap .'</td>';
				$content .= '<td style="padding-left: 6px;">'. $sekolah_mitra .'</td>';
				$content .= '<td style="text-align: center;">'. $nilai_mahasiswa .'</td>';
				$content .= '</tr>';

			}
			$content .= '</table>';
			$content .= '<script>window.print();</script>';
			$content .= '<style media="print">@page {size: auto; margin: 24px;}</style>';

			echo $content;
		}

	}

}

/* End of file Evaluation.php */
/* Location: ./application/controllers/Evaluation.php */
