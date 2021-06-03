<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Usuario;
use App\Models\Cuenta;

class CuentaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
     * @param  int  $cedulaEnvio
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $cedulaEnvio)
    {         
        $saldo = 0;
        $idUsuarioEnvia;
        $usuarioSend = DB::table('usuarios')
                            ->join('cuentas','usuarios.id', '=', 'cuentas.idUsuario' )
                            ->select('usuarios.*','cuentas.saldo')
                            ->where('usuarios.cedula', $cedulaEnvio)
                            ->get();
        $usuarioReceive = DB::table('usuarios')
                            ->join('cuentas','usuarios.id', '=', 'cuentas.idUsuario' )
                            ->select('usuarios.*','cuentas.saldo')
                            ->where('usuarios.cedula', $request->cedulaRecibe)
                            ->get();                    

    if(count($usuarioSend) <= 0)
    {
        return response()->json(['message'=> 'Usuario no registrado'], 404);
    }
    if(count($usuarioReceive) <= 0)
    {
        return response()->json(['message'=> 'El usuario a quien desea transferir dinero no se encuentra registrado'], 404);
    }
    else
    {
        foreach($usuarioSend as $valores)
        {
        $saldoSend = ($valores->saldo);
        $idUsuarioEnvia = ($valores->id);
        };

        if($request->valTransfer > $saldoSend)
        {
            return response()->json(['message'=> 'Saldo insuficiente'], 404);
        }
        else
        {
            foreach($usuarioReceive as $values)
            {
                $saldoActual = ($values->saldo);
                $idUsuarioReceive = ($values->id);
            };

            $saldoNuevoReceive = $request->valTransfer + $saldoActual;
            $saldoNuevoSend = $saldoSend - $request->valTransfer;
        
            $usuarioLessMoney = Cuenta::where('idUsuario', $idUsuarioEnvia)
                                       ->update(['saldo' => $saldoNuevoSend]);

            $usuarioAddMoney = Cuenta::where('idUsuario', $idUsuarioReceive)
                                       ->update(['saldo' => $saldoNuevoReceive]);
                                  
            return response()->json(['message'=> 'Transferencia Exitosa'], 200);
        }

    }
    return response()->json(['message'=> 'Bad request'], 400);                                       
    
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
