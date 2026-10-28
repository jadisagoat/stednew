<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Dompdf\Dompdf;

class Download extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('fpdf');
        $this->load->library('fpdi');
    }

    // public function index()
    // {

    //     $url = base_url() . 'uploads/test.pdf';
    //     header('Content-type: application/pdf');
    //     header('Content-Disposition: inline; filename="ayman"');
    //     header('Content-Transfer-Encoding: binary');
    //     header('Accept-Ranges: bytes');
    //     @readfile($url);
    // }



    // public function generatepdf()
    // {

    //     $this->load->helper('file');
    //     require_once(APPPATH . 'third_party/dompdf/autoload.php');

    //     $domdpf = new DOMPDF();
    //     $domdpf->loadHtml('<h1>HELLO WORLD</h1>');
    //     $domdpf->setPaper('A4', 'Landscape');
    //     $domdpf->render();

    //     $domdpf->stream();
    // }


    public function generate($text = 'Bilal Mrad')
    {
        error_reporting(E_ALL);
        ini_set('display_errors', 1);


        $url = FCPATH . 'uploads/test.pdf';

        // Create a new FPDI instance
        $pdf = new Fpdi();

        // Set the source file
        $pdf->setSourceFile($url);

        // Import the first page
        $templateId = $pdf->importPage(1);

        // Get the size of the imported page
        $size = $pdf->getTemplateSize($templateId);

        // Add a page with the same size as the template
        $pdf->AddPage($size['orientation'], array($size['width'], $size['height']));

        // Use the imported page as template and fit it to the entire page
        $pdf->useTemplate($templateId, 0, 0, $size['width'], $size['height']);

        // Add custom font
        $pdf->AddFont('GreatVibes-Regular', '', 'GreatVibes-Regular.php');
        $text1 = 'Your Custom Text Here';

        // Add first text
        $pdf->SetFont('GreatVibes-Regular', '', 25);
        $pdf->SetTextColor(199, 177, 134); // Color
        $pdf->SetXY(120, 95);
        $pdf->Cell(40, 10, $text1);

        // Set font and add text at specific positions
        $pdf->SetFont('Arial', 'B', 16);
        $pdf->SetTextColor(0, 0, 0); // Black color

        // Set the background color (example: light gray)
        $pdf->SetFillColor(200, 200, 200);

        $text2 = 'QUALITY MANAGEMENT SYSTEMQUALITY MANAGEMENT SYSTEMQUALITYQUALITY MANAGEMENT SYSTEMQUALITY MANAGEMENT SYSTEMQUALITY';

        // Calculate the width of the text to center it
        $pageWidth = $pdf->GetPageWidth();
        $cellWidth = $pdf->GetStringWidth($text2) - 10; // Adding some padding

        // Truncate text if it exceeds the cell width
        if ($pdf->GetStringWidth($text2) - 40 > $pageWidth) {
            while ($pdf->GetStringWidth($text2 . '...') > $pageWidth - 10) { // Adjusting for some padding
                $text2 = substr($text2, 0, -1);
            }
            $text2 .= '...';
            $cellWidth = $pdf->GetStringWidth($text2) - 10; // Adding some padding
        }

        $width = $pageWidth - $cellWidth;
        if ($width <= 0) {
            $width = 30;
            $cellWidth = 100;
        }
        $x = $width / 2; // Calculate x position for center alignment

        // Set the background fill for the cell
        $pdf->SetXY($x, 115);
        $pdf->SetTextColor(0, 0, 0); // Black color
        $pdf->SetFillColor(200, 200, 200);
        $pdf->Cell($cellWidth, 30, $text2, 0, 1, 'C', true);

        // Add the third text
        $pdf->SetXY(60, 147);
        $pdf->Cell(10, 20, "June 4, 2024");

        // Add fourth text
        $pdf->AddFont('GreatVibes-Regular', '', 'GreatVibes-Regular.php');
        $pdf->SetFont('GreatVibes-Regular', '', 25);
        $text3 = 'Imran Khan';

        // Maximum width for the cell
        $maxCellWidth = 50; // Adjust as necessary

        // Truncate text if it exceeds the maximum cell width
        if ($pdf->GetStringWidth($text3) > $maxCellWidth) {
            while ($pdf->GetStringWidth($text3 . '...') > $maxCellWidth) {
                $text3 = substr($text3, 0, -1);
            }
            $text3 .= '...';
        }

        // Set position and add the truncated text
        $pdf->SetXY(200, 160);
        $pdf->SetFillColor(200, 200, 200);
        $pdf->SetTextColor(90, 0, 0); // Dark red color
        $pdf->Cell($maxCellWidth, 10, $text3);

        // Output the PDF inline
        $pdf->Output('I', 'generatedaaa.pdf');
    }
}
