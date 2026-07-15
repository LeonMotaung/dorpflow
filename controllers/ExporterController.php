<?php
/**
 * DorpFlow ERP - ExporterController
 * Handles Global AG Audit Exports (CSV + PDF) across all municipal tenant data.
 */

require_once ROOT_PATH . '/core/Controller.php';
require_once ROOT_PATH . '/core/Auth.php';
require_once ROOT_PATH . '/core/Database.php';
require_once ROOT_PATH . '/core/fpdf.php';

class ExporterController extends Controller {

    public function showAuditConsole() {
        Auth::requireRole(['Super Admin']);

        $core = Database::getCoreConnection();
        $muniFilter = $_GET['municipality_id'] ?? '';

        $query = "
            SELECT id, user_type, action, details, ip_address, created_at
            FROM global_audit_logs
        ";

        if (!empty($muniFilter)) {
            $stmtMuni = $core->prepare("SELECT name FROM municipalities WHERE id = ? LIMIT 1");
            $stmtMuni->execute([$muniFilter]);
            $muniName = $stmtMuni->fetchColumn() ?: '';

            $stmt = $core->prepare($query . " WHERE details LIKE ? ORDER BY created_at DESC LIMIT 500");
            $stmt->execute(['%' . $muniName . '%']);
            $logs = $stmt->fetchAll();
        } else {
            $logs = $core->query($query . " ORDER BY created_at DESC LIMIT 500")->fetchAll();
        }

        $municipalities = $core->query("SELECT id, name FROM municipalities ORDER BY name")->fetchAll();

        $this->render('superadmin/ag_audit_console', [
            'title' => 'AG Audit Export Console | DorpFlow',
            'logs' => $logs,
            'municipalities' => $municipalities,
            'csrf_token' => $this->getCsrfToken()
        ]);
    }

    /**
     * Export audit logs as CSV download (Auditor-General format)
     */
    public function exportCsv() {
        Auth::requireRole(['Super Admin']);

        $core = Database::getCoreConnection();
        $muniFilter = $_GET['municipality_id'] ?? '';

        $query = "
            SELECT id, user_type, action, details, ip_address, created_at
            FROM global_audit_logs
        ";

        if (!empty($muniFilter)) {
            $stmtMuni = $core->prepare("SELECT name FROM municipalities WHERE id = ? LIMIT 1");
            $stmtMuni->execute([$muniFilter]);
            $muniName = $stmtMuni->fetchColumn() ?: '';

            $stmt = $core->prepare($query . " WHERE details LIKE ? ORDER BY created_at DESC");
            $stmt->execute(['%' . $muniName . '%']);
        } else {
            $stmt = $core->query($query . " ORDER BY created_at DESC");
        }

        $logs = $stmt->fetchAll();

        $filename = 'DorpFlow_AG_Audit_Export_' . date('Y-m-d_His') . '.csv';
        header('Content-Type: text/csv; charset=UTF-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Pragma: no-cache');

        $out = fopen('php://output', 'w');
        fputs($out, "\xEF\xBB\xBF"); // BOM for Excel UTF-8

        fputcsv($out, ['Log ID', 'User Type', 'Action Description', 'Details / Context', 'IP Address', 'Timestamp']);

        foreach ($logs as $row) {
            fputcsv($out, [
                $row['id'],
                $row['user_type'] ?? 'System',
                $row['action'],
                $row['details'] ?? '-',
                $row['ip_address'] ?? '-',
                $row['created_at']
            ]);
        }

        fclose($out);
        exit;
    }

