<?php
class Category extends Controller
{
    private $categoryModel;

    public function __construct()
    {
        $this->categoryModel = $this->model('Category_model');
    }

    public function index()
    {
        $data = [
            'title' => 'Asset Categories',
            'categories' => $this->categoryModel->getAllCategories(),
            'flash' => $this->getFlash()
        ];

        $this->view('category/index', $data);
    }

    public function add()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $nama_kategori = $_POST['nama_kategori'] ?? '';

            if (!empty($nama_kategori)) {
                if ($this->categoryModel->createCategory($nama_kategori)) {
                    $this->setFlash('success', 'Category added successfully');
                } else {
                    $this->setFlash('error', 'Failed to add category');
                }
            } else {
                $this->setFlash('error', 'Category name is required');
            }
        }

        $this->redirect('category');
    }

    public function edit($id)
    {
        $data = [
            'title' => 'Edit Category',
            'category' => $this->categoryModel->getCategoryById($id),
            'flash' => $this->getFlash()
        ];

        if (!$data['category']) {
            $this->setFlash('error', 'Category not found');
            $this->redirect('category');
        }

        $this->view('category/edit', $data);
    }

    public function update()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id = $_POST['id'] ?? 0;
            $nama_kategori = $_POST['nama_kategori'] ?? '';

            if ($id && !empty($nama_kategori)) {
                if ($this->categoryModel->updateCategory($id, $nama_kategori)) {
                    $this->setFlash('success', 'Category updated successfully');
                } else {
                    $this->setFlash('error', 'Failed to update category');
                }
            } else {
                $this->setFlash('error', 'Invalid data');
            }
        }

        $this->redirect('category');
    }

    public function delete()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id = $_POST['id'] ?? 0;
            if ($id) {
                if ($this->categoryModel->deleteCategory($id)) {
                    $this->setFlash('success', 'Category deleted successfully');
                } else {
                    $this->setFlash('error', 'Failed to delete category');
                }
            }
        }

        $this->redirect('category');
    }
}

