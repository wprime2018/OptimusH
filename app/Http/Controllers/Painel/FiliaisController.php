<?php

namespace App\Http\Controllers\Painel;

use App\Http\Controllers\Controller;
use App\Http\Requests\Painel\FilialFormRequest;
use App\Models\Painel\Filiais;
use Illuminate\Http\Request;

class FiliaisController extends Controller
{
    private $Filial;
    private $totalPage;
    public $countFilialActive;

    public function __construct(Filiais $Filial)
    {
        $this->Filiais = $Filial;
    }

    public function index()
    {
        $title = 'Filiais';
        $Filiais = $this->Filiais->all();
        $countFilialActive = Filiais::where('ativo', '==', 1)->count();
        return view('painel.filiais.index', compact('Filiais', 'title'));
    }

    public function create()
    {
        $title = 'Cadastrar Filiais';
        return view('painel.filiais.create-edit', compact('title'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(FilialFormRequest $request)
    {
        //dd($request->all());
        //dd($request->only(['fantasia','logradouro']));
        //dd($request->except(['cnpj','ie']));
        //dd($request->input('logradouro'));
        $dataForm = $request->except('_token');

        $dataForm['ativo'] = ($dataForm['ativo'] == '') ? 0 : 1;

        //Valida os dados
        //$this->validate($request, [ 'cnpj' => 'cnpj' ]);

        $insert = $this->Filiais->create($dataForm);

        if ($insert) {
            return redirect()->route('filial.index');
        } else {
            return redirect()->route('filial.create');
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $Filiais = $this->Filiais->find($id);

        $title = "Deletando: {$Filiais->fantasia}";

        return view('painel.filiais.show', compact('title', 'Filiais'));
    }

    public function edit($id)
    {
        //Recupera os produtos para mostrar na tela create como edição de dados.
        $Filiais = $this->Filiais->find($id);

        $title = "Editar Filial: {$Filiais->fantasia}";

        return view('painel.filiais.create-edit', compact('title', 'Filiais'));
    }

    public function update(FilialFormRequest $request, $id)
    {
        $dataForm = $request->except('_token');

        $dataForm['ativo'] = ($dataForm['ativo'] == 'on') ? 1 : 0;

        $filial = $this->Filiais->find($id); //Find para buscar pelo ID

        $update = $filial->update($dataForm);

        if ($update) {
            return redirect()->route('filial.index');
        } else {
            return redirect()->route('filial.edit', $id)->with(['errors' => 'Falha ao editar']);
        }

        /*$update = $this->Filiais->where('valor','=', 1025.39)
    ->update([
    'descricao'     => 'Atualizando a descrição do Item com WHere',
    'filial'        => 2,
    'valor'         => 1025.39,
    'obs'           => "Testando atualização de produtos no laravel"
    ]);
    if( $update )
    return "Alterado com sucesso! 2";
    else
    return 'Falha ao Alterar';*/

    }

    public function destroy($id)
    {
        $delete = $this->Filiais->destroy($id);

        if ($delete) {
            return redirect()->route('filial.index')->with('success','Filial deletada com sucesso!!!');
        } else {
            return redirect()->route('filial.show', $id)->with(['errors' => 'Falha ao deletar']);
        }
        
    }

    public function filial_NCD() {
        $Filiais = Filiais::where('ativo', '=', 1)->whereNull('filial_cd')->get();
        return $Filiais;
    }
    public function filial_CD() {
        $Filiais = Filiais::where('ativo', '=', 1)->whereNotNull('filial_cd')->get();
        return $Filiais;
    }
}
