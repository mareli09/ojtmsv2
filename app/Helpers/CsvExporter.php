<?php

namespace App\Helpers;

use Illuminate\Support\Facades\DB;

class CsvExporter
{
    /**
     * Export a single table to CSV
     */
    public static function exportTable($table)
    {
        $data = DB::table($table)->get();
        
        if ($data->isEmpty()) {
            return null;
        }

        $headers = array_keys((array)$data[0]);
        $rows = $data->map(function($row) {
            return (array)$row;
        })->toArray();

        return [
            'headers' => $headers,
            'rows' => $rows,
            'filename' => $table . '_' . now()->format('Y-m-d_H-i-s') . '.csv'
        ];
    }

    /**
     * Generate CSV content
     */
    public static function generateCsv($headers, $rows)
    {
        $output = fopen('php://output', 'w');
        
        // Write BOM for UTF-8
        fwrite($output, "\xEF\xBB\xBF");
        
        // Write headers
        fputcsv($output, $headers);
        
        // Write rows
        foreach ($rows as $row) {
            fputcsv($output, $row);
        }
        
        fclose($output);
    }

    /**
     * Generate preview HTML table
     */
    public static function generatePreviewHtml($headers, $rows, $limit = 5)
    {
        $html = '<table class="table preview-table">';
        $html .= '<thead><tr>';
        
        foreach ($headers as $header) {
            $html .= '<th>' . htmlspecialchars($header) . '</th>';
        }
        
        $html .= '</tr></thead><tbody>';
        
        $count = 0;
        foreach ($rows as $row) {
            if ($count >= $limit) break;
            
            $html .= '<tr>';
            foreach ($headers as $header) {
                $value = isset($row[$header]) ? $row[$header] : '';
                // Truncate long values
                $value = strlen($value) > 50 ? substr($value, 0, 50) . '...' : $value;
                $html .= '<td>' . htmlspecialchars($value) . '</td>';
            }
            $html .= '</tr>';
            $count++;
        }
        
        $html .= '</tbody></table>';
        
        if (count($rows) > $limit) {
            $html .= '<p class="text-muted text-center mt-3"><small>Showing ' . $limit . ' of ' . count($rows) . ' records</small></p>';
        }
        
        return $html;
    }
}
