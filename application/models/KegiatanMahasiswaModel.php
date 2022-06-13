<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class KegiatanMahasiswaModel extends CI_Model {

	private $table 			= 'kegiatan_mahasiswa';
	private $columnOrder	= ['id_kegiatan_mahasiswa', NULL];
	private $columnSearch	= ['id_kegiatan_mahasiswa', 'tanggal', 'kegiatan'];
	private $orderBy		= ['id_kegiatan_mahasiswa' => 'DESC'];

	private function _setBuilder($pengguna_id = null)
	{
		$this->db->join('pengguna', 'pengguna.id_pengguna = kegiatan_mahasiswa.pengguna_id', 'left');
		$this->db->where('md5(pengguna_id)', $pengguna_id);
		$this->db->where('jenis_pengguna_id', 5);
		if ($this->input->post('id_kegiatan_mahasiswa')) {
			$this->db->where('md5(id_kegiatan_mahasiswa)', $this->input->post('id_kegiatan_mahasiswa'));
		}
		$this->db->order_by('no_induk', 'asc');
		$this->db->from($this->table);
		$this->include->setDataTables($this->columnOrder, $this->columnSearch, $this->orderBy);
	}

	public function getDataTables($pengguna_id)
	{
		$query 	= $this->include->getResult($this->_setBuilder($pengguna_id));
		$data 	= array();
		$start 	= $this->input->post('start');
		$no  	= $start > 0 ? $start + 1 : 1;
		foreach ($query as $field) {
			$start++;
			$row 	= array();

			$id_kegiatan_mahasiswa = md5($field->id_kegiatan_mahasiswa);
			$tanggal  	 = $field->tanggal ? $this->include->datetime($field->tanggal) : '-';
			$kegiatan 	 = $field->kegiatan ? $field->kegiatan : '-';
			$dokumentasi = $field->dokumentasi ? '<img src="'. site_url(IMAGE . $field->dokumentasi) .'" alt="" style="width: 200px; height: 150px;">' : '-';

			$row[]  = '<p style="text-align: center;">'. $no++ .'</p>';
			$row[]  = '<p style="text-align: left;">'. $tanggal .'</p>';
			$row[]  = '<p style="text-align: justify;">'. $kegiatan .'</p>';
			$row[]  = '<p style="text-align: left;">'. $dokumentasi .'</p>';
			

			if ($this->session->id_jenis_pengguna == 5) {
				// Mahasiswa

				$row[]  = $this->_getButton($field);
			}


			$data[]	= $row;
		}

		return array(
			'draw' 				=> $this->input->post('draw'),
			'recordsTotal'		=> $this->db->count_all_results($this->table),
			'recordsFiltered' 	=> count($data),
			'data' 				=> $data, 
		);

	}

	private function _getButton($field)
	{
		$button = '<div style="text-align: center;">';
		$button .= '<div class="btn-group">';
		$button .= BTN_ACTION;
		$button .= '<div class="dropdown-menu dropdown-menu-right" role="menu">';
		$button .= '<a class="dropdown-item" href="javascript:void(0)" onclick="edit_kegiatan(' . "'" . md5($field->id_kegiatan_mahasiswa) . "'" . ')">Edit</a>';
		$button .= '<div class="dropdown-divider"></div>';
		$button .= '<a class="dropdown-item" href="javascript:void(0)" onclick="delete_data(' . "'" . site_url('student/delete_kegiatan/' . md5($field->id_kegiatan_mahasiswa)) . "'" . ')">Hapus</a>';
		$button .= '</div>';
		$button .= '</div>';
		$button .= '</div>';

		return $button;
	}

	public function get_kegiatan_mahasiswa($kelompok_sekolah_id)
	{
		$kelompok_mahasiswa = $this->db->get_where('kelompok_mahasiswa', ['kelompok_sekolah_id' => $kelompok_sekolah_id])->result();

		if (count($kelompok_mahasiswa) > 0) {

			$pengguna_id = array();
			foreach ($kelompok_mahasiswa as $key) {
				$pengguna_id[] = $key->pengguna_id;
			}

			$this->db->where_in('pengguna_id', $pengguna_id);
			$this->db->join('pengguna', 'pengguna.id_pengguna = kegiatan_mahasiswa.pengguna_id', 'left');
			$this->db->where('jenis_pengguna_id', 5);
			$this->db->where('tanggal', date('Y-m-d'));
			$this->db->order_by('no_induk', 'asc');
			return $this->db->get($this->table)->result();

		} else {
			return false;
		}


	}

}

/* End of file KegiatanMahasiswaModel.php */
/* Location: ./application/models/KegiatanMahasiswaModel.php */