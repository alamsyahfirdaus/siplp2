<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class PenilaianPembimbingModel extends CI_Model {

	private $table 			= 'detail_penilaian';
	private $columnOrder	= ['id_detail_penilaian', NULL];
	private $columnSearch	= ['id_detail_penilaian'];
	private $orderBy		= ['id_detail_penilaian' => 'ASC'];

	private function _setBuilder()
	{
		$id_detail_penilaian = $this->_id_detail_penilaian();
		if ($id_detail_penilaian) {
			$this->db->where_in('id_detail_penilaian', $id_detail_penilaian);
		}
		$this->db->join('penilaian', 'penilaian.id_penilaian = detail_penilaian.penilaian_id', 'left');
		$this->db->where('penilaian_id', $this->input->post('penilaian_id'));
		$this->db->from($this->table);
		$this->include->setDataTables($this->columnOrder, $this->columnSearch, $this->orderBy);
	}

	public function getDataTables()
	{
		$query 	= $this->include->getResult($this->_setBuilder());
		$data 	= array();
		$start 	= $this->input->post('start');
		$no  	= $start > 0 ? $start + 1 : 1;
		$alpha  = 'A';
		foreach ($query as $field) {
			$start++;
			$row 	= array();

			if ($field->isi) {
				$row[]  = '<p style="text-align: center; font-weight: bold;">'. $alpha++ .'</p>';

				$komponen = '<p style="text-align: left; font-weight: bold;">'. $field->komponen .'</p>';
				$komponen .= $this->_komponen($field, $no++);

				$skor = '<p style="text-align: center; color: #FFFFFF; font-weight: bold;">#</p>';
				$skor .= $this->_setSkor($field);

				$row[]  = $komponen;
				$row[]  = $skor;

			} else {
				$row[]  = '<p style="text-align: center;">'. $no++ .'</p>';
				$row[]  = '<p style="text-align: left;">'. $field->komponen .'</p>';
				$row[]  = $this->_setInput($field);

				$id_detail_penilaian[] = $field->id_detail_penilaian;
			}

			$data[]	= $row;
		}

		$idp  		= array();
		$idp  		= isset($id_detail_penilaian) ? $id_detail_penilaian : $this->_detail_penilaian();
		$query 		= $this->_hasil_penilaian($idp);
		$ihp  		= $query['id_hasil_penilaian'] != null ? count($query['id_hasil_penilaian']) : 0;
		$dpi 		= $idp > 0 ? count($idp) : 0;
		$update 	= md5($this->input->post('id_pengguna_mhs')) . '/' . md5($this->input->post('penilaian_id'));

		return array(
			'draw' 					=> $this->input->post('draw'),
			'recordsTotal'			=> $this->db->count_all_results($this->table),
			'recordsFiltered' 		=> $this->db->get($this->_setBuilder())->num_rows(),
			'data' 					=> $data,
			'id_hasil_penilaian'	=> $query['id_hasil_penilaian'],
			'selesai'				=> $dpi != $ihp || $query['id_selesai'] > 0 ? 1 : 0,
			'checked'				=> $dpi == $ihp && $query['id_selesai'] > 0 ? 1 : $update,
		);
	}

	private function _setInput($field)
	{	
		$query 	= $this->_hasil_penilaian($field->id_detail_penilaian);

		$radio = '<div style="text-align: center;">';
		$radio .= '<div class="form-group clearfix">';
		for ($i = 1; $i <= $field->skala; $i++) { 

			$checked 	= $query['skor'] == $i ? 'checked' : '';
			$disabled	= $query['selesai'] == 1 ? 'disabled' : '';

			$radio .= '<div class="icheck-primary d-inline">';
			$radio .= '<input type="radio" name="skor_'. $field->id_detail_penilaian .'" id="skor_'. $field->id_detail_penilaian .'_'. $i .'" onclick="add_data('. $i .', '. $field->id_detail_penilaian .', '. $query['ihp'] .')" '. $checked .' '. $disabled .'>';
			$radio .= '<label for="skor_'. $field->id_detail_penilaian .'_'. $i .'" style="margin-right: 8px;">'. $i .'</label>';
			$radio .= '</div>';
		}
		$radio .= '</div>';
		$radio .= '</div>';

		return $radio;
	}

	public function _hasil_penilaian($detail_penilaian_id = null)
	{
		if (is_array($detail_penilaian_id)) {
			$this->db->where_in('detail_penilaian_id', $detail_penilaian_id);
		} else {
			$this->db->where('detail_penilaian_id', $detail_penilaian_id);
		}

		$this->db->where('tahun_pelaksanaan_id', $this->input->post('tahun_pelaksanaan_id'));
		$this->db->where('id_pengguna_mhs', $this->input->post('id_pengguna_mhs'));
		$this->db->where('id_pengguna_penilai', $this->input->post('id_pengguna_penilai'));

		$query = $this->db->get('hasil_penilaian');

		$id_detail_penilaian = array();
		foreach ($query->result() as $key) {
			$id_hasil_penilaian[] = $key->id_hasil_penilaian;

			if ($key->selesai == 1) {
				$id_selesai[] = $key->id_hasil_penilaian;
			}
		}

		if ($query->num_rows()) {
			$ihp 		= $query->row()->id_hasil_penilaian;
			$skor 		= $query->row()->skor;
			$selesai 	= $query->row()->selesai;
		}

		return array(
			'id_hasil_penilaian' 	=> @$id_hasil_penilaian,
			'id_selesai'			=> @$id_hasil_penilaian == @$id_selesai ? 1 : 0,
			'ihp' 					=> @$ihp ? $ihp : 0,
			'skor' 					=> @$skor,
			'selesai' 				=> @$selesai,
		);
	}

	private function _komponen($field, $no)
	{
		$komponen = '<table class="table table-striped">';

		$no_aspek = $no == 1 ? $no : $no + 1;

		foreach ($this->db->get_where($this->table, ['isi' => $field->id_detail_penilaian])->result() as $key_aspek) {

			$komponen .= '<tr>';
			$komponen .= '<th style="width: 5%; text-align: center;">'. $no_aspek++ .'.</th>';
			$komponen .= '<th>'. $key_aspek->komponen .'</th>';
			$komponen .= '</tr>';

			foreach ($this->db->get_where($this->table, ['isi' => $key_aspek->id_detail_penilaian])->result() as $key_indikator) {

				$komponen .= '<tr><td colspan="2">'. $key_indikator->komponen .'</td></tr>';
			}
		}

		$komponen .= '</table>';

		return $komponen;

	}

	private function _setSkor($field)
	{
		$skor = '<table class="table">';

		foreach ($this->db->get_where($this->table, ['isi' => $field->id_detail_penilaian])->result() as $key_aspek) {
			$skor .= '<tr><th style="border-top: none; color: #FFFFFF; padding-bottom: 0px;"><div style="text-align: center;"><div class="form-group clearfix">#</div></div></th></tr>';

			foreach ($this->db->join('penilaian', 'penilaian.id_penilaian = detail_penilaian.penilaian_id', 'left')->get_where($this->table, ['isi' => $key_aspek->id_detail_penilaian])->result() as $key_indikator) {

				$skor .= '<tr><td style="border-top: none; padding-bottom: 0px;">'. $this->_setInput($key_indikator) .'</td></tr>';


			}
		}

		$skor .= '</table>';

		return $skor;

	}

	private function _id_detail_penilaian()
	{
		$query = $this->db->get_where('detail_penilaian', [
			'penilaian_id' 	=> $this->input->post('penilaian_id'),
			'isi' 			=> $this->input->post('penilaian_id'),
		])->result();

		$id_detail_penilaian = array();
		foreach ($query as $key) {
			$id_detail_penilaian[] = $key->id_detail_penilaian;
		}

		return count($id_detail_penilaian) ? $id_detail_penilaian : 0;
	}

	private function _detail_penilaian()
	{
		$id_detail_penilaian = $this->_id_detail_penilaian();

		if ($id_detail_penilaian) {

			foreach ($this->db->where_in('isi', $id_detail_penilaian)->get($this->table)->result() as $key_aspek) {
				
				foreach ($this->db->get_where($this->table, ['isi' => $key_aspek->id_detail_penilaian])->result() as $key_indikator) {

					$id[] = $key_indikator->id_detail_penilaian;

				}
			}

			return @$id;

		} else {
			return 0;
		}

	}

	public function get_penilaian($program_studi_id)
	{
		# 1 = Administrator (DPL & GPL)
		
		$table 			= 'penilaian';
		$column_order 	= ['id_penilaian', NULL];
		$column_search  = ['id_penilaian'];
		$order_by 		= ['id_penilaian' => 'desc'];

		$this->db->where_in('jenis_pengguna_id', [1, $this->session->id_jenis_pengguna]);
		$this->db->where('md5(program_studi_id)', $program_studi_id);
		
		$this->db->from($table);
		$result = $this->include->setDataTables($column_order, $column_search, $order_by);

		return array(
			'result' 	=> $this->include->getResult($result),
			'count' 	=> $this->db->count_all_results($table),
		);
	}

}

/* End of file PenilaianPembimbingModel.php */
/* Location: ./application/models/PenilaianPembimbingModel.php */