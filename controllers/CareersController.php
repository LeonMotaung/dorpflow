<?php
/**
 * DorpFlow ERP - Careers & Skills Database Controller for Residents
 */

require_once ROOT_PATH . '/core/Controller.php';
require_once ROOT_PATH . '/core/Auth.php';
require_once ROOT_PATH . '/models/AuditLog.php';

class CareersController extends Controller {

    public function __construct() {
        Auth::requireRole(['Resident']);
    }

    /**
     * Show Careers Form
     */
    public function index() {
        $db = Database::getConnection();

        // Self-repair: Create job_applications table if not exists
        $db->exec("
            CREATE TABLE IF NOT EXISTS job_applications (
                id INT AUTO_INCREMENT PRIMARY KEY,
                full_name VARCHAR(100) NOT NULL,
                email VARCHAR(100) NOT NULL,
                phone VARCHAR(20) NOT NULL,
                id_number VARCHAR(20) NOT NULL,
                cv_path VARCHAR(255) DEFAULT NULL,
                qualifications_path VARCHAR(255) DEFAULT NULL,
                status VARCHAR(20) DEFAULT 'Pending',
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            ) ENGINE=InnoDB;
        ");

        // Fetch user information to pre-fill
        $stmt = $db->prepare("SELECT * FROM users WHERE id = ? LIMIT 1");
        $stmt->execute([$_SESSION['user_id']]);
        $user = $stmt->fetch();

        $this->render('resident/careers', [
            'title' => 'Careers & Skills DB | DorpFlow',
            'user' => $user,
            'csrf_token' => $this->getCsrfToken()
        ]);
    }

    /**
     * Process Application Submission
     */
    public function apply() {
        $this->validateCsrf();
        $db = Database::getConnection();

        $fullName = $_POST['full_name'] ?? '';
        $email = $_POST['email'] ?? '';
        $phone = $_POST['phone'] ?? '';
        $idNumber = $_POST['id_number'] ?? '';

        if (empty($fullName) || empty($email) || empty($phone) || empty($idNumber)) {
            $_SESSION['error_message'] = "All details are required.";
            $this->redirect('/resident/careers');
            return;
        }

        // Handle CV upload
        $cvPath = null;
        if (isset($_FILES['cv']) && $_FILES['cv']['error'] === UPLOAD_ERR_OK) {
            $cvTmp = $_FILES['cv']['tmp_name'];
            $cvName = $_FILES['cv']['name'];
            $cvExt = strtolower(pathinfo($cvName, PATHINFO_EXTENSION));

            if (in_array($cvExt, ['pdf', 'doc', 'docx'])) {
                $dir = ROOT_PATH . '/public/uploads/applications/cvs/';
                if (!is_dir($dir)) {
                    mkdir($dir, 0777, true);
                }
                $newCvName = 'cv_' . preg_replace('/[^a-zA-Z0-9]/', '', $fullName) . '_' . time() . '.' . $cvExt;
                if (move_uploaded_file($cvTmp, $dir . $newCvName)) {
                    $cvPath = '/public/uploads/applications/cvs/' . $newCvName;
                }
            }
        }

        // Handle Qualifications upload
        $qualificationsPath = null;
        if (isset($_FILES['qualifications']) && $_FILES['qualifications']['error'] === UPLOAD_ERR_OK) {
            $qualTmp = $_FILES['qualifications']['tmp_name'];
            $qualName = $_FILES['qualifications']['name'];
            $qualExt = strtolower(pathinfo($qualName, PATHINFO_EXTENSION));

            if (in_array($qualExt, ['pdf', 'doc', 'docx'])) {
                $dir = ROOT_PATH . '/public/uploads/applications/qualifications/';
                if (!is_dir($dir)) {
                    mkdir($dir, 0777, true);
                }
                $newQualName = 'qual_' . preg_replace('/[^a-zA-Z0-9]/', '', $fullName) . '_' . time() . '.' . $qualExt;
                if (move_uploaded_file($qualTmp, $dir . $newQualName)) {
                    $qualificationsPath = '/public/uploads/applications/qualifications/' . $newQualName;
                }
            }
        }

        if (!$cvPath || !$qualificationsPath) {
            $_SESSION['error_message'] = "Both CV and Qualification documents are required in PDF or Word format.";
            $this->redirect('/resident/careers');
            return;
        }

        $stmt = $db->prepare("
            INSERT INTO job_applications (full_name, email, phone, id_number, cv_path, qualifications_path)
            VALUES (?, ?, ?, ?, ?, ?)
        ");
        $stmt->execute([$fullName, $email, $phone, $idNumber, $cvPath, $qualificationsPath]);

        // Audit Log
        $audit = new AuditLog();
        $audit->log($_SESSION['user_id'], "Submitted CV and qualifications to the municipal skills database.");

        $_SESSION['success_message'] = "Thank you! Your profile, CV, and qualifications have been successfully uploaded to the municipality database. HR will contact you if suitable positions match your profile.";
        $this->redirect('/resident/careers');
    }
}
