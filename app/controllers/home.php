<?php
class Home extends Controller
{
    private $assetModel;
    private $categoryModel;
    private $locationModel;
    private $mutationModel;

    public function __construct()
    {
        $this->assetModel = $this->model('Asset_model');
        $this->categoryModel = $this->model('Category_model');
        $this->locationModel = $this->model('Location_model');
        $this->mutationModel = $this->model('Mutation_model');
    }

    public function index()
    {
        $data = [
            'title' => 'Dashboard',
            'total_assets' => $this->assetModel->getTotalCount(),
            'total_categories' => $this->categoryModel->getTotalCount(),
            'total_locations' => $this->locationModel->getTotalCount(),
            'total_mutations' => $this->mutationModel->getTotalCount(),
            'recent_mutations' => $this->mutationModel->getRecentMutations(5),
            'flash' => $this->getFlash()
        ];

        $this->view('home/index', $data);
    }
}
