<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Mahasiswa extends CI_Controller
{
     // Konstruktor
	function __construct()
    {
        parent::__construct();
        $this->load->model('M_mahasiswa');
		$this->load->model('M_users');
        $this->load->library('form_validation');
		$this->load->helper(array('form', 'url')); // Memanggil form dan url yang terdapat pada helper
		$this->load->library('upload'); // Memanggil upload yang terdapat pada helper
		$this->load->library('datatables'); // Memanggil datatables yang terdapat pada library
    }

	// Fungsi untuk menampilkan halaman mahasiswa
    public function index(){
		// Jika session data username tidak ada maka akan dialihkan kehalaman login
		if (!isset($this->session->userdata['username'])) {
			redirect(base_url("login"));
		}

		// Menampilkan data berdasarkan id-nya yaitu username
		$rowAdm = $this->M_users->get_by_id($this->session->userdata['username']);
		$dataAdm = array(
			'wa'       => 'Web administrator',
			'univ'     => 'STIKOM POLTEK CIREBON',
			'username' => $rowAdm->username,
			'email'    => $rowAdm->email,
			'level'    => $rowAdm->level,
		);

		$this->load->view('header_list', $dataAdm); // Menampilkan bagian header dan object data users
        $this->load->view('mahasiswa/mahasiswa_list'); // Menampilkan halaman utama mahasiswa
		$this->load->view('footer_list'); // Menampilkan bagian footer
    }

	// Fungsi JSON
	public function json() {
        header('Content-Type: application/json');
        echo $this->M_mahasiswa->json();
    }

	// Fungsi untuk menampilkan halaman mahasiswa secara detail
    public function read($id){
		// Jika session data username tidak ada maka akan dialihkan kehalaman login
		if (!isset($this->session->userdata['username'])) {
			redirect(base_url("login"));
		}

		// Menampilkan data berdasarkan id-nya yaitu username
		$rowAdm = $this->M_users->get_by_id($this->session->userdata['username']);
		$dataAdm = array(
			'wa'       => 'Web administrator',
			'univ'     => 'STIKOM POLTEK CIREBON',
			'username' => $rowAdm->username,
			'email'    => $rowAdm->email,
			'level'    => $rowAdm->level,
		);

		// Menampilkan data mahasiswa yang ada di database berdasarkan id-nya yaitu nim
        $row = $this->M_mahasiswa->get_by_id($id);

		// Jika data mahasiswa tersedia maka akan ditampilkan
        if ($row) {
            $data = array(
				'button' => 'Read',
				'back'   => site_url('mahasiswa'),
				'nim' => $row->nim,
				'nama_lengkap' => $row->nama_lengkap,
				'nama_panggilan' => $row->nama_panggilan,
				'alamat' => $row->alamat,
				'email' => $row->email,
				'telp' => $row->telp,
				'tempat_lahir' => $row->tempat_lahir,
				'tgl_lahir' => $row->tgl_lahir,
				'jenis_kelamin' => $row->jenis_kelamin,
				'agama' => $row->agama,
				'photo' => $row->photo,
				'id_prodi' => $row->id_prodi,
			);
            $this->load->view('header', $dataAdm); // Menampilkan bagian header dan object data users
			$this->load->view('mahasiswa/mahasiswa_read', $data); // Menampilkan halaman detail mahasiswa
			$this->load->view('footer'); // Menampilkan bagian footer
        }
		// Jika data mahasiswa tidak tersedia maka akan ditampilkan informasi 'Record Not Found'
		else {
			$this->load->view('header', $dataAdm); // Menampilkan bagian header dan object data users
            $this->session->set_flashdata('message', 'Record Not Found');
			$this->load->view('footer'); // Menampilkan bagian footer
            redirect(site_url('mahasiswa'));
        }
    }

	// Fungsi menampilkan form Create Mahasiswa
    public function create(){
		// Jika session data username tidak ada maka akan dialihkan kehalaman login
		if (!isset($this->session->userdata['username'])) {
			redirect(base_url("login"));
		}

		// Menampilkan data berdasarkan id-nya yaitu username
		$rowAdm = $this->M_users->get_by_id($this->session->userdata['username']);
		$dataAdm = array(
			'wa'       => 'Web administrator',
			'univ'     => 'STIKOM POLTEK CIREBON',
			'username' => $rowAdm->username,
			'email'    => $rowAdm->email,
			'level'    => $rowAdm->level,
		);

	  // Menampung data yang diinputkan
      $data = array(
        'button' => 'Create',
		'back'   => site_url('mahasiswa'),
        'action' => site_url('mahasiswa/create_action'),
	    'nim' => set_value('nim'),
	    'nama_lengkap' => set_value('nama_lengkap'),
	    'nama_panggilan' => set_value('nama_panggilan'),
	    'alamat' => set_value('alamat'),
	    'email' => set_value('email'),
	    'telp' => set_value('telp'),
	    'tempat_lahir' => set_value('tempat_lahir'),
	    'tgl_lahir' => set_value('tgl_lahir'),
	    'jenis_kelamin' => set_value('jenis_kelamin'),
	    'agama' => set_value('agama'),
		'photo' => set_value('photo'),
	    'id_prodi' => set_value('id_prodi'),
	  );
        $this->load->view('header',$dataAdm ); // Menampilkan bagian header dan object data users
        $this->load->view('mahasiswa/mahasiswa_form', $data); // Menampilkan halaman form mahasiswa
		$this->load->view('footer'); // Menampilkan bagian footer
    }

	// Fungsi untuk melakukan aksi simpan data
    public function create_action(){

		// Jika session data username tidak ada maka akan dialihkan kehalaman login
		if (!isset($this->session->userdata['username'])) {
			redirect(base_url("login"));
		}

        $this->_rules(); // Rules atau aturan bahwa setiap form harus diisi

		// Jika form mahasiswa belum diisi dengan benar
		// maka sistem akan meminta user untuk menginput ulang
        if ($this->form_validation->run() == FALSE) {
            $this->create();
        }
		// Jika form mahasiswa telah diisi dengan benar
		// maka sistem akan menyimpan kedalam database
		else {
			// konfigurasi untuk melakukan upload photo
			$config['upload_path']   = './images/';    //path folder image
			$config['allowed_types'] = 'jpg|png|jpeg'; //type yang dapat diupload jpg|png|jpeg
			$config['file_name']     = url_title($this->input->post('nim')); //nama file photo dirubah menjadi nama berdasarkan nim
			$this->upload->initialize($config);

			// Jika file photo ada
			if(!empty($_FILES['photo']['name'])){

				if ($this->upload->do_upload('photo')){
					$photo = $this->upload->data();
					$dataphoto = $photo['file_name'];
					$this->load->library('upload', $config);

					$data = array(
						'nim' => $this->input->post('nim',TRUE),
						'nama_lengkap' => $this->input->post('nama_lengkap',TRUE),
						'nama_panggilan' => $this->input->post('nama_panggilan',TRUE),
						'alamat' => $this->input->post('alamat',TRUE),
						'email' => $this->input->post('email',TRUE),
						'telp' => $this->input->post('telp',TRUE),
						'tempat_lahir' => $this->input->post('tempat_lahir',TRUE),
						'tgl_lahir' => $this->input->post('tgl_lahir',TRUE),
						'jenis_kelamin' => $this->input->post('jenis_kelamin',TRUE),
						'agama' => $this->input->post('agama',TRUE),
						'photo' => $dataphoto,
						'id_prodi' => $this->input->post('id_prodi',TRUE),
					);

					$this->M_mahasiswa->insert($data);
				}

				$this->session->set_flashdata('message', 'Create Record Success');
				redirect(site_url('mahasiswa'));
			}
			// Jika file photo kosong
			else{

				$data = array(
					'nim' => $this->input->post('nim',TRUE),
					'nama_lengkap' => $this->input->post('nama_lengkap',TRUE),
					'nama_panggilan' => $this->input->post('nama_panggilan',TRUE),
					'alamat' => $this->input->post('alamat',TRUE),
					'email' => $this->input->post('email',TRUE),
					'telp' => $this->input->post('telp',TRUE),
					'tempat_lahir' => $this->input->post('tempat_lahir',TRUE),
					'tgl_lahir' => $this->input->post('tgl_lahir',TRUE),
					'jenis_kelamin' => $this->input->post('jenis_kelamin',TRUE),
					'agama' => $this->input->post('agama',TRUE),
					'id_prodi' => $this->input->post('id_prodi',TRUE),
				);

				$this->M_mahasiswa->insert($data);
				$this->session->set_flashdata('message', 'Create Record Success');
				redirect(site_url('mahasiswa'));
			}

        }
    }

	// Fungsi menampilkan form Update Mahasiswa
    public function update($id){
		// Jika session data username tidak ada maka akan dialihkan kehalaman login
		if (!isset($this->session->userdata['username'])) {
			redirect(base_url("login"));
		}

		// Menampilkan data berdasarkan id-nya yaitu username
		$rowAdm = $this->M_users->get_by_id($this->session->userdata['username']);
		$dataAdm = array(
			'wa'       => 'Web administrator',
			'univ'     => 'STIKOM POLTEK CIREBON',
			'username' => $rowAdm->username,
			'email'    => $rowAdm->email,
			'level'    => $rowAdm->level,
		);

		// Menampilkan data berdasarkan id-nya yaitu nim
        $row = $this->M_mahasiswa->get_by_id($id);

		// Jika id-nya dipilih maka data mahasiswa ditampilkan ke form edit mahasiswa
        if ($row) {
            $data = array(
                'button' => 'Update',
				'back'   => site_url('mahasiswa'),
                'action' => site_url('mahasiswa/update_action'),
				'nim' => set_value('nim', $row->nim),
				'nama_lengkap' => set_value('nama_lengkap', $row->nama_lengkap),
				'nama_panggilan' => set_value('nama_panggilan', $row->nama_panggilan),
				'alamat' => set_value('alamat', $row->alamat),
				'email' => set_value('email', $row->email),
				'telp' => set_value('telp', $row->telp),
				'tempat_lahir' => set_value('tempat_lahir', $row->tempat_lahir),
				'tgl_lahir' => set_value('tgl_lahir', $row->tgl_lahir),
				'jenis_kelamin' => set_value('jenis_kelamin', $row->jenis_kelamin),
				'agama' => set_value('agama', $row->agama),
				'photo' => set_value('photo', $row->photo),
				'id_prodi' => set_value('id_prodi', $row->id_prodi),
			);
		    $this->load->view('header',$dataAdm); // Menampilkan bagian header dan object data users
            $this->load->view('mahasiswa/mahasiswa_form', $data); // Menampilkan form mahasiswa
			$this->load->view('footer'); // Menampilkan bagian footer
        }
		// Jika id-nya yang dipilih tidak ada maka akan menampilkan pesan 'Record Not Found'
		else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('mahasiswa'));
        }
    }

	// Fungsi untuk melakukan aksi update data
    public function update_action(){

		// Jika session data username tidak ada maka akan dialihkan kehalaman login
		if (!isset($this->session->userdata['username'])) {
			redirect(base_url("login"));
		}

        $this->_rules(); // Rules atau aturan bahwa setiap form harus diisi

		// Jika form mahasiswa belum diisi dengan benar
		// maka sistem akan meminta user untuk menginput ulang
        if ($this->form_validation->run() == FALSE) {
            $this->update($this->input->post('nim', TRUE));
        }
		// Jika form mahasiswa telah diisi dengan benar
		// maka sistem akan melakukan update data mahasiswa kedalam database
		else{
			// Konfigurasi untuk melakukan upload photo
			$config['upload_path']   = './images/';    //path folder
			$config['allowed_types'] = 'jpg|png|jpeg'; //type yang dapat diupload jpg|png|jpeg
			$config['file_name']     = url_title($this->input->post('nim')); //nama file photo dirubah menjadi nama berdasarkan nim
			$this->upload->initialize($config);

			// Jika file photo ada
			if(!empty($_FILES['photo']['name'])){

				// Menghapus file image lama
				unlink("./images/".$this->input->post('photo'));

				// Upload file image baru
				if ($this->upload->do_upload('photo')){
					$photo = $this->upload->data();
					$dataphoto = $photo['file_name'];
					$this->load->library('upload', $config);

					// Menampung data yang diinputkan
					$data = array(
						'nim' => $this->input->post('nim',TRUE),
						'nama_lengkap' => $this->input->post('nama_lengkap',TRUE),
						'nama_panggilan' => $this->input->post('nama_panggilan',TRUE),
						'alamat' => $this->input->post('alamat',TRUE),
						'email' => $this->input->post('email',TRUE),
						'telp' => $this->input->post('telp',TRUE),
						'tempat_lahir' => $this->input->post('tempat_lahir',TRUE),
						'tgl_lahir' => $this->input->post('tgl_lahir',TRUE),
						'jenis_kelamin' => $this->input->post('jenis_kelamin',TRUE),
						'agama' => $this->input->post('agama',TRUE),
						'photo' => $dataphoto,
						'id_prodi' => $this->input->post('id_prodi',TRUE),
					);

					$this->M_mahasiswa->update($this->input->post('nim', TRUE), $data);
				}

				$this->session->set_flashdata('message', 'Update Record Success');
				redirect(site_url('mahasiswa'));
			}
			// Jika file photo kosong
			else{
				// Menampung data yang diinputkan
				$data = array(
					'nim' => $this->input->post('nim',TRUE),
					'nama_lengkap' => $this->input->post('nama_lengkap',TRUE),
					'nama_panggilan' => $this->input->post('nama_panggilan',TRUE),
					'alamat' => $this->input->post('alamat',TRUE),
					'email' => $this->input->post('email',TRUE),
					'telp' => $this->input->post('telp',TRUE),
					'tempat_lahir' => $this->input->post('tempat_lahir',TRUE),
					'tgl_lahir' => $this->input->post('tgl_lahir',TRUE),
					'jenis_kelamin' => $this->input->post('jenis_kelamin',TRUE),
					'agama' => $this->input->post('agama',TRUE),
					'id_prodi' => $this->input->post('id_prodi',TRUE),
				);

				$this->M_mahasiswa->update($this->input->post('nim', TRUE), $data);
				$this->session->set_flashdata('message', 'Update Record Success');
				redirect(site_url('mahasiswa'));
			}

        }
    }

	// Fungsi untuk melakukan aksi delete data berdasarkan id yang dipilih
    public function delete($id){
		// Jika session data username tidak ada maka akan dialihkan kehalaman login
		if (!isset($this->session->userdata['username'])) {
			redirect(base_url("login"));
		}

        $row = $this->M_mahasiswa->get_by_id($id);

		//jika id nim yang dipilih tersedia maka akan dihapus
        if ($row) {
			// menghapus data berdasarkan id-nya yaitu nim
			if($this->M_mahasiswa->delete('mahasiswa',array('nim'->$id))){

				// menampilkan informasi 'Delete Record Success' setelah data mahasiswa dihapus
				$this->session->set_flashdata('message', 'Delete Record Success');

				// menghapus file photo
				unlink("./images/".$row->photo);
			}
			// jika data tidak ada yang dihapus maka akan menampilkan 'Can not Delete This Record !'
			else{

				$this->session->set_flashdata('message', 'Can not Delete This Record !');
			}
            redirect(site_url('mahasiswa'));

        }
		//jika id nim yang dipilih tidak tersedia maka akan muncul pesan 'Record Not Found'
		else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('mahasiswa'));
        }
    }

	// Fungsi rules atau aturan untuk pengisian pada form (create/input dan update)
    public function _rules()
    {
	$this->form_validation->set_rules('nim', 'nim', 'trim|required');
	$this->form_validation->set_rules('nama_lengkap', 'nama lengkap', 'trim|required');
	$this->form_validation->set_rules('nama_panggilan', 'nama panggilan', 'trim|required');
	$this->form_validation->set_rules('alamat', 'alamat', 'trim|required');
	$this->form_validation->set_rules('email', 'email', 'trim|required');
	$this->form_validation->set_rules('telp', 'telp', 'trim|required');
	$this->form_validation->set_rules('tempat_lahir', 'tempat lahir', 'trim|required');
	$this->form_validation->set_rules('tgl_lahir', 'tgl lahir', 'trim|required');
	$this->form_validation->set_rules('jenis_kelamin', 'jenis kelamin', 'trim|required');
	$this->form_validation->set_rules('agama', 'agama', 'trim|required');
	$this->form_validation->set_rules('id_prodi', 'id prodi', 'trim|required');
	$this->form_validation->set_rules('nim', 'nim', 'trim');
	$this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
    }

}