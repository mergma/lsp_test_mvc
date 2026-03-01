<?php

class Controller
{
    public function __construct() {}

    public function view($view, $data = [])
    {
        if (!empty($data)) {
            extract($data);
        }

        if (file_exists('../app/views/' . $view . '.php')) {
            require_once '../app/views/' . $view . '.php';
        } else {
            die('View not found');
        }
    }

    public function model($model)
    {
        require_once '../app/models/' . $model . '.php';
        return new $model();
    }

    /**
     * Set a flash message in the session
     * @param string $type Type of message (success, error, warning, info)
     * @param string $message The message content
     */
    public function setFlash($type, $message)
    {
        $_SESSION['flash'] = [
            'type' => $type,
            'message' => $message
        ];
    }

    /**
     * Get and clear flash message from session
     * @return array|null Flash message array or null if not set
     */
    public function getFlash()
    {
        if (isset($_SESSION['flash'])) {
            $flash = $_SESSION['flash'];
            unset($_SESSION['flash']);
            return $flash;
        }
        return null;
    }

    /**
     * Redirect to a URL
     * @param string $url The URL to redirect to (relative to BASEURL)
     */
    public function redirect($url)
    {
        header('Location: ' . BASEURL . $url);
        exit;
    }

    /**
     * Check if user is logged in
     * @return bool True if user is logged in, false otherwise
     */
    public function isLoggedIn()
    {
        return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
    }

    /**
     * Require admin role, redirect if not admin
     */
    public function requireAdmin()
    {
        if (!$this->isLoggedIn()) {
            $this->redirect('auth/login');
        }
        if ($_SESSION['role'] !== 'admin') {
            $this->setFlash('error', 'You do not have permission to access this page');
            $this->redirect('');
        }
    }
}
