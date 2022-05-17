<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class PengaturanModel extends CI_Model {

	private $table 			= 'pengaturan';
	private $columnOrder	= ['id_pengaturan', NULL];
	private $columnSearch	= ['id_pengaturan', 'nama', 'pengaturan'];
	private $orderBy		= ['id_pengaturan' => 'ASC'];

	private function _setBuilder()
	{
		if ($this->input->post('id_pengaturan')) {
			$this->db->where('id_pengaturan', $this->input->post('id_pengaturan'));
		}
		$this->db->where_not_in('id_pengaturan', [5]);
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
			$row[]	= $this->_setPengaturan($field, $this->input->post('id_pengaturan'));
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

	private function _setPengaturan($field, $id_pengaturan)
	{
		$table = '<table class="table" style="width: 100%;">';
		$table .= '<tr>';
		$table .= '<td style="border-top: 0px solid #FFFFFF; border-bottom: 0px solid #FFFFFF; padding: 0px;">'. $field->nama .'</td>';

		if ($field->pengaturan) {

			if ($id_pengaturan == $field->id_pengaturan) {
				$table .= '<td style="border-top: 0px solid #FFFFFF; border-bottom: 0px solid #FFFFFF; padding: 0px; width: 45%;">';
				$table .= '<div class="form-group">';
				$table .= '<input type="text" class="form-control" id="pengaturan_'. $field->id_pengaturan .'" autofocus="" autocomplete="off" placeholder="Pengaturan" value="'. $field->pengaturan .'">';
				$table .= '<span id="pengaturan_'. $field->id_pengaturan .'-error" class="error invalid-feedback"></span>';
				$table .= '</div>';
				$table .= '<input type="hidden" id="pengaturan_old_'. $field->id_pengaturan .'" value="'. $field->pengaturan .'">';
				$table .= '</td>';

				$table .= '<td style="border-top: 0px solid #FFFFFF; border-bottom: 0px solid #FFFFFF; padding-top: 0px; padding-bottom: 0px; padding-right: 0px; width: 5%;" text-align: center;"><a href="javascript:void(0)" class="btn btn-default" onclick="update_pengaturan('. $field->id_pengaturan .')"><i class="fas fa-save"></i></a></td>';
			} else {
				$table .= '<td style="border-top: 0px solid #FFFFFF; border-bottom: 0px solid #FFFFFF; padding: 0px; width: 45%;">'. $field->pengaturan .'</td>';
				$table .= '<td style="border-top: 0px solid #FFFFFF; border-bottom: 0px solid #FFFFFF; padding-top: 0px; padding-bottom: 0px; padding-right: 0px; width: 5%;" text-align: center;"><a href="javascript:void(0)" class="btn btn-default btn-sm" onclick="change_pengaturan('. $field->id_pengaturan .')"><i class="fas fa-edit"></i></a></td>';
			}


		} else {

			$table .= '<td style="border-top: 0px solid #FFFFFF; border-bottom: 0px solid #FFFFFF; padding: 0px; width: 45%;"><img class="img-responsive" src="'. site_url(IMAGE . $this->include->image($field->gambar)) .'" alt="User profile picture" style="width: 125px; max-height: 125px;"></td>';
			$table .= '<td style="border-top: 0px solid #FFFFFF; border-bottom: 0px solid #FFFFFF; padding-top: 0px; padding-bottom: 0px; padding-right: 0px; width: 5%;" text-align: center;"><a href="javascript:void(0)" class="btn btn-default btn-sm" onclick="change_gambar('. $field->id_pengaturan .')"><i class="fas fa-image"></i></a></td>';

		}

		$table .= '</tr>';
		$table .= '</table>';
		return $table;
	}

}

/* End of file PengaturanModel.php */
/* Location: ./application/models/PengaturanModel.php */