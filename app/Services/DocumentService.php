<?php

namespace App\Services;

use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class DocumentService
{
    /**
     * Replace placeholders in the HTML template with actual data.
     *
     * @param string $html
     * @param array $data Associative array of placeholder => value
     * @return string
     */
    public function processTemplate(string $html, array $data): string
    {
        // Standardize keys to be wrapped in [[...]] if not already
        $replacements = [];
        foreach ($data as $key => $value) {
            // Handle both [[Key]] and Key formats
            $cleanKey = trim($key, '[]');
            $replacements['[[' . $cleanKey . ']]'] = $value;
        }

        return strtr($html, $replacements);
    }

    /**
     * Generate a PDF from the processed HTML.
     *
     * @param string $html
     * @param string $filename
     * @return \Illuminate\Http\Response
     */
    /**
     * Generate a PDF from the processed HTML with headers, footers, and tracking.
     *
     * @param string $html
     * @param \App\Models\Tenant $tenant
     * @param string $filename
     * @param string|null $trackingNumber
     * @return \Illuminate\Http\Response
     */
    public function generatePdf(string $html, \App\Models\Tenant $tenant, string $filename = 'document.pdf', ?string $trackingNumber = null)
    {
        $trackingNumber = $trackingNumber ?? \Illuminate\Support\Str::upper(\Illuminate\Support\Str::random(12));
        $date = Carbon::now()->format('F j, Y');
        
        // Get branding
        $settings = $tenant->customization;
        $primaryColor = $settings?->getPrimaryColor() ?? '#3b82f6';
        $logoPath = null;

        if ($settings && $settings->logo_path) {
            $fullPath = storage_path('app/public/' . $settings->logo_path);
            if (file_exists($fullPath)) {
                // Convert to base64 for reliable DomPDF rendering
                $type = pathinfo($fullPath, PATHINFO_EXTENSION);
                $data = file_get_contents($fullPath);
                $logoPath = 'data:image/' . $type . ';base64,' . base64_encode($data);
            }
        }
        
        // Header HTML
        $headerHtml = '<div class="header">';
        if ($logoPath) {
            $headerHtml .= '<img src="' . $logoPath . '" class="logo">';
        }
        $headerHtml .= '<div class="clinic-info">';
        $headerHtml .= '<h2>' . ($settings?->custom_brand_name ?? $tenant->name) . '</h2>';
        $headerHtml .= '<p>' . $tenant->address . '</p>';
        $headerHtml .= '<p>' . $tenant->city . ', ' . $tenant->state . ' ' . $tenant->zip_code . '</p>';
        $headerHtml .= '</div></div>';

        // Footer HTML with Tracking Number and Digital Signature placeholder
        $footerHtml = '<div class="footer">';
        $footerHtml .= '<div style="border-top: 1px solid #ddd; padding-top: 5px; margin-top: 10px;">';
        $footerHtml .= '<table width="100%"><tr>';
        $footerHtml .= '<td align="left" style="font-size: 8pt; color: #666;">Generated on ' . $date . '</td>';
        $footerHtml .= '<td align="center" style="font-size: 8pt; color: #666;">Page <span class="page-number"></span></td>';
        $footerHtml .= '<td align="right" style="font-size: 8pt; color: #666;">Tracking #: <strong>' . $trackingNumber . '</strong></td>';
        $footerHtml .= '</tr></table>';
        $footerHtml .= '</div>';
        $footerHtml .= '</div>';

        // Add CSS for PDF formatting with @page support
        $styles = '
            <style>
                @page { margin: 100px 50px 80px 50px; }
                body { font-family: DejaVu Sans, sans-serif; font-size: 11pt; line-height: 1.5; color: #333; }
                .header { position: fixed; top: -80px; left: 0px; right: 0px; height: 80px; border-bottom: 2px solid ' . $primaryColor . '; padding-bottom: 10px; }
                .footer { position: fixed; bottom: -60px; left: 0px; right: 0px; height: 50px; }
                .logo { float: left; width: 60px; height: auto; margin-right: 15px; }
                .clinic-info { float: left; }
                .clinic-info h2 { margin: 0; font-size: 16pt; color: ' . $primaryColor . '; }
                .clinic-info p { margin: 0; font-size: 9pt; color: #555; }
                .page-number:after { content: counter(page); }
                .content { margin-top: 20px; }
                h1 { font-size: 16pt; margin-bottom: 15px; color: #111; }
                h2 { font-size: 14pt; margin-bottom: 10px; color: #333; }
                p { margin-bottom: 10px; }
            </style>
        ';

        $fullHtml = '
            <!DOCTYPE html>
            <html>
            <head>
                <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
                ' . $styles . '
            </head>
            <body>
                ' . $headerHtml . '
                ' . $footerHtml . '
                <div class="content">
                    ' . $html . '
                </div>
            </body>
            </html>
        ';

        $pdf = Pdf::loadHTML($fullHtml);
        $pdf->setPaper('A4', 'portrait');
        
        return $pdf->stream($filename);
    }

    /**
     * Get a list of common variables for documentation/UI.
     *
     * @return array
     */
    public function getCommonVariables(): array
    {
        return [
            '[[PatientName]]' => 'Full name of the patient',
            '[[PatientFirstName]]' => 'First name of the patient',
            '[[PatientLastName]]' => 'Last name of the patient',
            '[[PatientDOB]]' => 'Date of birth',
            '[[Age]]' => 'Patient age',
            '[[Sex]]' => 'Patient gender',
            '[[GuardianName]]' => 'Guardian name (if applicable)',
            '[[DentistName]]' => 'Treating dentist',
            '[[ClinicName]]' => 'Name of the clinic',
            '[[ClinicAddress]]' => 'Address of the clinic',
            '[[Date]]' => 'Current date',
        ];
    }
}
