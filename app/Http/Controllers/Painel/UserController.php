<?php

namespace App\Http\Controllers\Painel;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\Models\Painel\Filiais;

class UserController extends Controller
{

    public function index()
    {
        $title = 'Usuários';
        $Users = User::all();
        return view('painel.users.index', compact('Users', 'title'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $title = 'Cadastrar Usuários';
        $ListFiliais= Filiais::where('ativo','=', '1')->get();
        return view('painel.users.create-edit', compact('title','ListFiliais'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
       /** $dataForm = $request->except('_token');

        $insert = User::create($dataForm);

        if ($insert) {
            return redirect()->route('user.index');
        } else {
            return redirect()->route('user.create');
        }*/
        dd($request);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $User = User::find($id);

        $title = "Editar Usuários: {$User->name}";

        return view('painel.user.create-edit', compact('title', 'User'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $dataForm = $request->except('_token');

        $user = User::find($id); //Find para buscar pelo ID

        $update = User::update($dataForm);

        if ($update) {
            return redirect()->route('user.index');
        } else {
            return redirect()->route('user.create-edit', $id)->with(['errors' => 'Falha ao editar']);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $delete = User::destroy($id);

        if ($delete) {
            return redirect()->route('user.index')->with('success','Usuário deletado com sucesso!!!');
        } else {
            return redirect()->route('user.create-edit', $id)->with(['errors' => 'Falha ao deletar']);
        }
    }
}
