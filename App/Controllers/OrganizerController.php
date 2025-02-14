<?php
namespace App\Controllers;
use App\core\views;
use App\Models\Organizer;
use App\Core\Request;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;


class OrganizerController
{
    private Organizer $model;

    public function __construct()
    {
        $this->model = new Organizer();
    }
    public function index()
    {
        $data = 3;
        views::render('organizer/organizerdash', ["event_stats" => $data]);
    }
    public function eventstats(Request $request)
    {
        $data = $this->model->eventStats($request->get('id'));
        views::render('organizer/organizerdash', ["eventstats" => $data]);
    }
    public function MyEvents()
    {
        $data = $this->model->eventList();
        views::render('organizer/organizerdash', ["events" => $data]);
    }
    public function participant(Request $request)
    {
        $data = $this->model->participantList($request->get('id'));
        views::render('organizer/organizerdash', ["participants" => $data]);
    }
    public function promo()
    {
        
    }
    public function announcements()
    {
        views::render('home');
    }
    public function csv(Request $request)
    {
        $rows = $this->model->participantList($request->get('id'));
        foreach ($rows as $key => $r) {
            unset($rows[$key]["event_id"], $rows[$key]["participant_id"], $rows[$key]["qr_code"], $rows[$key]["price"], $rows[$key]["reservation_date"]);
        }
        
        // Set headers for CSV download
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=participants_list.csv');

        // Open output stream
        $output = fopen('php://output', 'w');

        // Write column headers
        if (!empty($rows)) {
            fputcsv($output, array_keys($rows[0])); 
        }

        // Write data rows
        foreach ($rows as $row) {
            fputcsv($output, $row);
        }

        fclose($output);
    }
    public function excel(Request $request)
    {
        $rows = $this->model->participantList($request->get('id'));
        foreach ($rows as $key => $r) {
            unset($rows[$key]["event_id"], $rows[$key]["participant_id"], $rows[$key]["qr_code"], $rows[$key]["price"], $rows[$key]["reservation_date"]);
        }
        
        // Create new Spreadsheet object
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Add headers
        $columns = array_keys($rows[0]);
        foreach ($columns as $index => $column) {
            $cellPosition = chr(65 + $index) . '1'; // Convert number to letter (A1, B1, etc.)
            $sheet->setCellValue($cellPosition, ucfirst($column));
            
            // Optional: Style the header
            $sheet->getStyle($cellPosition)->getFont()->setBold(true);
        }

        // Add data
        foreach ($rows as $rowIndex => $row) {
            $rowNumber = $rowIndex + 2; // Start from row 2 (after headers)
            foreach ($columns as $columnIndex => $column) {
                $cellPosition = chr(65 + $columnIndex) . $rowNumber;
                $sheet->setCellValue($cellPosition, $row[$column]);
            }
        }

        // Auto-size columns
        foreach (range('A', chr(65 + count($columns) - 1)) as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }

        // Set headers for download
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="participants_list.xlsx"');
        header('Cache-Control: max-age=0');

        // Create Excel file
        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
    }
}