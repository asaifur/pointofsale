<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Auth extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('User_model');
    }

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
    public function proses_login()
    {
        $username = $this->input->post('email');
        $password = $this->input->post('password');

        $user = $this->User_model->check_login($username);

        if ($user) {

            // =========================
            // CEK STATUS AKTIF
            // =========================
            if ($user['active'] != 1) {

                echo json_encode([
                    'status'  => 'inactive',
                    'message' => 'Akun belum aktif! Silakan verifikasi OTP terlebih dahulu.',
                    'email'   => $user['email']
                ]);
                return;
            }

            // =========================
            // CEK PASSWORD
            // =========================
            if (password_verify($password, $user['password'])) {

                // Simpan session
                $this->session->set_userdata($user);

                $response = [
                    'status'  => 'success',
                    'message' => 'Login berhasil',
                    'nama'    => $user['username'],
                    'salam'   => waktu()
                ];

                echo json_encode($response);
            } else {

                echo json_encode([
                    'status'  => 'error',
                    'message' => 'Password Salah!'
                ]);
            }
        } else {

            echo json_encode([
                'status'  => 'error',
                'message' => 'Email tidak terdaftar!'
            ]);
        }
    }
}
