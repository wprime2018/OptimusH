<?php

namespace App\Http\Controllers\Painel;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Painel\TpDespesas;
use App\Http\Requests\Painel\TpDespesaFormRequest;

class TpDespesasController extends Controller
{
    private $TpDespesa;
    //private $totalPage;

    public function __construct(TpDespesas $TpDespesa)
    {
        $this->TpDespesas = $TpDespesa;
    }

    public function index()
    {
        $title = 'TpDespesas';
        //$TpDespesas = $this->TpDespesas->paginate($this->totalPage);
        $TpDespesas = $this->TpDespesas->all();
        return view ('painel.tipos_despesas.index',compact('TpDespesas','title'));
    }

    public function create()
    {
        $title = 'Cadastrar TpDespesas';
        return view('painel.tipos_despesas.create-edit',compact('title'));
    }

    public function store(TpDespesaFormRequest $request)
    {
        $dataForm = $request->except('_token');

        $dataForm['compartilhada'] = ($dataForm['compartilhada'] == '') ? 0 : 1;

        $insert = $this->TpDespesas->create($dataForm);
        
        if ($insert)
            return redirect()->route('tpDespesa.index');
        else
            return redirect()->route('tpDespesa.create');
    }

    public function show($id)
    {
        $TpDespesas = $this->TpDespesas->find($id);

        $title = "Deletando: {$TpDespesas->descricao}";

        return view('painel.tipos_despesas.show',compact('title','TpDespesas'));
    }

    public function edit($id)
    {
        //Recupera os produtos para mostrar na tela create como edição de dados. 
        $TpDespesas = $this->TpDespesas->find($id);
        
        $title = "Editar Tipo de Despesa: {$TpDespesas->descricao}";

        return view('painel.tipos_despesas.create-edit',compact('title','TpDespesas'));
    }

    public function update(TpDespesaFormRequest $request, $id)
    {
        $dataForm = $request->except('_token');

        $dataForm['compartilhada'] = ($dataForm['compartilhada'] == 'on') ? 1 : 0;

        $TpDespesa = $this->TpDespesas->find($id); //Find para buscar pelo ID
        
        $update = $TpDespesa->update($dataForm);

        if( $update )
            return redirect()->route('tpDespesa.index');
        else
            return redirect()->route('tpDespesa.edit', $id)->with(['errors'=>'Falha ao editar']);           
    }

    public function destroy($id)
    {
        $delete = $this->TpDespesas->destroy($id);
        
        if ($delete)
            return redirect()->route('tpDespesa.index');
        else
        return redirect()->route('tpDespesa.show', $id)->with(['errors'=>'Falha ao deletar']);
    }
}
