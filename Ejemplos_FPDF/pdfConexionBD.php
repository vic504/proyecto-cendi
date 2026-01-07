<?php
    require("fpdf/fpdf.php");

    class PDF extends FPDF{
        function header(){
            $this->Image('encabezado.fw.png',10,10,200);
            $this->Ln(30);
        }

        function footer(){
            $this->SetY(-20);
            $this->SetFont('helvetica','I','10');
            $this->Cell(0,15,'Pagina '.$this->PageNo().'/{nb}',0,0,'C');
        }
    }

    $conexion = mysqli_connect('localhost','root','','sem26_1');
    $sql = "SELECT * FROM alumno WHERE boleta = 2020630500";
    $resultado = mysqli_query($conexion,$sql);
    $alumno = mysqli_fetch_array($resultado);
    $contenido = "$alumno[0] $alumno[1] $alumno[2] $alumno[3]";
    //Creación del objeto de la clase heredada
    $pdf = new PDF('P','mm','A4');
    $pdf->AliasNBPages(); 
    $pdf->AddPage();
    $pdf->SetFont('helvetica','B',20);
    $pdf->Cell(0,10,utf8_decode($contenido),1,1,'C');   
    $pdf->Output();
?>