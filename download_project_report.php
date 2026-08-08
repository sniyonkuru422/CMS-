<?php

ob_start();
// Authentication
include("auth.php");

checkLogin();


// Allow only company users
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


// Get logged-in company
$company_id = $_SESSION['company_id'];

// Get company information
$companyQuery = mysqli_query($conn,
"SELECT company_name
FROM companies
WHERE company_id='$company_id'"
);

$company = mysqli_fetch_assoc($companyQuery);
$company_name = $company['company_name'];


//Get project budget data
$result = mysqli_query($conn,

"SELECT 

projects.project_name,
projects.location,
projects.budget,

SUM(expenses.amount) AS total_expenses
FROM projects
LEFT JOIN expenses
ON projects.project_id = expenses.project_id
WHERE projects.company_id='$company_id'
GROUP BY projects.project_id"

);

//Create PDF
$pdf = new FPDF();
$pdf->AddPage();

$pdf->SetFont('Arial','B',16);
$pdf->Cell(0,10,'Project Budget Report',0,1,'C');

$pdf->SetFont('Arial','',12);
$pdf->Cell(0,10,'Company: '.$company_name,0,1);

$pdf->Cell(0,10,'Generated Date: '.date('d M Y'),0,1);
$pdf->Ln(10);

//Add table heading
$pdf->SetFont('Arial','B',10);

$pdf->Cell(40,10,'Project',1);
$pdf->Cell(35,10,'Location',1);
$pdf->Cell(35,10,'Budget',1);
$pdf->Cell(35,10,'Expenses',1);
$pdf->Cell(35,10,'Remaining',1);

$pdf->Ln();

$pdf->SetFont('Arial','',10);


//Inser project records

while($row = mysqli_fetch_assoc($result)){


    $expenses = $row['total_expenses'] ?? 0;
    $remaining = $row['budget'] - $expenses;

    $pdf->Cell(40,10,$row['project_name'],1);
    $pdf->Cell(35,10,$row['location'],1);
    $pdf->Cell(35,10,number_format($row['budget']),1);
    $pdf->Cell(35,10,number_format($expenses),1);
    $pdf->Cell(35,10,number_format($remaining),1);

    $pdf->Ln();

}

//A line that downloads the PDF
$pdf->Output('I','Project_Budget_Report.pdf');

?>