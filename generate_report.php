<?php
// This file generates a report based on the selected criteria (e.g., date range, project, worker attendance) and displays it to the user. The report can be exported as a PDF or Excel file for further analysis and record-keeping.

require('fpdf/fpdf.php'); // Include the FPDF class library for PDF generation

// Database connection parameters Added
$conn = mysqli_connect($host, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

//Create pdf report based on the data from the database
$pdf= new FPDF(); 
$pdf->AddPage();

$pdf->SetFont('Arial','B',18);
$pdf->Cell(0,10,'Construction Material Report',0,1,'c'); 

$pdf->SetFont('Arial','',12);
$pdf->Cell(0,10,'Generated on: ' . date("Y-m-d"),0,1,'c');
$pdf->Ln(5);

// Table Header
$pdf->SetFont('Arial','B',10);
$pdf->Cell(60,10,'Material Name',1);
$pdf->Cell(40,10,'Quantity',1);
$pdf->Cell(40,10, 'Unit Price', 1);
$pdf->Cell(40,10, 'Total Cost', 1);
$pdf->Ln();

// Fetch data from the database and populate the PDF
$result = $conn->query("SELECT * FROM materials"); // Replace with your actual query

$pdf->SetFont('Arial','',12);

$total = 0; // Initialize total cost variable

while ($row = $result->fetch_assoc()) {
    $pdf->Cell(60,10,$row['name'],1);
    $pdf->Cell(40,10,$row['quantity'],1);
    $pdf->Cell(40,10, $row['unit_price'], 1);
    $pdf->Cell(40,10, $row['total_cost'], 1);
    $pdf->Ln();

    $total += $row['total_cost']; // Accumulate total cost
}
// This shows the total cost at the end of the report
$pdf->Ln(5);
$pdf->SetFont('Arial','B',12);
$pdf->Cell(140,10,'Total Cost:',1);
$pdf->Cell(40,10, $total, 1);
$pdf->Output('','');
?>