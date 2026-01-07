<?php
    require("fpdf/fpdf.php");

    class PDF extends FPDF{
        //Cabecera de mi documento
        function header(){
            //Agregamos un banner, imagen o logo
            $this->Image('encabezado.fw.png',10,10,200);
            // $this->Image('logoIPNChico.fw.png',5,5);
            //$this->Image('logoESCOMAzul.fw.png',170,10); 
            $this->Ln(30);
        }

        //Pie de página
        function footer(){
            $this->SetY(-20);
            $this->SetFont('helvetica','I','10');
            //Creamos nuestro pie de página
            $this->Cell(0,15,'Pagina '.$this->PageNo().'/{nb}',0,0,'C');
        }
    }

    //Creación del objeto de la clase heredada
    $pdf = new PDF('P','mm','A4');
    /*Define un alias para el número total de páginas. 
    el total de las páginas vd={nb}*/
    $pdf->AliasNBPages(); 
    $pdf->AddPage();
    $pdf->SetFont('helvetica','B',20);
     //$pdf->Cell(0,10,'Buenos días alumno del 4CM4',1,1,'R');
    for($i=1;$i<=30;$i++){
        $pdf->Cell(40,20,'Texto:'.$i,0,1);    
        }
    $pdf->Output();
?>