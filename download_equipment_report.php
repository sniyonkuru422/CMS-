<?php

ob_start();

// Start session to access logged-in user information
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Authentication and role checking
include("auth.php");

checkLogin();

requireAnyRole([
    "COMPANY_ADMIN",
    "PROJECT_MANAGER",
    "SITE_ENGINEER",
    "SUPERVISOR",
    "STORE_KEEPER"
]);


// Database connection
include("database/connect.php");


// Load FPDF library
require('fpdf/fpdf.php');


// Get logged-in user's company
$company_id = $_SESSION['company_id'];


// ===============================
// GET COMPANY INFORMATION
// ===============================

$companyQuery = mysqli_query($conn,

"SELECT company_name 
FROM companies 
WHERE company_id='$company_id'"

);

$company = mysqli_fetch_assoc($companyQuery);

$company_name = $company['company_name'];

// ===============================
// GET EQUIPMENT SUMMARY DATA
// ===============================

// Total equipment
$totalEquipment = mysqli_fetch_assoc(mysqli_query($conn,

"SELECT COUNT(*) total
FROM equipment
WHERE company_id='$company_id'"

))['total'];


// Available equipment
$availableEquipment = mysqli_fetch_assoc(mysqli_query($conn,

"SELECT COUNT(*) total
FROM equipment
WHERE company_id='$company_id'
AND status='Available'"

))['total'];


// Under maintenance
$maintenanceEquipment = mysqli_fetch_assoc(mysqli_query($conn,

"SELECT COUNT(*) total
FROM equipment
WHERE company_id='$company_id'
AND status='Under Maintenance'"

))['total'];


// Damaged equipment
$damagedEquipment = mysqli_fetch_assoc(mysqli_query($conn,

"SELECT COUNT(*) total
FROM equipment
WHERE company_id='$company_id'
AND condition_status='Damaged'"

))['total'];


// Total value
$totalValue = mysqli_fetch_assoc(mysqli_query($conn,

"SELECT SUM(total_cost) total
FROM equipment
WHERE company_id='$company_id'"

))['total'];

// ===============================
// GET EQUIPMENT RECORDS
// ===============================


$result = mysqli_query($conn,

"SELECT equipment_name,
        equipment_type,
        quantity,
        condition_status,
        status

FROM equipment

WHERE company_id='$company_id'"

);



// ===============================
// CREATE PDF
// ===============================


$pdf = new FPDF();

$pdf->AddPage();


// Title

$pdf->SetFont('Arial','B',16);

$pdf->Cell(0,10,'Equipment Report',0,1,'C');

$pdf->Ln(5);


// Company name

$pdf->SetFont('Arial','',12);

$pdf->Cell(0,10,'Company: '.$company_name,0,1);

$pdf->Cell(0,10,'Generated Date: '.date('d M Y'),0,1);

$pdf->Ln(5);


// ===============================
// SUMMARY SECTION
// ===============================

$pdf->SetFont('Arial','B',12);

$pdf->Cell(0,10,'Equipment Summary',0,1);


$pdf->SetFont('Arial','',11);


$pdf->Cell(60,8,'Total Equipment:',0);
$pdf->Cell(30,8,$totalEquipment,0,1);


$pdf->Cell(60,8,'Available:',0);
$pdf->Cell(30,8,$availableEquipment,0,1);


$pdf->Cell(60,8,'Under Maintenance:',0);
$pdf->Cell(30,8,$maintenanceEquipment,0,1);


$pdf->Cell(60,8,'Damaged:',0);
$pdf->Cell(30,8,$damagedEquipment,0,1);


$pdf->Cell(60,8,'Total Value (RWF):',0);
$pdf->Cell(30,8,number_format($totalValue),0,1);



$pdf->Ln(10);



// ===============================
// EQUIPMENT TABLE
// ===============================


$pdf->SetFont('Arial','B',10);


$pdf->Cell(40,10,'Equipment',1);
$pdf->Cell(40,10,'Type',1);
$pdf->Cell(20,10,'Qty',1);
$pdf->Cell(45,10,'Condition',1);
$pdf->Cell(45,10,'Status',1);


$pdf->Ln();



$pdf->SetFont('Arial','',10);


while($row = mysqli_fetch_assoc($result)){


    $pdf->Cell(40,10,$row['equipment_name'],1);

    $pdf->Cell(40,10,$row['equipment_type'],1);

    $pdf->Cell(20,10,$row['quantity'],1);

    $pdf->Cell(45,10,$row['condition_status'],1);

    $pdf->Cell(45,10,$row['status'],1);


    $pdf->Ln();

}


// Download PDF

ob_end_clean();
$pdf->Output('I','Equipment_Report.pdf');
exit();

