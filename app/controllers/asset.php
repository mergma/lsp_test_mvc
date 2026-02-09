<?php
class Asset extends Controller
{
    private $assetModel;
    private $categoryModel;
    private $locationModel;

    public function __construct()
    {
        $this->assetModel = $this->model('Asset_model');
        $this->categoryModel = $this->model('Category_model');
        $this->locationModel = $this->model('Location_model');
    }

    public function index()
    {
        $data = [
            'title' => 'Assets',
            'assets' => $this->assetModel->getAllAssets(),
            'categories' => $this->categoryModel->getAllCategories(),
            'locations' => $this->locationModel->getAllLocations(),
        ];

        $this->view('asset/index', $data);
    }

    public function add()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $assetData = [
                'nama_aset' => $_POST['nama_aset'] ?? '',
                'kategori_id' => $_POST['kategori_id'] ?? 0,
                'lokasi_id' => $_POST['lokasi_id'] ?? 0,
                'kondisi' => $_POST['kondisi'] ?? 'baik',
                'jumlah' => $_POST['jumlah'] ?? 1
            ];

            if (!empty($assetData['nama_aset']) && $assetData['kategori_id'] && $assetData['lokasi_id']) {
                if ($this->assetModel->createAsset($assetData)) {
                    $this->setFlash('success', 'Asset added successfully');
                    $this->redirect('asset');
                } else {
                    $this->setFlash('error', 'Failed to add asset');
                }
            } else {
                $this->setFlash('error', 'All required fields must be filled');
            }
        }

        $this->redirect('asset');
    }

    public function edit($id)
    {
        $data = [
            'title' => 'Edit Asset',
            'asset' => $this->assetModel->getAssetById($id),
            'categories' => $this->categoryModel->getAllCategories(),
            'locations' => $this->locationModel->getAllLocations(),
            'flash' => $this->getFlash()
        ];

        if (!$data['asset']) {
            $this->setFlash('error', 'Asset not found');
            $this->redirect('asset');
        }

        $this->view('asset/edit', $data);
    }

    public function update()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id = $_POST['id'] ?? 0;
            $assetData = [
                'nama_aset' => $_POST['nama_aset'] ?? '',
                'kategori_id' => $_POST['kategori_id'] ?? 0,
                'lokasi_id' => $_POST['lokasi_id'] ?? 0,
                'kondisi' => $_POST['kondisi'] ?? 'baik',
                'jumlah' => $_POST['jumlah'] ?? 1
            ];

            if ($id && !empty($assetData['nama_aset']) && $assetData['kategori_id'] && $assetData['lokasi_id']) {
                if ($this->assetModel->updateAsset($id, $assetData)) {
                    $this->setFlash('success', 'Asset updated successfully');
                } else {
                    $this->setFlash('error', 'Failed to update asset');
                }
            } else {
                $this->setFlash('error', 'Invalid data');
            }
        }

        $this->redirect('asset');
    }

    public function delete()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id = $_POST['id'] ?? 0;
            if ($id) {
                if ($this->assetModel->deleteAsset($id)) {
                    $this->setFlash('success', 'Asset deleted successfully');
                } else {
                    $this->setFlash('error', 'Failed to delete asset');
                }
            }
        }

        $this->redirect('asset');
    }
}

