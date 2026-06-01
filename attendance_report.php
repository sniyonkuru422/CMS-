<?php
require('fpdf/fpdf.php');
include("database/connect.php");


$pdf = new FPDF();
$pdf->AddPage();

$pdf->SetFont("Arial","B",10);
$pdf->Cell(0,10,"Attendance Report",0,1,);

$result =$conn->query("SELECT attendance.*, user.name FROM attendance JOIN user ON
attendence.worker_id = user.user_id");

while ($row = $result->fetch_assoc()) {
    $pdf->Cell(0,10,

    $row['name'] . " - " .$row['date'] . " - " . $row['status'], 0,1
    );
}
$pdf->Output();
?>