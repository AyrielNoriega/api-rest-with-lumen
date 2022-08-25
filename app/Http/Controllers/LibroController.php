<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Libro;

class LibroController extends Controller
{
    public function index(){

        $datosLibro = Libro::all();
        return response()->json($datosLibro);
    }

    public function store(Request $request)
    {
        $libro = new Libro;
        $libro->titulo  = $request->titulo;
        $libro->archivo = $request->archivo;
        $libro->save();

        return response()->json($request);
    }

}