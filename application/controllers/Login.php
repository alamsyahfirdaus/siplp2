<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {

	public function __construct()
	{
		parent::__construct();

	}

	public function index()
	{	
		logged_out();

		$this->form_validation->set_rules('id_pengguna', 'Akun Pengguna', 'trim|required');
		$this->form_validation->set_rules('password', 'Password', 'trim|required');

		if ($this->form_validation->run() == FALSE) {
			$this->include->auth('index_login');
		} else {
			$this->_login();
		}
	}

	private function _login()
	{
		$email = $this->_get_data([
			'email' 	=> $this->input->post('id_pengguna'),
			'password'	=> sha1($this->input->post('password'))
		]);

		$no_induk = $this->_get_data([
			'no_induk' 	=> $this->input->post('id_pengguna'),
			'password'	=> sha1($this->input->post('password'))
		]);

		if ($email) {
			$query = $email;
		} elseif ($no_induk) {
			$query = $no_induk;
		} else {
			$query = NULL;
		}

		if ($query) {


			if ($query->password == sha1($this->input->post('password'))) {

				// if ($query->status_pendaftaran == 1) {

					$this->db->update('pengguna', ['login_terakhir' => date('Y-m-d H:i:s')], ['id_pengguna' => $query->id_pengguna]);

					$this->session->set_userdata([
						'id_pengguna' 			=> $query->id_pengguna,
						'id_jenis_pengguna' 	=> $query->jenis_pengguna_id,
					]);

					$output = array('status' => TRUE);

				// } else {
				// 	$output = [
				// 		'status' 	=> FALSE,
				// 		'message'	=> 'LOGIN GAGAL',
				// 	];
				// }

			} else {
				$output = [
					'status' 	=> FALSE,
					'errors'	=> array('password' => 'Password salah')
				];
			}
			
		} else {
			$output = [
				'status' 	=> FALSE,
				'message'	=> 'AKUN PENGGUNA TIDAK DITEMUKAN',
			];
		}

		echo json_encode($output);
	}

	private function _get_data($where)
	{
		return $this->db->get_where('pengguna', $where)->row();
	}


	public function recovery()
	{
		logged_out();

		$this->form_validation->set_rules('email', 'Email', 'trim|required');

		if ($this->form_validation->run() == FALSE) {
			$this->include->auth('forgot_password');
		} else {
			$this->_forgot_password();
		}
		
	}

	private function _forgot_password()
	{
		$query = $this->_get_data(['email' => $this->input->post('email')]);

		if ($query) {

			$data = [
				'to' 		=> $query->email,
				'subject' 	=> 'Reset Password',
				'message' 	=> 'Hi, ' . $query->nama_lengkap . '. <br><a href="' . site_url('login/reset/' . md5($query->id_pengguna) . '/' . md5($query->email)) . '">Reset Password</a>',
			];

			$send_email = $this->_send_email($data);

			if ($send_email) {
				$output = [
					'status' 	=> TRUE,
					'message' 	=> 'BERHASIL MENGIRIM EMAIL'
				];
			} else {
				$output = [
					'status' 	=> FALSE,
					'message' 	=> 'RESET PASSWORD GAGAL'
				];
			}

		} else {
			$output = [
				'status' 	=> FALSE,
				'errors'	=> array('email' => 'Email belum terdaftar')
			];
		}

		echo json_encode($output);

	}

	private function _send_email($data)
	{
		$website 	= $this->mall->getSetting(2)['nama'];
		$smtp_host 	= $this->mall->getSetting(6)['nama'];
		$smtp_user 	= $this->mall->getSetting(7)['nama'];
		$smtp_pass 	= $this->mall->getSetting(8)['nama'];

		$config = [
			'protocol' 	=> 'smtp', 
			'smtp_host' => $smtp_host,
			'smtp_user' => $smtp_user,
			'smtp_pass' => $smtp_pass,
			'smtp_port' => '465',
			'mailtype'	=> 'html',
			'chaset'	=> 'utf-8',
			'newline'	=> "\r\n"
		];

		$this->load->library('email', $config);
		$this->email->initialize($config);
		
		$this->email->from($smtp_user, $website);
		$this->email->to($data['to']);
		$this->email->subject($data['subject']);
		$this->email->message($data['message']);

		// echo $this->email->send() ? TRUE : show_error($this->email->print_debugger());

		return $this->email->send() ? TRUE : FALSE;
	}

	public function reset($id_pengguna = NULL, $email = NULL)
	{
		$where = [
			'md5(id_pengguna)' 	=> $id_pengguna,
			'md5(email)'		=> $email
		];

		$query = $this->_get_data($where);

		if ($query) {
			$this->include->auth('reset_password', ['id_pengguna' => sha1($query->id_pengguna)]);
		} else {
			redirect('login/recovery');
		}

	}

	public function update($id_pengguna = NULL)
	{
		# Change Password

		$query = $this->_get_data(['sha1(id_pengguna)' => $id_pengguna]);

		$this->form_validation->set_rules('password1', 'Password', 'trim|required|min_length[6]');
		$this->form_validation->set_rules('password2', 'Konfirmasi Password', 'trim|required|min_length[6]|matches[password1]');

		if ($this->form_validation->run() == FALSE && !$query) {
			redirect('login/recovery');
		} else {
			$this->db->update('pengguna', ['password' => sha1($this->input->post('password1'))], ['id_pengguna' => $query->id_pengguna]);

			$output = [
				'status' 	=> TRUE,
				'message'	=> 'BERHASIL MENGUBAH PASSWORD'
			];

			echo json_encode($output);
		}
	}

	public function logout()
	{
		$this->session->sess_destroy();
		redirect('login');
	}

	public function register()
	{
		logged_out();
		$this->include->auth('pendaftaran_mahasiswa');
	}

	public function add_student()
	{
		$this->form_validation->set_error_delimiters('', '');
		$this->form_validation->set_rules('no_induk', 'NIM', 'trim|required|alpha_numeric|is_unique[pengguna.no_induk]');
		$this->form_validation->set_rules('nama_lengkap', 'Nama Lengkap', 'trim|required|alpha_numeric_spaces');
		$this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|is_unique[pengguna.email]');
		$this->form_validation->set_rules('password1', 'Password', 'trim|required');
		$this->form_validation->set_rules('password2', 'Konfirmasi Password', 'trim|required');
		$this->form_validation->set_message('is_unique', '{field} sudah terdaftar');
		$this->form_validation->set_message('alpha_numeric_spaces', '{field} tidak valid');
		$this->form_validation->set_message('valid_email', '{field} tidak valid');

		if ($this->form_validation->run() == FALSE) {
			$output = [
			    'status' => FALSE,
			    'errors' => array(
			        'no_induk' 		=> form_error('no_induk'),
			        'nama_lengkap' 	=> form_error('nama_lengkap'),
			        'email' 		=> form_error('email'),
			        'password1' 	=> form_error('password1'),
			        'password1' 	=> form_error('password2'),
			    )
			];
		} else {

			$nama_lengkap = ucwords($this->input->post('nama_lengkap'));

			$data = array(
				'no_induk' 				=> htmlspecialchars($this->input->post('no_induk')),
				'nama_lengkap' 			=> htmlspecialchars($nama_lengkap),
				'email'					=> htmlspecialchars($this->input->post('email')),
				'jenis_pengguna_id'		=> 5,
				'password'				=> sha1($this->input->post('password2')),
				'tanggal_pendaftaran'	=> date('Y-m-d H:i:s')
			);

			$pengguna = $this->db->insert('pengguna', $data);

			if ($pengguna) {
				$this->db->insert('mahasiswa', ['pengguna_id' => $this->db->insert_id()]);
			}

			$output = array(
				'status' 	=> TRUE,
				'message'	=> 'PENDAFTARAN BERHASIL, SILAKAN LOGIN!'
			);
		}

		echo json_encode($output);
	}

}

/* End of file Login.php */
/* Location: ./application/controllers/Login.php */
