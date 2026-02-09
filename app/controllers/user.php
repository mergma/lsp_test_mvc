<?php
class User extends Controller
{
    private $userModel;

    public function __construct()
    {
        $this->requireAdmin(); // Only admin can access user management
        $this->userModel = $this->model('User_model');
    }

    public function index()
    {
        $data = [
            'title' => 'User Management',
            'users' => $this->userModel->getAllUsers(),
            'flash' => $this->getFlash()
        ];

        $this->view('user/index', $data);
    }

    public function add()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $userData = [
                'name' => $_POST['name'] ?? '',
                'email' => $_POST['email'] ?? '',
                'password' => $_POST['password'] ?? '',
                'role' => $_POST['role'] ?? 'petugas'
            ];

            if (!empty($userData['name']) && !empty($userData['email']) && !empty($userData['password'])) {
                if ($this->userModel->emailExists($userData['email'])) {
                    $this->setFlash('error', 'Email already exists');
                } elseif (strlen($userData['password']) < 6) {
                    $this->setFlash('error', 'Password must be at least 6 characters long');
                } else {
                    if ($this->userModel->createUser($userData)) {
                        $this->setFlash('success', 'User added successfully');
                    } else {
                        $this->setFlash('error', 'Failed to add user');
                    }
                }
            } else {
                $this->setFlash('error', 'All required fields must be filled');
            }
        }

        $this->redirect('user');
    }

    public function edit($id)
    {
        $data = [
            'title' => 'Edit User',
            'user' => $this->userModel->getUserById($id),
            'flash' => $this->getFlash()
        ];

        if (!$data['user']) {
            $this->setFlash('error', 'User not found');
            $this->redirect('user');
        }

        $this->view('user/edit', $data);
    }

    public function update()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id = $_POST['id'] ?? 0;
            $userData = [
                'name' => $_POST['name'] ?? '',
                'email' => $_POST['email'] ?? '',
                'password' => $_POST['password'] ?? '',
                'role' => $_POST['role'] ?? 'petugas'
            ];

            if ($id && !empty($userData['name']) && !empty($userData['email'])) {
                if ($this->userModel->emailExists($userData['email'], $id)) {
                    $this->setFlash('error', 'Email already exists');
                } elseif (!empty($userData['password']) && strlen($userData['password']) < 6) {
                    $this->setFlash('error', 'Password must be at least 6 characters long');
                } else {
                    if ($this->userModel->updateUser($id, $userData)) {
                        $this->setFlash('success', 'User updated successfully');
                    } else {
                        $this->setFlash('error', 'Failed to update user');
                    }
                }
            } else {
                $this->setFlash('error', 'Invalid data');
            }
        }

        $this->redirect('user');
    }

    public function delete()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id = $_POST['id'] ?? 0;
            
            // Prevent deleting yourself
            if ($id == $_SESSION['user_id']) {
                $this->setFlash('error', 'You cannot delete your own account');
            } elseif ($id) {
                if ($this->userModel->deleteUser($id)) {
                    $this->setFlash('success', 'User deleted successfully');
                } else {
                    $this->setFlash('error', 'Failed to delete user');
                }
            }
        }

        $this->redirect('user');
    }
}

