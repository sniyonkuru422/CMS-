<!--CODES TO DOWNLOAD ATTENDANCE REPORTS-->

<?php
ob_start();
require('fpdf/fpdf.php');
include("database/connect.php");


$pdf = new FPDF('P','mm','A4');
$pdf->AddPage();

$pdf->SetFont("helvetica","B",16);
$pdf->Cell(0,10,"Attendance Report",0,1,'C');

$pdf->Ln(5);

// Table headings

$pdf->SetFont('helvetica','B',11);
$pdf->Cell(40,10,'Name',1);
$pdf->Cell(35,10,'Type',1);
$pdf->Cell(35,10,'Role',1);
$pdf->Cell(30,10,'Date',1);
$pdf->Cell(30,10,'Status',1);

$pdf->Ln();

// Normal text
$pdf->SetFont('helvetica','',10);

$sql = "

SELECT CASE WHEN attendance.worker_type='SYSTEM USER' THEN users.name

ELSE workers.name

END AS worker_name, attendance.worker_type, attendance.role, attendance.date, attendance.status FROM attendance

LEFT JOIN users ON attendance.user_id = users.user_id

LEFT JOIN workers ON attendance.worker_id = workers.worker_id

ORDER BY attendance.date DESC

";

$result = $conn->query($sql);

if(!$result){

    die("Database Error: ".$conn->error);

}

while($row = $result->fetch_assoc()){


        $pdf->Cell(40,10,$row['worker_name'],1);

        $pdf->Cell(35,10,$row['worker_type'],1);

        $pdf->Cell(35,10,$row['role'],1);

        $pdf->Cell(30,10,$row['date'],1);

        $pdf->Cell(30,10,$row['status'],1);


        $pdf->Ln();
}

$pdf->Output();

?>