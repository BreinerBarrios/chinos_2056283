<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\MediaType;

class MediaTypeController extends Controller
{
    public function showmass(){
        // Mostrar la vista de carga masiva
        return view('media-types.insert-mass'); 

    }
    // typo mime:

    // 'application/vnd.ms-excel'

    // Validacion: 

    // 'video' => 'mime:video/avi,video/mpeg,video/quicktime'
    public function storemass(Request $r){

        // arreglo de mediatypes repetidos en bd
        $repetidos=[];

        $contadora = 0;

        // reglas de validacion 

        $reglas = [
            "media-types" => "required|mimes:csv,txt", "alpha", "mimes"
        ];

        $mensajes = [
                    "required" => "Se requiere seleccionar un archivo", 
                    "mimes" => "Solo archivos.csv"
                ];

                $validador = Validator::make($r->all() , $reglas , $mensajes);

                if($validador->fails()){
                    // Enviar mensaje de error de validacion a la vista 
                    return redirect('media-types/insert')->withErrors($validador);

                }else{  

                    // Almacena el archivo en storage/app/media-types, con el nombre media-types.csv 

        $path = $r->file('media-types')->storeAs("media-types" , "media-types.csv");

        // Abre el archivo para lectura, utilizando fopen.

        if (($puntero = fopen(base_path() . '\storage\app\media-types\media-types.csv', 'r')) !== false){

        // Recorrer cada fila del archivo

                while (($linea = fgetcsv($puntero, 1000, ',')) !== false) 
                {
                    // Buscar el media type en $linea
                    $conteo = MediaType::where('Name','=', $linea[0])->get()->count();

                    // Si no hay registros en meduatype que se llamen igual
                    if ($conteo == 0) { 

                         // Crear un objeto Modelo MediaType, para insertar
                    $m = new MediaType();
                    // asigno el nombre del mediatypes
                    $m->Name = $linea[0];
                    // gravo en sqlite el nuevo media-type
                    $m->save();
                    // 
                    $contadora++;

                    }else{  // hay registro del mediatype
                        // agregar una casilla al arreglo repetodos
                        $repetidos[] = $linea[0];

                    }
                    
                }
                //  poner mensaje de grabacion de carga masiva en la vista
                // di hubieron repetidos
                if (count($repetidos) == 0) {

                    // return redirect('media-types/insert')->with('Exito' , 
                    // "Carga masiva de medios realizada, Registros ingresados: $contadora");
                    return redirect('media-types/insert')->with('Exito' , 
                    "Carga masiva de medios realizada, Registros ingresados: $contadora");

                }else{

                    return redirect('media-types/insert')->with('Exito' ,
                    "Carga masiva con siguientes excepciones:")->with("repetidos" , $repetidos);
                }
                
        }
                   
                }  
    }
}

// $mediatype::where('Name', $linea[0])->get() == "[]"




