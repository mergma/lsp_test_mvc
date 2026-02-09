<?php
class Auth extends Controller
{
    private $userModel;

    public function __construct()
    {
        $this->userModel = $this->model('User_model');
    }

    public function login()
    {
        // If already logged in, redirect to home
        if ($this->isLoggedIn()) {
            $this->redirect('');
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';

            if (!empty($email) && !empty($password)) {
                $user = $this->userModel->verifyPassword($email, $password);

                if ($user) {
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['email'] = $user['email'];
                    $_SESSION['name'] = $user['name'];
                    $_SESSION['role'] = $user['role'];
                    $this->redirect('');
                } else {
                    $data['error'] = 'Invalid email or password';
                }
            } else {
                $data['error'] = 'Please fill in all fields';
            }
        }

        $this->view('auth/login', $data ?? []);
    }

    public function register()
    {
        // If already logged in, redirect to home
        if ($this->isLoggedIn()) {
            $this->redirect('');
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $name = $_POST['name'] ?? '';
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';
            $confirm_password = $_POST['confirm_password'] ?? '';

            if (!empty($name) && !empty($email) && !empty($password) && !empty($confirm_password)) {
                if ($password !== $confirm_password) {
                    $data['error'] = 'Passwords do not match';
                } elseif (strlen($password) < 6) {
                    $data['error'] = 'Password must be at least 6 characters long';
                } elseif ($this->userModel->emailExists($email)) {
                    $data['error'] = 'Email already exists';
                } else {
                    $userData = [
                        'name' => $name,
                        'email' => $email,
                        'password' => $password,
                        'role' => 'petugas'
                    ];

                    if ($this->userModel->createUser($userData)) {
                        $data['success'] = 'Registration successful! You can now login.';
                        $data['name'] = '';
                        $data['email'] = '';
                    } else {
                        $data['error'] = 'Registration failed. Please try again.';
                    }
                }
            } else {
                $data['error'] = 'Please fill in all fields';
            }

            if (!isset($data['success'])) {
                $data['name'] = $name;
                $data['email'] = $email;
            }
        }

        $this->view('auth/register', $data ?? []);
    }

    public function logout()
    {
        session_destroy();
        $this->redirect('auth/login');
    }
}

