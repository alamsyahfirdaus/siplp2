<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class SekolahMitraModel extends CI_Model {

	private $table 			= 'sekolah_mitra';
	private $columnOrder	= ['id_sekolah_mitra', NULL];
	private $columnSearch	= ['id_sekolah_mitra', 'nama_sekolah', 'alamat_sekolah'];
	private $orderBy		= ['id_sekolah_mitra' => 'DESC'];

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
			$row[]  = '<p style="text-align: center;">'. $no++ .'</p>';
			$row[]  = '<p style="text-align: left;" id="nama_sekolah_'. md5($field->id_sekolah_mitra) .'">'. $field->nama_sekolah .'</p>';

			$alamat_sekolah = $field->alamat_sekolah ? $field->alamat_sekolah : '-';

			$row[]  = '<p style="text-align: justify;">'. $alamat_sekolah .'</p><input type="hidden" id="alamat_sekolah_'. md5($field->id_sekolah_mitra) .'" value="'. $field->alamat_sekolah .'">';
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
		$button .= '<a class="dropdown-item" href="javascript:void(0)" onclick="add_edit(' . "'" . md5($field->id_sekolah_mitra) . "'" . ');">Edit</a>';
		$button .= '<div class="dropdown-divider"></div>';
		$button .= '<a class="dropdown-item" href="javascript:void(0)" onclick="delete_data(' . "'" . site_url('master/delete_school/' . md5($field->id_sekolah_mitra)) . "'" . ')">Hapus</a>';
		$button .= '</div>';
		$button .= '</div>';
		$button .= '</div>';

		return $button;
	}

	public function getData($id_sekolah_mitra = null)
	{
		if ($id_sekolah_mitra) {
			return $this->db->get_where($this->table, ['md5(id_sekolah_mitra)' => $id_sekolah_mitra])->row();
		} else {
			return $this->db->get($this->table)->result();
		}
	}

	public function get_sekolah_mitra($tahun_pelaksanaan_id = null, $program_studi_id = null, $sekolah_mitra_id = null)
	{	
		$this->load->model('KelompokMahasiswaModel');
		$kelompok_sekolah 	= $this->KelompokMahasiswaModel->get_where_not_in($tahun_pelaksanaan_id, $program_studi_id)['program_studi'];
		
		$sekolah_mitra 	= $this->getData($sekolah_mitra_id);

		if (count($kelompok_sekolah) > 0) {
			$id_sekolah_mitra = array();
			foreach ($kelompok_sekolah as $key) {
				$id_sekolah_mitra[] = $key->sekolah_mitra_id;
			}

			$this->db->where_not_in('id_sekolah_mitra', $id_sekolah_mitra);
		}

		if (isset($sekolah_mitra->id_sekolah_mitra)) {
			$this->db->or_where('id_sekolah_mitra', $sekolah_mitra->id_sekolah_mitra);
		}

		return $this->db->get($this->table)->result_array();
	}

}

/* End of file SekolahMitraModel.php */
/* Location: ./application/models/SekolahMitraModel.php */