<?php
/**
 * DorpFlow ERP - Fleet and Dispatch Management Controller
 */

require_once ROOT_PATH . '/core/Controller.php';
require_once ROOT_PATH . '/core/Auth.php';
require_once ROOT_PATH . '/models/Fleet.php';

class FleetController extends Controller {

    public function index() {
        Auth::requireRole(['Municipality Administrator', 'Department Manager', 'Supervisor']);
        
        $fleetModel = new Fleet();
        $vehicles = $fleetModel->getFleet();

        $this->render('fleet/list', [
            'title' => 'Municipal Fleet Management | DorpFlow',
            'vehicles' => $vehicles
        ]);
    }
}
