<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Auth extends CI_Controller
{

    /**
     * Index Page for this controller.
     *
     * Maps to the following URL
     * 		http://example.com/index.php/welcome
     *	- or -
     * 		http://example.com/index.php/welcome/index
     *	- or -
     * Since this controller is set as the default controller in
     * config/routes.php, it's displayed at http://example.com/
     *
     * So any other public methods not prefixed with an underscore will
     * map to /index.php/welcome/<method_name>
     * @see https://codeigniter.com/userguide3/general/urls.html
     */


    public function index()
    {

        if ($this->session->userdata('role')) {
            // Jika session sudah ada, lempar ke Controller Dashboard
            redirect('dashboard');
        }

        // Jika belum login, tampilkan halaman login
        $this->load->view('adminlte/login');
    }

    public function loginProcess()
    {
        $email = $_POST['email'];
        $password = $_POST['password'];

        $user = $this->userModel->findByEmail($email);

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['login'] = true;
            $_SESSION['user_id'] = $user['id'];

            header('Location: /dashboard');
        } else {
            $_SESSION['error'] = 'Email atau password salah';
            header('Location: /login');
        }
    }
}
