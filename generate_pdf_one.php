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

// SQL query to fetch data from association_rules table ordered by conviction descending
$sql = "SELECT antecedents, consequents, conviction FROM association_rules ORDER BY conviction DESC";

// Fetch data from the database
$result = $conn->query($sql);

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
$html1 = '<h3 style="margin-bottom: 0;">Service Association Report</h3>';
$html1 .= '<h5 style="margin-top: 0;"><span style="font-weight:normal;">It identifies the most popular service combinations, that can help the business understand customer preferences and optimize service offerings.</span></h5>';
$html1 .= '<h5 style="margin-top: 0;"><span style="font-weight:normal;"><strong>Conviction</strong> simply refers to the likelihood that if Service A is availed, Service B will be availed too. The higher it is from 1, the greater its chances. </span></h5>';
$html1 .= '<h5><span style="font-weight:normal;">Report generated on ' . $currentDateTime . '.</span></h5>';
$html1 .= '<table border="1" cellpadding="5"><tr><th><strong>Rank</strong></th><th><strong>Conviction</strong></th><th><strong>Top Pick</strong></th><th><strong>Top Pick Combination</strong></th></tr>';

// Initialize rank counter
$rank = 1;

// Loop through each row fetched from the database
while ($row = $result->fetch_assoc()) {
    $html1 .= '<tr>';
    $html1 .= '<td>' . $rank . '</td>'; // Display rank
    $html1 .= '<td>' . htmlspecialchars($row['conviction']) . '</td>';
    $html1 .= '<td>' . htmlspecialchars($row['antecedents']) . '</td>';
    $html1 .= '<td>' . htmlspecialchars($row['consequents']) . '</td>';
    $html1 .= '</tr>';
    $rank++;
}


$html1 .= '</table>';

// Output chart data as HTML
$pdf->writeHTML($html1, true, false, true, false, '');

// Set the output filename
$outputFilename = 'Service_Association_Report.pdf';

// Close and output PDF document
$pdf->Output($outputFilename, 'D'); // 'D' parameter forces download

// Close database connection
$conn->close();
?>
