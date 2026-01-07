<?php
    require('fpdf/fpdf.php');

    $pdf = new FPDF('P','mm','letter');
    $pdf ->AddPage();
    $pdf ->SetFont('helvetica','BI','20');
    $pdf ->Cell(0,10,'Hola chicos del 4CM5. Feliz 2026',1,0,'R');
    $pdf->Output(); 
?>