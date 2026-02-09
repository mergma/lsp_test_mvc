<?php
class Mutation extends Controller
{
    private $mutationModel;
    private $assetModel;
    private $locationModel;

    public function __construct()
    {
        $this->mutationModel = $this->model('Mutation_model');
        $this->assetModel = $this->model('Asset_model');
        $this->locationModel = $this->model('Location_model');
    }

    public function index()
    {
        $data = [
            'title' => 'Asset Mutations',
            'mutations' => $this->mutationModel->getAllMutations(),
            'assets' => $this->assetModel->getAllAssets(),
            'locations' => $this->locationModel->getAllLocations(),
            'flash' => $this->getFlash()
        ];

        $this->view('mutation/index', $data);
    }

    public function add()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $mutationData = [
                'asset_id' => $_POST['asset_id'] ?? 0,
                'lokasi_asal_id' => $_POST['lokasi_asal_id'] ?? 0,
                'lokasi_tujuan_id' => $_POST['lokasi_tujuan_id'] ?? 0,
                'tanggal_mutasi' => $_POST['tanggal_mutasi'] ?? date('Y-m-d'),
                'keterangan' => $_POST['keterangan'] ?? '',
                'user_id' => $_SESSION['user_id']
            ];

            if ($mutationData['asset_id'] && $mutationData['lokasi_asal_id'] && $mutationData['lokasi_tujuan_id']) {
                // Add mutation record
                if ($this->mutationModel->createMutation($mutationData)) {
                    // Update asset's current location
                    $this->assetModel->updateAssetLocation($mutationData['asset_id'], $mutationData['lokasi_tujuan_id']);
                    $this->setFlash('success', 'Asset mutation recorded successfully');
                } else {
                    $this->setFlash('error', 'Failed to record mutation');
                }
            } else {
                $this->setFlash('error', 'All required fields must be filled');
            }
        }

        $this->redirect('mutation');
    }

    public function delete()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id = $_POST['id'] ?? 0;
            if ($id) {
                if ($this->mutationModel->deleteMutation($id)) {
                    $this->setFlash('success', 'Mutation record deleted successfully');
                } else {
                    $this->setFlash('error', 'Failed to delete mutation record');
                }
            }
        }

        $this->redirect('mutation');
    }
}