    /**
     * Export audit logs as PDF (AG formatted report)
     */
    public function exportPdf() {
        Auth::requireRole(['Super Admin']);

        $core = Database::getCoreConnection();
        $muniFilter = $_GET['municipality_id'] ?? '';

        $query = "
            SELECT id, user_type, action, details, ip_address, created_at
            FROM global_audit_logs
        ";

        if (!empty($muniFilter)) {
            $stmtMuni = $core->prepare("SELECT name FROM municipalities WHERE id = ? LIMIT 1");
            $stmtMuni->execute([$muniFilter]);
            $muniName = $stmtMuni->fetchColumn() ?: '';

            $stmt = $core->prepare($query . " WHERE details LIKE ? ORDER BY created_at DESC LIMIT 100");
            $stmt->execute(['%' . $muniName . '%']);
        } else {
            $stmt = $core->query($query . " ORDER BY created_at DESC LIMIT 100");
        }

        $logs = $stmt->fetchAll();

        $pdf = new FPDF('L', 'mm', 'A4'); // Landscape
        $pdf->SetAutoPageBreak(true, 15);
        $pdf->AddPage();

        // Header
        $logoPath = ROOT_PATH . '/dorpflow.png';
        $logoWidth = 35;
        $logoHeight = 12;
        if (file_exists($logoPath)) {
            list($wPx, $hPx) = getimagesize($logoPath);
            if ($wPx > 0) $logoHeight = ($hPx / $wPx) * $logoWidth;
            $pdf->Image($logoPath, 10, 8, $logoWidth);
        }

        $pdf->SetFont('Helvetica', 'B', 14);
        $pdf->SetTextColor(10, 43, 76);
        $pdf->Cell(277, 6, 'DorpFlow ERP - Auditor-General System Audit Export', 0, 1, 'R');

        $pdf->SetFont('Helvetica', '', 8);
        $pdf->SetTextColor(100, 110, 120);
        $pdf->Cell(277, 4, 'Generated: ' . date('d F Y H:i:s') . ' | CONFIDENTIAL - Not for public distribution', 0, 1, 'R');

        $lineY = 8 + $logoHeight + 4;
        $pdf->SetDrawColor(226, 232, 240);
        $pdf->Line(10, $lineY, 287, $lineY);
        $pdf->SetY($lineY + 4);

        // Table Header
        $pdf->SetFont('Helvetica', 'B', 8);
        $pdf->SetFillColor(10, 43, 76);
        $pdf->SetTextColor(255, 255, 255);
        $pdf->Cell(12, 7, 'ID', 1, 0, 'C', true);
        $pdf->Cell(35, 7, 'User Type', 1, 0, 'C', true);
        $pdf->Cell(120, 7, 'Action Description', 1, 0, 'L', true);
        $pdf->Cell(60, 7, 'Details / Context', 1, 0, 'L', true);
        $pdf->Cell(25, 7, 'IP Address', 1, 0, 'C', true);
        $pdf->Cell(25, 7, 'Timestamp', 1, 1, 'C', true);

        // Table Rows
        $pdf->SetFont('Helvetica', '', 7.5);
        $pdf->SetTextColor(50, 60, 80);
        $rowAlt = false;

        foreach ($logs as $row) {
            $pdf->SetFillColor($rowAlt ? 248 : 255, $rowAlt ? 250 : 255, $rowAlt ? 255 : 255);
            $rowAlt = !$rowAlt;

            $pdf->Cell(12, 6, $row['id'], 1, 0, 'C', true);
            $pdf->Cell(35, 6, $row['user_type'] ?? 'System', 1, 0, 'C', true);
            $pdf->Cell(120, 6, mb_substr($row['action'], 0, 70), 1, 0, 'L', true);
            $pdf->Cell(60, 6, mb_substr($row['details'] ?? '-', 0, 35), 1, 0, 'L', true);
            $pdf->Cell(25, 6, $row['ip_address'] ?? '-', 1, 0, 'C', true);
            $pdf->Cell(25, 6, date('d/m/Y H:i', strtotime($row['created_at'])), 1, 1, 'C', true);
        }

        // Footer info
        $pdf->Ln(5);
        $pdf->SetFont('Helvetica', 'I', 7);
        $pdf->SetTextColor(150, 160, 170);
        $pdf->Cell(0, 5, 'DorpFlow ERP Platform  |  Prepared by Super Admin Console  |  ' . date('Y') . '  |  This report is intended exclusively for the Auditor-General of South Africa.', 0, 0, 'C');

        $filename = 'DorpFlow_AG_Audit_' . date('Y-m-d') . '.pdf';
        $pdf->Output('D', $filename);
        exit;
    }
}
