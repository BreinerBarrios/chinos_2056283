<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Codedge\Fpdf\Fpdf\Fpdf;
use App\Artista;

class PDFController extends Controller
{
    public function index(){
        // crear el objeto pdf
        $pdf = new Fpdf();
        $pdf->SetDrawColor(140,170,250);
        $pdf->SetFillColor(0,135,250);
        // Añadir pagina 
        $pdf->AddPage();
        // establecer una cordenada (1o,10) para comenzar a pintar
        $pdf->SetXY(10,10);
        // establecer tipo de letra
        $pdf->SetFont('Arial' , 'B', 16);
        // Establecer un contendo para mostar 

        $pdf->Cell(110,10,"Nombre artista" , 1,0, "C" );
        $pdf->Cell(60,10, utf8_decode("Número albunmes") , 1,1, "C" );
        $pdf->SetTextColor(0,0,80);
        

        //  Recorrer el arreglo de artista para mostrar
        // artista y numero de discos por artista
        $artistas = Artista::all();
        $pdf->SetFont('Arial', 'I',12);
        foreach ($artistas as $a) {
            
            $pdf->Cell(110,10, substr(utf8_decode($a->Name), 0, 50)  , 1, 0, "L", true  );
            $pdf->Cell(60,10, $a->albumes()->count() , 1,1, "C" , true);
        }



        // sacar el pdf al naverador 
        // $pdf->Output();

        // utilizar objeto response 
        $response = response($pdf->Output());
        // Definir el tipo mime 
        $response->header("Content-Type" , 'application/pdf');
        // retorne una vista al navegador
        return $response;
    }
}
