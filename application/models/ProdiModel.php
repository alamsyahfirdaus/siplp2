<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ProdiModel extends CI_Model {

	private $table 			= 'program_studi';
	private $columnOrder	= ['id_program_studi ', NULL];
	private $columnSearch	= ['id_program_studi ', 'program_studi'];
	private $orderBy		= ['id_program_studi ' => 'DESC'];

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
			$row[]  = '<p style="text-align: left;" id="program_studi_'. md5($field->id_program_studi) .'">'. $field->program_studi .'</p>';
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
		$button .= '<a class="dropdown-item" href="javascript:void(0)" onclick="add_edit(' . "'" . md5($field->id_program_studi) . "'" . ');">Edit</a>';
		$button .= '<div class="dropdown-divider"></div>';
		$button .= '<a class="dropdown-item" href="javascript:void(0)" onclick="delete_data(' . "'" . site_url('master/delete_department/' . md5($field->id_program_studi)) . "'" . ')">Hapus</a>';
		$button .= '</div>';
		$button .= '</div>';
		$button .= '</div>';

		return $button;
	}

	public function getData($id_program_studi = null)
	{
		if ($id_program_studi) {
			return $this->db->get_where($this->table, ['md5(id_program_studi)' => $id_program_studi])->row();
		} else {
			return $this->db->get($this->table)->result();
		}
	}

}

/* End of file ProdiModel.php */
/* Location: ./application/models/ProdiModel.php */