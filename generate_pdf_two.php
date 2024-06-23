<?php
// Error reporting (for debugging purposes)
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once('./vendors/tcpdf/tcpdf.php');

define('INCLUDED', true);
require_once('connect.php');
require_once('stopback.php');

// Register Montserrat fonts with TCPDF
$fontname_regular = TCPDF_FONTS::addTTFfont('./assets/fonts/Montserrat-Regular.ttf', 'TrueTypeUnicode', '', 96);
$fontname_bold = TCPDF_FONTS::addTTFfont('./assets/fonts/Montserrat-Bold.ttf', 'TrueTypeUnicode', '', 96);

// Fetch data for chart
$sql = "SELECT service_id FROM client_appointment";
$result = $conn->query($sql);

if ($result !== false && $result->num_rows > 0) {
    // Initialize array to hold service counts
    $serviceCounts = [];

    // Loop through results
    while ($row = $result->fetch_assoc()) {
        $serviceIds = json_decode($row['service_id']);
        if (is_array($serviceIds)) {
            foreach ($serviceIds as $serviceId) {
                if (!isset($serviceCounts[$serviceId])) {
                    $serviceCounts[$serviceId] = 0;
                }
                $serviceCounts[$serviceId]++;
            }
        }
    }

    // Fetch service names
    $serviceNames = [];
    $sql = "SELECT service_id, service_name FROM services";
    $result = $conn->query($sql);

    if ($result !== false && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $serviceNames[$row['service_id']] = $row['service_name'];
        }

        // Prepare data for the chart
        $data2 = [];
        foreach ($serviceCounts as $serviceId => $count) {
            $data2[] = ['service' => $serviceNames[$serviceId], 'count' => $count];
        }

        // Output JSON (if needed for debugging)
        // echo json_encode($data2);
    } else {
        die("Error: Unable to fetch service names");
    }
} else {
    die("Error: Unable to fetch data from client_appointment table");
}

// Create new TCPDF instance
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// Set document information
$pdf->SetAuthor('Your Name');
$pdf->SetTitle('Charts PDF');
$pdf->SetSubject('TCPDF Tutorial');
$pdf->SetKeywords('TCPDF, PDF, example, test, guide');

// Set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// Set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

// Set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// Set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// Add a page
$pdf->AddPage();

// Title
$pdf->SetFont($fontname_bold, '', 20); // Set font to bold for the title
$pdf->Cell(0, 15, 'Browlesque', 0, 1, 'C');
$pdf->SetFont($fontname_regular, '', 12); // Switch back to regular font
$pdf->Ln(5);

// Set the correct timezone
date_default_timezone_set('Asia/Manila');

// Get current date and time
$currentDateTime = date('F j, Y, \a\t h:i A');

// Construct HTML with dynamic date and time
$html1 = '<h3>Total Number per Services Availed Report</h3>';
$html1 .= '<h5><span style="font-weight:normal;">Report generated on ' . $currentDateTime . '.</span></h5>';
$html1 .= '<table border="1" cellpadding="5"><tr><th><strong>Service Name</strong></th><th><strong>Total Number</strong></th></tr>';

// Loop through serviceCounts to generate table rows
foreach ($data2 as $item) {
    $html1 .= '<tr>';
    $html1 .= '<td>' . htmlspecialchars($item['service']) . '</td>';
    $html1 .= '<td>' . htmlspecialchars($item['count']) . '</td>';
    $html1 .= '</tr>';
}

$html1 .= '</table>';

// Output chart data as HTML
$pdf->writeHTML($html1, true, false, true, false, '');

// Set the output filename
$outputFilename = 'Total_Number_per_Services_Availed.pdf';

// Close and output PDF document
$pdf->Output($outputFilename, 'D'); // 'D' parameter forces download

// Close database connection
$conn->close();
?>
