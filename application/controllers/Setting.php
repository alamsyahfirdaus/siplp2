<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Setting extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		logged_in();
		$this->mall->hak_akses();
	}

	private $folder = 'Pengaturan';


	public function index()
	{
		redirect(site_url());
	}

	public function menu()
	{
		$data = array(
			'folder'	=> $this->folder,
			'title' 	=> 'Menu'
		);
		$this->include->view('index_menu', $data);
	}

	public function showMenu()
	{
		$this->form_validation->set_rules('id', '', 'trim|required');

		if ($this->form_validation->run() == FALSE) {
			redirect('setting/menu');
		} else {
			$this->load->model('MenuModel', 'menu');
			$data = $this->menu->getDataTables();
			echo json_encode($data);
		}

	}

	public function hakAkses()
	{
		$this->form_validation->set_rules('id_menu', '', 'trim|required');
		$this->form_validation->set_rules('id_jenis_pengguna', '', 'trim|required');

		if ($this->form_validation->run() == FALSE) {
			redirect('setting/menu');
		} else {
			$data = array(
				'menu_id' 			=> $this->input->post('id_menu'),
				'jenis_pengguna_id'	=> $this->input->post('id_jenis_pengguna')
			);

			$query = $this->db->get_where('akses_menu', $data)->row();

			if ($query) {
				$action = $this->db->delete('akses_menu', ['id_akses_menu' => $query->id_akses_menu]);
			} else {
				$action = $this->db->insert('akses_menu', $data);
			}

			echo json_encode($action);
		}

	}

	public function changeAktivasi($id_sub_menu = NULL)
	{
		$query = $this->db->get_where('sub_menu', ['md5(id_sub_menu)' => $id_sub_menu])->row();

		if (!$query) {
			redirect('setting/menu');
		}

		$data 	= array('aktivasi' => $query->aktivasi == 1 ? 0 : 1);
		$action = $this->db->update('sub_menu', $data, ['id_sub_menu' => $query->id_sub_menu]);

		if ($action) {
			$aktivasi = $this->db->get_where('sub_menu', ['aktivasi' => 1])->num_rows();
			if (!$aktivasi) {
				foreach ($this->db->get_where('akses_menu', ['menu_id' => $query->menu_id])->result() as $row) {
					$this->db->delete('akses_menu', ['id_akses_menu' => $row->id_akses_menu]);
				}
			}
		}

		echo json_encode($action);
	}

	public function sortMenu($id_menu = NULL)
	{
		$query = $this->db->get_where('menu', ['md5(id_menu)' => $id_menu])->row();

		if (!$query) {
			redirect('setting/menu');
		}

		$action = $this->db->update('menu', ['sort_by' => time()], ['id_menu' => $query->id_menu]);
		echo json_encode($action);
	}

	public function sortSubMenu($id_sub_menu = NULL)
	{
		$query = $this->db->get_where('sub_menu', ['md5(id_sub_menu)' => $id_sub_menu])->row();

		if (!$query) {
			redirect('setting/menu');
		}

		$action = $this->db->update('sub_menu', ['sort_by' => time()], ['id_sub_menu' => $query->id_sub_menu]);
		echo json_encode($action);
	}

	public function other()
	{
		$data = array(
			'folder'	=> $this->folder,
			'title' 	=> 'Website'
		);
		$this->include->view('index_pengaturan', $data);
	}

	public function showPengaturan()
	{
		$this->form_validation->set_rules('id', '', 'trim|required');

		if ($this->form_validation->run() == FALSE) {
			redirect('setting/other');
		} else {
			$this->load->model('PengaturanModel', 'pengaturan');
			$data = $this->pengaturan->getDataTables();
			echo json_encode($data);
		}
	}

	public function updatePengaturan()
	{
		$this->form_validation->set_rules('id_pengaturan', '', 'trim|required');

		if ($this->form_validation->run() == FALSE) {
			redirect('setting/other');
		} else {
			$query = $this->db->get_where('pengaturan', ['id_pengaturan' => $this->input->post('id_pengaturan')])->row();

			if ($this->input->post('pengaturan')) {
				 $data['pengaturan'] = ucwords($this->input->post('pengaturan'));
			}

			$this->_do_upload();

			if ($this->upload->do_upload('gambar')) {
			    if (@$query->gambar) {
			        unlink(IMAGE . $query->gambar);
			    }

			    $data['gambar'] = $this->upload->data('file_name');
			}


			$this->db->update('pengaturan', $data, ['id_pengaturan' => $query->id_pengaturan]);

			$this->session->set_flashdata('alert', 'BERHASIL MENGUBAH ' . str_replace("_", " ", $query->nama));
			redirect('setting/other');
		}
	}

	private function _do_upload()
	{
        $config['upload_path']   = IMAGE;
        $config['allowed_types'] = 'gif|jpg|png|jpeg|bmp|GIF|JPG|PNG|JPEG|BMP|';
        $config['max_size']    	 = 10000;
        $config['max_width']   	 = 10000;
        $config['max_height']  	 = 10000;
        $config['file_name']     = time();
        $this->upload->initialize($config);
	}

	public function saveMenu()
	{
		$this->form_validation->set_rules('menu', '', 'trim|required');
		$this->form_validation->set_rules('icon', '', 'trim|required');

		if ($this->form_validation->run() == FALSE) {
			redirect('setting/menu');
		} else {
			$query = $this->db->get_where('menu', ['id_menu' => $this->input->post('id_menu')])->row();

			$data = array(
				'menu' 		=> ucwords($this->input->post('menu')),
				'icon' 		=> $this->input->post('icon'),
				'url'		=> $this->input->post('url') ? $this->input->post('url') : NULL,
			);

			if ($query) {
				$this->db->update('menu', $data, ['id_menu' => $query->id_menu]);
				$message = 'Berhasil Mengubah Menu';
			} else {
				$data['sort_by'] = time();
				$this->db->insert('menu', $data);
				$message = 'Berhasil Menambah Menu';
			}

			if ($this->db->affected_rows()) {
				$output['message'] = $message;
			}

			$output['status'] = TRUE;

			echo json_encode($output);

		}

	}

	public function deleteMenu($id_menu = NULL)
	{
		$query = $this->db->get_where('menu', ['md5(id_menu)' => $id_menu])->row();

		if (!$query) {
			redirect('setting/menu');
		}

		$this->db->delete('menu', ['id_menu' => $query->id_menu]);
		echo json_encode(['status' => TRUE]);
	}

	public function deleteSubMenu($id_sub_menu = NULL)
	{
		$query = $this->db->get_where('sub_menu', ['md5(id_sub_menu)' => $id_sub_menu])->row();

		if (!$query) {
			redirect('setting/menu');
		}

		$this->db->delete('sub_menu', ['id_sub_menu' => $query->id_sub_menu]);

		$output = array(
			'status' 	=> TRUE,
			'message' 	=> 'Berhasil Menghapus Sub Menu',
		);
		echo json_encode($output);

	}

	public function getMenu()
	{
		foreach ($this->db->get('menu')->result() as $row) {
			$data[] = array(
				'id_menu' 	=> $row->id_menu,
				'menu'		=> $row->menu 
			);
		}

		echo json_encode($data);
	}

	public function saveSubMenu()
	{
		$this->form_validation->set_rules('sub_menu', '', 'trim|required');
		$this->form_validation->set_rules('url', '', 'trim|required');
		$this->form_validation->set_rules('menu_id', '', 'trim|required');

		if ($this->form_validation->run() == FALSE) {
			redirect('setting/menu');
		} else {
			$query = $this->db->get_where('sub_menu', ['id_sub_menu' => $this->input->post('id_sub_menu')])->row();

			$data = array(
				'sub_menu' 		=> ucwords($this->input->post('sub_menu')),
				'url' 			=> $this->input->post('url'),
				'menu_id'		=> $this->input->post('menu_id'),
			);

			if ($query) {
				$this->db->update('sub_menu', $data, ['id_sub_menu' => $query->id_sub_menu]);
				$message = 'Berhasil Mengubah Sub Menu';
			} else {
				$data['sort_by'] 	= time();
				$data['aktivasi'] 	= 1;
				$this->db->insert('sub_menu', $data);
				$message = 'Berhasil Menambah Sub Menu';
			}

			if ($this->db->affected_rows()) {
				$output['message'] = $message;
			}

			$output['status'] = TRUE;

			echo json_encode($output);

		}

	}

}

/* End of file Setting.php */
/* Location: ./application/controllers/Setting.php */