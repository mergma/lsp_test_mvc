<?php
class Location extends Controller
{
    private $locationModel;

    public function __construct()
    {
        $this->locationModel = $this->model('Location_model');
    }

    public function index()
    {
        $data = [
            'title' => 'Locations',
            'locations' => $this->locationModel->getAllLocations(),
            'flash' => $this->getFlash()
        ];

        $this->view('location/index', $data);
    }

    public function add()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $locationData = [
                'nama_lokasi' => $_POST['nama_lokasi'] ?? '',
                'keterangan' => $_POST['keterangan'] ?? ''
            ];

            if (!empty($locationData['nama_lokasi'])) {
                if ($this->locationModel->createLocation($locationData)) {
                    $this->setFlash('success', 'Location added successfully');
                } else {
                    $this->setFlash('error', 'Failed to add location');
                }
            } else {
                $this->setFlash('error', 'Location name is required');
            }
        }

        $this->redirect('location');
    }

    public function edit($id)
    {
        $data = [
            'title' => 'Edit Location',
            'location' => $this->locationModel->getLocationById($id),
            'flash' => $this->getFlash()
        ];

        if (!$data['location']) {
            $this->setFlash('error', 'Location not found');
            $this->redirect('location');
        }

        $this->view('location/edit', $data);
    }

    public function update()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id = $_POST['id'] ?? 0;
            $locationData = [
                'nama_lokasi' => $_POST['nama_lokasi'] ?? '',
                'keterangan' => $_POST['keterangan'] ?? ''
            ];

            if ($id && !empty($locationData['nama_lokasi'])) {
                if ($this->locationModel->updateLocation($id, $locationData)) {
                    $this->setFlash('success', 'Location updated successfully');
                } else {
                    $this->setFlash('error', 'Failed to update location');
                }
            } else {
                $this->setFlash('error', 'Invalid data');
            }
        }

        $this->redirect('location');
    }

    public function delete()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id = $_POST['id'] ?? 0;
            if ($id) {
                if ($this->locationModel->deleteLocation($id)) {
                    $this->setFlash('success', 'Location deleted successfully');
                } else {
                    $this->setFlash('error', 'Failed to delete location');
                }
            }
        }

        $this->redirect('location');
    }
}

