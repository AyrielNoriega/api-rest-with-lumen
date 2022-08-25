<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Libro;
use Carbon\Carbon;

class LibroController extends Controller
{
    public function index(){

        $datosLibro = Libro::all();
        return response()->json($datosLibro);
    }

    public function store(Request $request)
    {
        
        if ($request->hasFile('archivo')) {
            $nombreArchivoOriginal = $request->file('archivo')->getClientOriginalName();
            $nuevoNombre = Carbon::now()->timestamp ."_". $nombreArchivoOriginal;
            $carpetaDestino = './upload/';
 
            $request->file('archivo')->move($carpetaDestino, $nuevoNombre);

            $libro = new Libro;
            $libro->titulo  = $request->titulo;
            $libro->archivo = ltrim($carpetaDestino, '.').$nuevoNombre;
            $libro->save();
        }


        $file = $request->file('archivo');

        // $name = $file->hashName(); // Generate a unique, random name...
        // $extension = $file->extension(); 

        return response()->json($nuevoNombre);
    }

    public function show($id){
        $datosLibro = new Libro;
        $datosLibro = $datosLibro->find($id);

        return response()->json($datosLibro);
    }

    public function delete($id){
        $datosLibro = Libro::find($id);

        if ($datosLibro) {
            $rutaArchivo = base_path('public') . $datosLibro->archivo;

            if (file_exists($rutaArchivo)) {
                unlink($rutaArchivo);
            }

            $datosLibro->delete();
        } else {
            return response()->json('No hay registro');
        }

        return response()->json('Registro borrado');
    }

    public function update(Request $request, $id){

        $datosLibro = Libro::find($id);

        if ($request->input('titulo')) {
            $datosLibro->titulo = $request->input('titulo');
        }

        if ($request->hasFile('archivo')) {

            if ($datosLibro) { //delete file
                $rutaArchivo = base_path('public') . $datosLibro->archivo;
    
                if (file_exists($rutaArchivo)) {
                    unlink($rutaArchivo);
                }
    
                $datosLibro->delete();
            }

            //save new file
            $nombreArchivoOriginal = $request->file('archivo')->getClientOriginalName();
            $nuevoNombre = Carbon::now()->timestamp ."_". $nombreArchivoOriginal;
            $carpetaDestino = './upload/';
 
            $request->file('archivo')->move($carpetaDestino, $nuevoNombre);

            $datosLibro = new Libro;
            $datosLibro->archivo = ltrim($carpetaDestino, '.').$nuevoNombre;
        }

        $datosLibro->save();

        return response()->json($datosLibro);
    }


}