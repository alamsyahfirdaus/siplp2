<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class TahunPelaksanaanModel extends CI_Model {

	private $table 			= 'tahun_pelaksanaan';
	private $columnOrder	= ['id_tahun_pelaksanaan', NULL];
	private $columnSearch	= ['id_tahun_pelaksanaan', 'tahun_pelaksanaan'];
	private $orderBy		= ['id_tahun_pelaksanaan' => 'DESC'];

	private function _setBuilder()
	{
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

			$row[]  = '<p style="text-align: center;">'. $no .'</p>';

			$disabled 			= $field->status_pelaksanaan == 1 ? '' : 'disabled';

			$status_pelaksanaan = $field->status_pelaksanaan == 1 ? 'Aktif' : 'Tidak Aktif';
			$tahun_pelaksanaan  = $field->tahun_pelaksanaan .' - '. $status_pelaksanaan;

			$row[]  = '<p style="text-align: left;">'. $tahun_pelaksanaan . '</p>';

			$status_pendaftaran 	= $field->pendaftaran_mahasiswa == 1 ? 'Aktif' : 'Tidak Aktif';
			$checked_pendaftaran 	= $field->pendaftaran_mahasiswa == 1 ? 'checked' : '';

			$pendaftaran_mahasiswa = '<div class="form-group">';
			$pendaftaran_mahasiswa .= '<div class="custom-control custom-switch">';
			$pendaftaran_mahasiswa .= '<input type="checkbox" class="custom-control-input" id="pendaftaran_mahasiswa_'. $field->id_tahun_pelaksanaan .'" '. $disabled .' '. $checked_pendaftaran .' onclick="pendaftaran_mahasiswa(' . "'" . md5($field->id_tahun_pelaksanaan) . "'" . ')">';
			$pendaftaran_mahasiswa .= '<label class="custom-control-label" for="pendaftaran_mahasiswa_'. $field->id_tahun_pelaksanaan .'">'. $status_pendaftaran .'</label>';
			$pendaftaran_mahasiswa .= '</div>';
			$pendaftaran_mahasiswa .= '</div>';
			$pendaftaran_mahasiswa .= '<input type="hidden" id="tahun_pelaksanaan-'. $no .'" value="'. md5($field->id_tahun_pelaksanaan) .'">';

			$disabled_tanggal = $field->status_pelaksanaan == 1 ? '' : 'disabled';

			$tanggal_mulai = '<div class="form-group">';
			$tanggal_mulai .= '<select name="tanggal_mulai_'. md5($field->id_tahun_pelaksanaan) .'" id="tanggal_mulai_'. $field->id_tahun_pelaksanaan .'" class="form-control" '. $disabled .' onchange="tanggal_mulai(' . "'" . md5($field->id_tahun_pelaksanaan) . "'" . ')">';
			$tanggal_mulai .= '<option value="">Tanggal Mulai</option>';
			foreach ($this->_get_tanggal_mulai($field->tahun_pelaksanaan) as $key => $value) {
				$selected = $key == $field->tanggal_mulai ? 'selected=""' : '';
				$tanggal_mulai .= '<option value="'. $key .'" '. $selected .'>'. $value .'</option>';
			}
			$tanggal_mulai .= '</select>';
			$tanggal_mulai .= '</div>';
			$tanggal_mulai .= '<script>$("#tanggal_mulai_'. $field->id_tahun_pelaksanaan .'").select2();</script>';

			$row[]  = $tanggal_mulai;
			$row[]  = $pendaftaran_mahasiswa;
			$row[]  = $this->_getButton($field);

			$data[]	= $row;

			$no++;
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
		$status_pelaksanaan = $field->status_pelaksanaan == 1 ? 'Nonaktifkan' : 'Aktifkan';

		$button = '<div style="text-align: center;">';
		$button .= '<div class="btn-group">';
		$button .= BTN_ACTION;
		$button .= '<div class="dropdown-menu dropdown-menu-right" role="menu">';
		$button .= '<a class="dropdown-item" href="javascript:void(0)" onclick="update_data(' . "'" . md5($field->id_tahun_pelaksanaan) . "'" . ');">'. $status_pelaksanaan .'</a>';
		$button .= '<div class="dropdown-divider"></div>';
		// $button .= '<a class="dropdown-item" href="javascript:void(0)">Edit</a>';
		// $button .= '<div class="dropdown-divider"></div>';
		$button .= '<a class="dropdown-item" href="javascript:void(0)" onclick="delete_data(' . "'" . site_url('master/delete_year/' . md5($field->id_tahun_pelaksanaan)) . "'" . ')">Hapus</a>';
		$button .= '</div>';
		$button .= '</div>';
		$button .= '</div>';

		return $button;
	}

	public function getAktif()
	{
		$query = $this->db->get_where($this->table, ['status_pelaksanaan' => 1])->row();

		return isset($query->id_tahun_pelaksanaan) ? $query->id_tahun_pelaksanaan : 0;
	}

	public function getRow($id = null)
	{
		$id_tahun_pelaksanaan = $id > 0 ? $id : $this->getAktif();
		return $this->db->get_where($this->table, ['id_tahun_pelaksanaan' => $id_tahun_pelaksanaan])->row();
	}

	public function getTanggalPelaksanaan($pengguna_id)
	{
		$id_tahun_pelaksanaan = $this->getAktif();
		$query = $this->db->get_where($this->table, ['id_tahun_pelaksanaan' => $id_tahun_pelaksanaan])->row();


		$data = array();

		if (isset($query->tanggal_mulai)) {

			$tanggal_mulai    = $query->tanggal_mulai;
			$tanggal_selesai  = date('Y-m-d', strtotime('+90 days'));
			$from_date 		  = new DateTime($tanggal_mulai);
			$to_date 		  = new DateTime($tanggal_selesai);
			
			for ($date = $from_date; $date <= $to_date; $date->modify('+1 day')) {
				$data[$date->format('Y-m-d')] = $this->include->date($date->format('Y-m-d'));
			}
		}

		$tgl_mhs = $this->_tanggal_kegiatan_mahasiswa($data, $pengguna_id);

		return count($data) > 0 ? $tgl_mhs : false;
	}

	private function _tanggal_kegiatan_mahasiswa($tanggal, $pengguna_id)
	{
		$query = $this->db->get_where('kegiatan_mahasiswa', ['pengguna_id' => $pengguna_id])->result();

		if (count($query) > 0) {
			$id_kegiatan_mahasiswa = array();
			foreach ($query as $row) {
				$id_kegiatan_mahasiswa[$row->tanggal] = $row->tanggal;
			}

			foreach ($id_kegiatan_mahasiswa as $key => $value) {
				unset($tanggal[$key]);
			}

			return $tanggal;
		} else {
			return $tanggal;
		}
	}

	private function _get_tanggal_mulai($tahun)
	{
		$tanggal_mulai    = ''. $tahun .'-01-01';
		$tanggal_selesai  = ''. $tahun .'-12-31';
		$from_date 		  = new DateTime($tanggal_mulai);
		$to_date 		  = new DateTime($tanggal_selesai);
		
		for ($date = $from_date; $date <= $to_date; $date->modify('+1 day')) {
			$data[$date->format('Y-m-d')] = $this->include->date($date->format('Y-m-d'));
		}

		return $data;
	}

}

/* End of file TahunPelaksanaanModel.php */
/* Location: ./application/models/TahunPelaksanaanModel.php */