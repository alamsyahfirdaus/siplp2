<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class KomponenPenilaianModel extends CI_Model {

	private $table 			= 'detail_penilaian';
	private $columnOrder	= ['id_detail_penilaian', NULL];
	private $columnSearch	= ['id_detail_penilaian', 'komponen'];
	private $orderBy		= ['id_detail_penilaian' => 'ASC'];

	private function _setBuilder($penilaian_id = null)
	{
		$id_detail_penilaian = $this->detail_penilaian($penilaian_id);
		if ($id_detail_penilaian) {
			$this->db->where_in('id_detail_penilaian', $id_detail_penilaian);
		} 
		$this->db->where('md5(penilaian_id)', $penilaian_id);
		$this->db->from($this->table);
		$this->include->setDataTables($this->columnOrder, $this->columnSearch, $this->orderBy);
	}

	public function getDataTables($penilaian_id)
	{
		$query 	= $this->include->getResult($this->_setBuilder($penilaian_id));
		$data 	= array();
		$start 	= $this->input->post('start');
		$no  	= $start > 0 ? $start + 1 : 1;
		$alpha  = 'A';

		foreach ($query as $field) {
			$start++;
			$row 	= array();

			if ($field->isi) {

				$row[]  = $this->_setPenialian($field, $alpha++, $no++);

			} else {
				$row[]  = '<p style="text-align: center;">'. $no++ .'</p>';

				$button = '<a class="dropdown-item" href="javascript:void(0)" onclick="edit_pernyataan('. $field->id_detail_penilaian .')">Edit Pernyataan</a>';
				$button .= '<div class="dropdown-divider"></div>';
				$button .= '<a class="dropdown-item" href="javascript:void(0)" onclick="delete_data(' . "'" . site_url('evaluation/delete_komponen/' . md5($field->id_detail_penilaian)) . "'" . ')">Hapus Pernyataan</a>';
				
				$row[]  = '<p style="text-align: left;" id="pernyataan_'. $field->id_detail_penilaian .'">'. $field->komponen .'</p>';
				$row[]  = $this->_getButton($button);
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

	private function _getButton($dropdown)
	{
		$button = '<div style="text-align: center;">';
		$button .= '<div class="btn-group">';
		$button .= BTN_ACTION;
		$button .= '<div class="dropdown-menu dropdown-menu-right" role="menu">';
		$button .= $dropdown;
		$button .= '</div>';
		$button .= '</div>';
		$button .= '</div>';

		return $button;
	}

	private function _setPenialian($field, $alpha, $number)
	{
		$komponen = '<table class="table table-striped">';

		$aspek = '<a class="dropdown-item" href="javascript:void(0)" onclick="add_indikator('. $field->id_detail_penilaian .')">Tambah Indikator</a>';
		$aspek .= '<div class="dropdown-divider"></div>';
		$aspek .= '<a class="dropdown-item" href="javascript:void(0)" onclick="edit_aspek('. $field->id_detail_penilaian .')">Edit Aspek</a>';
		$aspek .= '<div class="dropdown-divider"></div>';
		$aspek .= '<a class="dropdown-item" href="javascript:void(0)" onclick="delete_data(' . "'" . site_url('evaluation/delete_komponen/' . md5($field->id_detail_penilaian)) . "'" . ')">Hapus Aspek</a>';

		$komponen .= '<tr>';
		$komponen .= '<th style="width: 5%; text-align: center; border-top: none;">'. $alpha .'</th>';
		$komponen .= '<th style="border-top: none; " id="aspek_'. $field->id_detail_penilaian .'">'. $field->komponen .'</th>';
		$komponen .= '<th style="width: 10%; text-align: center; border-top: none;">'. $this->_getButton($aspek) .'</th>';
		$komponen .= '</tr>';

		$no_indikator = $number == 1 ? $number : $number + 1;

		foreach ($this->db->get_where($this->table, ['isi' => $field->id_detail_penilaian])->result() as $key_aspek) {

			$indikator = '<a class="dropdown-item" href="javascript:void(0)" onclick="add_pernyataan('. $key_aspek->id_detail_penilaian .')">Tambah Pernyataan</a>';
			$indikator .= '<div class="dropdown-divider"></div>';
			$indikator .= '<a class="dropdown-item" href="javascript:void(0)" onclick="edit_indikator('. $key_aspek->id_detail_penilaian .')">Edit Indikator</a>';
			$indikator .= '<div class="dropdown-divider"></div>';
			$indikator .= '<a class="dropdown-item" href="javascript:void(0)" onclick="delete_data(' . "'" . site_url('evaluation/delete_komponen/' . md5($key_aspek->id_detail_penilaian)) . "'" . ')">Hapus Indikator</a>';

			$komponen .= '<tr>';
			$komponen .= '<th style="width: 5%; text-align: center;">'. $no_indikator++ .'</th>';
			$komponen .= '<input type="hidden" id="aspek_id_'. $key_aspek->id_detail_penilaian .'" value="'. $key_aspek->isi .'">';
			$komponen .= '<th style="" id="indikator_'. $key_aspek->id_detail_penilaian .'">'. $key_aspek->komponen .'</th>';
			$komponen .= '<th style="width: 10%; text-align: center;">'. $this->_getButton($indikator) .'</th>';
			$komponen .= '</tr>';

			foreach ($this->db->get_where($this->table, ['isi' => $key_aspek->id_detail_penilaian])->result() as $key_indikator) {

				$pernyataan = '<a class="dropdown-item" href="javascript:void(0)" onclick="edit_pernyataan('. $key_indikator->id_detail_penilaian .')">Edit Pernyataan</a>';
				$pernyataan .= '<div class="dropdown-divider"></div>';
				$pernyataan .= '<a class="dropdown-item" href="javascript:void(0)" onclick="delete_data(' . "'" . site_url('evaluation/delete_komponen/' . md5($key_indikator->id_detail_penilaian)) . "'" . ')">Hapus Pernyataan</a>';

				$komponen .= '<tr>';
				$komponen .= '<td style="width: 5%; text-align: center; color: #FFFFFF;"></td>';
				$komponen .= '<input type="hidden" id="indikator_id_'. $key_indikator->id_detail_penilaian .'" value="'. $key_indikator->isi .'">';
				$komponen .= '<td style="" id="pernyataan_'. $key_indikator->id_detail_penilaian .'">'. $key_indikator->komponen .'</td>';
				$komponen .= '<td style="width: 5%; text-align: center;">'. $this->_getButton($pernyataan) .'</td>';
				$komponen .= '</tr>';
			}
		}

		$komponen .= '</table>';

		return $komponen;

	}

	public function detail_penilaian($penilaian_id = null)
	{
		$query = $this->db->get_where('detail_penilaian', [
			'md5(penilaian_id)' => $penilaian_id,
			'md5(isi)' 			=> $penilaian_id,
		])->result();

		if (count($query) > 0) {
			foreach ($query as $key) {
				$id_detail_penilaian[] = $key->id_detail_penilaian;
			}
		}

		return isset($id_detail_penilaian) ? $id_detail_penilaian : 0;
	}

}

/* End of file KomponenPenilaianModel.php */
/* Location: ./application/models/KomponenPenilaianModel.php */