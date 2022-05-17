<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class PenilaianModel extends CI_Model {

	private $table 			= 'penilaian';
	private $columnOrder	= ['id_penilaian', NULL];
	private $columnSearch	= ['id_penilaian', 'penilaian', 'program_studi', 'jenis_pengguna'];
	private $orderBy		= ['id_penilaian' => 'DESC'];

	private function _setJoin()
	{
		$this->db->join('program_studi', 'program_studi.id_program_studi = penilaian.program_studi_id', 'left');
		$this->db->join('jenis_pengguna', 'jenis_pengguna.id_jenis_pengguna = penilaian.jenis_pengguna_id', 'left');
	}

	private function _setBuilder()
	{
		$this->_setJoin();
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

			if ($field->jenis_pengguna_id == 3) {
				$penilai = 'DPL';
			} elseif ($field->jenis_pengguna_id == 4) {
				$penilai = 'GPL';
			} else {
				$penilai = 'DPL<span style="color: #ffffff;">_</span>&<span style="color: #ffffff;">_</span>GPL';
			}

			$row[]  = '<p style="text-align: center;">'. $no++ .'</p>';
			$row[]  = '<p style="text-align: left;">'. $field->program_studi .'</p>';
			$row[]  = '<p style="text-align: justify;">'. $field->penilaian .'</p>';
			$row[]  = '<p style="text-align: left;">'. $penilai .'</p>';
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
		$button .= '<a class="dropdown-item" href="'. site_url('evaluation/setinstrument/' . md5($field->id_penilaian)) .'">Edit</a>';
		$button .= '<div class="dropdown-divider"></div>';
		$button .= '<a class="dropdown-item" href="javascript:void(0)" onclick="delete_data(' . "'" . site_url('evaluation/delete_penilaian/' . md5($field->id_penilaian)) . "'" . ')">Hapus</a>';
		$button .= '</div>';
		$button .= '</div>';
		$button .= '</div>';

		return $button;
	}

}

/* End of file PenilaianModel.php */
/* Location: ./application/models/PenilaianModel.php */