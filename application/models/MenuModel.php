<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MenuModel extends CI_Model {

	private $table 			= 'menu m';
	private $columnOrder	= ['id_menu', NULL];
	private $columnSearch	= ['id_menu', 'menu'];
	private $orderBy		= ['id_menu' => 'DESC'];

	private function _setBuilder()
	{
		$this->db->order_by('sort_by', 'asc');
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
			$row[]  = '<a href="javascript:void(0)" onclick="sort_menu(' . "'" . md5($field->id_menu) . "'" . ')">'. $field->menu .'</a>';
			$row[]	= $this->_btnMenu($field);

			$sub_menu = $this->db->get_where('sub_menu', ['menu_id' => $field->id_menu])->num_rows();

			$row[]	= !$sub_menu ? '<p style="text-align: center;">-</p>' : $this->_setSubMenu($field->id_menu);
			$row[]	= !$sub_menu ? '<p style="text-align: center;">-</p>' : $this->_btnSubMenu($field->id_menu);
			
			$row[]	= $this->_setHakAkses($field->id_menu);
			$data[]	= $row;
		}

		$setData = [
			'draw' 				=> $this->input->post('draw'),
			'recordsTotal'		=> $this->db->count_all_results($this->table),
			'recordsFiltered' 	=> $this->db->get($this->_setBuilder())->num_rows(),
			'data' 				=> $data,
		];

		return $setData;
	}

	private function _btnMenu($field)
	{
		$button = '<div style="text-align: center;">';
		$button .= '<button type="button" class="btn btn-default btn-sm" data-toggle="dropdown"><i class="fas fa-cogs"></i></button>';
		$button .= '<div class="dropdown-menu dropdown-menu-right">';
		$button .= '<a class="dropdown-item" href="javascript:void(0)" onclick="edit_menu('. $field->id_menu .')">Edit</a>';
		$button .= '<div class="dropdown-divider"></div>';
		$button .= '<a class="dropdown-item" href="javascript:void(0)" onclick="delete_data(' . "'" . site_url('setting/deleteMenu/' . md5($field->id_menu)) . "'" . ')">Hapus</a>';
		$button .= '</div>';
		$button .= '</div>';
		$button .= '<input type="hidden" name="menu_'. $field->id_menu .'" value="'. $field->menu .'">';
		$button .= '<input type="hidden" name="icon_'. $field->id_menu .'" value="'. $field->icon .'">';
		$button .= '<input type="hidden" name="url_menu_'. $field->id_menu .'" value="'. $field->url .'">';
		return $button;
	}

	private function _setHakAkses($id_menu)
	{
		$query = $this->db->get('jenis_pengguna')->result();

		$table = '<table class="table">';
		foreach ($query as $row) {
			$akses_menu = $this->db->get_where('akses_menu', [
				'menu_id' 			=> $id_menu,
				'jenis_pengguna_id'	=> $row->id_jenis_pengguna,
			])->num_rows();

			$checked = $akses_menu ? 'checked' : '';

			$table .= '<tr>';
			$table .= '<td style="border-top: 1px solid #FFFFFF; border-bottom: 1px solid #DEE2E6; padding-top: 0px; padding-left: 0px;">'. $row->jenis_pengguna .'</td>';
			$table .= '<td style="border-top: 1px solid #FFFFFF; border-bottom: 1px solid #DEE2E6; padding-top: 0px; padding-left: 0px; padding-right: 0px; text-align: center;"><input type="checkbox" '. $checked .' onclick="hak_akses('. $id_menu . ', ' . $row->id_jenis_pengguna .')"></td>';
			$table .= '</tr>';
		}
		$table .= '</table>';
		return $table;
	}

	private function _setSubMenu($id_menu)
	{
		$query = $this->db->order_by('sort_by', 'asc')->get_where('sub_menu', ['menu_id' 	=> $id_menu]);

		if ($query->num_rows()) {

			$table = '<table class="table">';
			foreach ($query->result() as $row) {
				$checked = $row->aktivasi == 1 ? 'checked' : '';

				$table .= '<tr>';
				$table .= '<td style="border-top: 1px solid #FFFFFF; border-bottom: 1px solid #DEE2E6; padding-top: 0px; padding-left: 0px;"><a href="javascript:void(0)" onclick="sort_sub_menu(' . "'" . md5($row->id_sub_menu) . "'" . ');">'. $row->sub_menu .'</a></td>';
				// $table .= '<td style="border-top: 1px solid #FFFFFF; border-bottom: 1px solid #DEE2E6; padding-top: 0px; padding-left: 0px; padding-right: 0px; text-align: center;"><input type="checkbox" '. $checked .' onclick="change_aktivasi(' . "'" . md5($row->id_sub_menu) . "'" . ')"></td>';
				$table .= '</tr>';
			}
			$table .= '</table>';
			return $table;

		} else {
			return '<p style="text-align: center;">-</p>';
		}

	}

	private function _btnSubMenu($id_menu)
	{
		$query = $this->db->order_by('sort_by', 'asc')->get_where('sub_menu', ['menu_id' => $id_menu]);

		if ($query->num_rows()) {

			$table = '<table class="table">';
			foreach ($query->result() as $row) {
				$button = '<div style="text-align: center;">';
				$button .= '<button type="button" class="btn btn-default btn-sm" data-toggle="dropdown"><i class="fas fa-cogs"></i></button>';
				$button .= '<div class="dropdown-menu dropdown-menu-right">';
				$button .= '<a class="dropdown-item" href="javascript:void(0)" onclick="edit_sub_menu('. $row->id_sub_menu .')">Edit</a>';
				$button .= '<div class="dropdown-divider"></div>';
				$button .= '<a class="dropdown-item" href="javascript:void(0)" onclick="delete_sub_menu(' . "'" . site_url('setting/deleteSubMenu/' . md5($row->id_sub_menu)) . "'" . ')">Hapus</a>';
				$button .= '</div>';
				$button .= '</div>';

				$table .= '<tr>';
				$table .= '<td style="border-top: 1px solid #FFFFFF; padding-top: 0px; padding-left: 0px; padding-right: 0px;">';
				$table .= $button;
				$table .= '</td>';
				$table .= '</tr>';
				$table .= '<input type="hidden" name="sub_menu_'. $row->id_sub_menu .'" value="'. $row->sub_menu .'">';
				$table .= '<input type="hidden" name="url_'. $row->id_sub_menu .'" value="'. $row->url .'">';
				$table .= '<input type="hidden" name="menu_id_'. $row->id_sub_menu .'" value="'. $row->menu_id .'">';
			}
			$table .= '</table>';

			return $table;

		} else {
			return '<p style="text-align: center;">-</p>';
		}

	}


}

/* End of file MenuModel.php */
/* Location: ./application/models/MenuModel.php */