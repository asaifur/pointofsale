<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Auth extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('User_model');
        $this->load->library('form_validation');
        $this->load->library('email');
    }

    // ===========================
    // LOGIN PAGE
    // ===========================
    public function index()
    {
        if ($this->session->userdata('role')) {
            redirect('dashboard');
        }

        $this->load->view('auth/login');
    }

    // ===========================
    // PROSES LOGIN
    // ===========================
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

    // ===========================
    // REGISTER PAGE
    // ===========================
    public function register()
    {
        if ($this->session->userdata('role')) {
            redirect('dashboard');
        }

        $this->load->view('auth/register');
    }

    // ===========================
    // PROSES REGISTER
    // ===========================
    public function proses_register()
    {
        $this->form_validation->set_rules('username', 'Username', 'required|min_length[3]|max_length[100]');
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email|is_unique[users.email]', [
            'is_unique' => 'Email sudah terdaftar!'
        ]);
        $this->form_validation->set_rules('password', 'Password', 'required|min_length[6]');
        $this->form_validation->set_rules('password_confirm', 'Konfirmasi Password', 'required|matches[password]', [
            'matches' => 'Konfirmasi password tidak sesuai!'
        ]);
        $this->form_validation->set_rules('noTelepon', 'No. Telepon', 'required|numeric|min_length[10]');

        if ($this->form_validation->run() === FALSE) {
            echo json_encode([
                'status'  => 'error',
                'message' => 'Validasi gagal',
                'errors'  => $this->form_validation->error_array()
            ]);
            return;
        }

        // Cek duplikat email
        $existing = $this->User_model->check_login($this->input->post('email'));
        if ($existing) {
            echo json_encode([
                'status'  => 'error',
                'message' => 'Email sudah terdaftar!'
            ]);
            return;
        }

        // Generate OTP
        $otp = rand(100000, 999999);

        // Data user baru
        $data = [
            'username'    => $this->input->post('username'),
            'email'       => $this->input->post('email'),
            'password'    => password_hash($this->input->post('password'), PASSWORD_BCRYPT),
            'noTelepon'   => $this->input->post('noTelepon'),
            'otp'         => $otp,
            'otp_expiry'  => date('Y-m-d H:i:s', strtotime('+5 minutes')),
            'active'      => 0,
            'role'        => 3, // Default user role
            'date_created' => date('Y-m-d H:i:s')
        ];

        // Insert user
        if ($this->User_model->register($data)) {
            // Kirim email dengan OTP
            $this->send_otp_email($this->input->post('email'), $otp);

            echo json_encode([
                'status'  => 'success',
                'message' => 'Registrasi berhasil! Silakan verifikasi OTP yang dikirim ke email Anda.',
                'email'   => $this->input->post('email')
            ]);
        } else {
            echo json_encode([
                'status'  => 'error',
                'message' => 'Registrasi gagal! Silakan coba lagi.'
            ]);
        }
    }

    // ===========================
    // FORGOT PASSWORD PAGE
    // ===========================
    public function forgot_password()
    {
        $this->load->view('auth/forgot-password');
    }

    // ===========================
    // PROSES FORGOT PASSWORD
    // ===========================
    public function proses_forgot_password()
    {
        $email = $this->input->post('email');

        // Cek email exist
        $user = $this->User_model->check_login($email);
        if (!$user) {
            echo json_encode([
                'status'  => 'error',
                'message' => 'Email tidak terdaftar!'
            ]);
            return;
        }

        // Generate OTP
        $otp = rand(100000, 999999);

        // Update OTP
        $update_data = [
            'otp'        => $otp,
            'otp_expiry' => date('Y-m-d H:i:s', strtotime('+10 minutes'))
        ];

        if ($this->User_model->update_otp($email, $update_data)) {
            // Kirim email OTP
            $this->send_otp_email($email, $otp);

            echo json_encode([
                'status'  => 'success',
                'message' => 'OTP berhasil dikirim ke email Anda!',
                'email'   => $email
            ]);
        } else {
            echo json_encode([
                'status'  => 'error',
                'message' => 'Gagal mengirim OTP! Silakan coba lagi.'
            ]);
        }
    }

    // ===========================
    // VERIFY OTP PAGE
    // ===========================
    public function verify_otp()
    {
        $email = $this->session->flashdata('email') ?? $this->input->get('email');

        $data = ['email' => $email];
        $this->load->view('auth/verify_otp', $data);
    }

    // ===========================
    // PROSES VERIFY OTP
    // ===========================
    public function proses_verify_otp()
    {
        $email = $this->input->post('email');
        $otp = $this->input->post('otp');

        // Cek user
        $user = $this->User_model->check_login($email);
        if (!$user) {
            echo json_encode([
                'status'  => 'error',
                'message' => 'Email tidak terdaftar!'
            ]);
            return;
        }

        // Cek OTP
        if ($user['otp'] != $otp) {
            echo json_encode([
                'status'  => 'error',
                'message' => 'OTP salah!'
            ]);
            return;
        }

        // Cek OTP expiry
        if (strtotime($user['otp_expiry']) < time()) {
            echo json_encode([
                'status'  => 'error',
                'message' => 'OTP sudah kadaluarsa! Silakan minta OTP baru.'
            ]);
            return;
        }

        // OTP valid, jika registrasi - set account active
        if ($user['active'] == 0) {
            $this->User_model->activate_account($email);

            echo json_encode([
                'status'  => 'success',
                'message' => 'Verifikasi berhasil! Silakan login.',
                'redirect' => site_url('auth')
            ]);
        } else {
            // Untuk forgot password, bisa reset password
            $this->session->set_tempdata('email_verified', $email, 600); // 10 menit

            echo json_encode([
                'status'  => 'success',
                'message' => 'Verifikasi berhasil! Silakan reset password Anda.',
                'redirect' => site_url('auth/reset_password?email=' . $email)
            ]);
        }
    }

    // ===========================
    // RESEND OTP
    // ===========================
    public function resend_otp()
    {
        $email = $this->input->post('email');

        $user = $this->User_model->check_login($email);
        if (!$user) {
            echo json_encode([
                'status'  => 'error',
                'message' => 'Email tidak terdaftar!'
            ]);
            return;
        }

        // Generate OTP baru
        $otp = rand(100000, 999999);
        $update_data = [
            'otp'        => $otp,
            'otp_expiry' => date('Y-m-d H:i:s', strtotime('+5 minutes'))
        ];

        if ($this->User_model->update_otp($email, $update_data)) {
            $this->send_otp_email($email, $otp);

            echo json_encode([
                'status'  => 'success',
                'message' => 'OTP baru telah dikirim ke email Anda!'
            ]);
        } else {
            echo json_encode([
                'status'  => 'error',
                'message' => 'Gagal mengirim OTP! Silakan coba lagi.'
            ]);
        }
    }

    // ===========================
    // RESET PASSWORD PAGE
    // ===========================
    public function reset_password()
    {
        $email = $this->input->get('email');

        $data = ['email' => $email];
        $this->load->view('auth/reset-password', $data);
    }

    // ===========================
    // PROSES RESET PASSWORD
    // ===========================
    public function proses_reset_password()
    {
        $email = $this->input->post('email');
        $password = $this->input->post('password');
        $password_confirm = $this->input->post('password_confirm');

        // Validasi
        if (strlen($password) < 6) {
            echo json_encode([
                'status'  => 'error',
                'message' => 'Password minimal 6 karakter!'
            ]);
            return;
        }

        if ($password !== $password_confirm) {
            echo json_encode([
                'status'  => 'error',
                'message' => 'Konfirmasi password tidak sesuai!'
            ]);
            return;
        }

        // Update password
        $update_data = [
            'password' => password_hash($password, PASSWORD_BCRYPT),
            'otp'      => NULL,
            'otp_expiry' => NULL
        ];

        if ($this->User_model->update_password($email, $update_data)) {
            echo json_encode([
                'status'   => 'success',
                'message'  => 'Password berhasil direset! Silakan login.',
                'redirect' => site_url('auth')
            ]);
        } else {
            echo json_encode([
                'status'  => 'error',
                'message' => 'Gagal reset password! Silakan coba lagi.'
            ]);
        }
    }

    // ===========================
    // LOGOUT
    // ===========================
    public function logout()
    {
        $this->session->sess_destroy();
        redirect('auth');
    }

    // ===========================
    // HELPER: SEND OTP EMAIL
    // ===========================
    private function send_otp_email($email, $otp)
    {
        $config = [
            'protocol' => 'smtp',
            'smtp_host' => 'smtp.gmail.com',
            'smtp_port' => 587,
            'smtp_user' => 'your_email@gmail.com', // Ganti dengan email Anda
            'smtp_pass' => 'your_password',         // Ganti dengan password Anda
            'mailtype'  => 'html',
            'charset'   => 'utf-8',
            'newline'   => "\r\n"
        ];

        $this->email->initialize($config);

        $subject = 'Kode OTP Verifikasi - Point of Sale System';
        $message = "
            <html>
                <body>
                    <h2>Verifikasi OTP</h2>
                    <p>Kode OTP Anda adalah:</p>
                    <h1 style='color: #007bff; font-weight: bold;'>$otp</h1>
                    <p>Kode ini berlaku selama 5-10 menit.</p>
                    <p>Jangan bagikan kode ini kepada siapapun.</p>
                    <hr>
                    <p>Jika Anda tidak melakukan permintaan ini, abaikan email ini.</p>
                </body>
            </html>
        ";

        $this->email->from('noreply@pointofsale.com', 'Point of Sale System');
        $this->email->to($email);
        $this->email->subject($subject);
        $this->email->message($message);

        return $this->email->send();
    }
}
