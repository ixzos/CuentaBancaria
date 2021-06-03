<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Usuario;
use App\Models\Cuenta;
use Illuminate\Support\Facades\DB;

class UsuarioController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $usuarios = Usuario::all();
        return $usuarios;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $usuarioReturn = DB::table('usuarios')
                                ->join('cuentas','usuarios.id', '=', 'cuentas.idUsuario' )
                                ->select('usuarios.*','cuentas.saldo')
                                ->where('usuarios.cedula', $request->cedula)
                                ->get();

        if(count($usuarioReturn) > 0)
        {
            return response()->json(['message'=> 'Usuario ya se encuentra registrado'], 201);
        }            

        $usuario = new Usuario;
        $usuario -> nombre = $request -> input('nombre');
        $usuario -> apellido = $request -> input('apellido');
        $usuario -> cedula = $request -> input('cedula');       
        $usuario -> save();

        return response()->json(['message'=> 'Usuario registrado satisfactoriamente'], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($cedula)
    {
        //        
        $usuarioReturn = DB::table('usuarios')
                                ->join('cuentas','usuarios.id', '=', 'cuentas.idUsuario' )
                                ->select('usuarios.*','cuentas.saldo')
                                ->where('usuarios.cedula', $cedula)
                                ->get();

        if(count($usuarioReturn) <= 0)
        {
            return response()->json(['message'=> 'Usuario no registrado'], 404);
        }
        return $usuarioReturn;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $cedula
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $cedula)
    {
         
        //
        $saldo = 0;
        $id =0;
        $usuarioReturn = DB::table('usuarios')
                                ->join('cuentas','usuarios.id', '=', 'cuentas.idUsuario' )
                                ->select('usuarios.*','cuentas.saldo')
                                ->where('usuarios.cedula', $cedula)
                                ->get();
        if(count($usuarioReturn) <= 0)
        {
            return response()->json(['message'=> 'Usuario no registrado'], 404);
        }

        foreach($usuarioReturn as $valores)
        {
        $saldo = ($valores->saldo);
        $id = ($valores->id);
        };                        
        
        $saldoSuma = $saldo + $request->saldoAdd;

        $usuarioAddMoney = Cuenta::where('idUsuario', $id)
                                   ->update(['saldo' => $saldoSuma]);
                              
        return response()->json(['message'=> 'Saldo actualizado'], 200); 
    }
    
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
