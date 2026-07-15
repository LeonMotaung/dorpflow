<?php
/**
 * DorpFlow ERP - Infrastructure Asset Management Controller
 */

require_once ROOT_PATH . '/core/Controller.php';
require_once ROOT_PATH . '/core/Auth.php';
require_once ROOT_PATH . '/models/Asset.php';

class AssetController extends Controller {

    public function index() {
        Auth::requireRole(['Municipality Administrator', 'Department Manager', 'Supervisor']);
        
        $assetModel = new Asset();
        $assets = $assetModel->getAssets();

        $this->render('assets/list', [
            'title' => 'Infrastructure Assets | DorpFlow',
            'assets' => $assets
        ]);
    }
}
