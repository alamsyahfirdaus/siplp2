<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class AllModel extends CI_Model {

	public function getUser()
	{
		$this->db->join('jenis_pengguna jp', 'jp.id_jenis_pengguna = p.jenis_pengguna_id', 'left');
		$this->db->where('p.id_pengguna', $this->session->id_pengguna);
		return $this->db->get('pengguna p')->row();
	}

	public function getSetting($id)
	{
		$query 	= $this->db->get_where('pengaturan', ['id_pengaturan' => $id])->row();
		$data 	= array(
			'nama' 		=> $query->pengaturan,
			'gambar' 	=> site_url(IMAGE . $this->include->image($query->gambar)),
		);

		return $data;
	}

	public function hak_akses()
	{   
	    $menu  = $this->db->get_where('menu', ['url' => $this->uri->segment(1)])->row();

	    if ($menu) {
	    	$akses_menu = $this->db->get_where('akses_menu', [
	    		'menu_id'			=> $menu->id_menu,
	    		'jenis_pengguna_id'	=> $this->session->id_jenis_pengguna,
	    	])->num_rows();

	    	if (!$akses_menu) {
	    		redirect(site_url());
	    	}

	    } else {
	    	redirect(site_url());
	    }
	}

}

/* End of file AllModel.php */
/* Location: ./application/models/AllModel.php */