<?php
/**
 * DorpFlow ERP - Mafube Local Municipality Bootstrap Entry Point
 */

// Override tenant context for local folder routing
$_GET['tenant'] = 'mafube';

// Load primary index dispatcher
require_once dirname(__DIR__) . '/public/index.php';
