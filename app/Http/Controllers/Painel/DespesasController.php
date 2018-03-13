<?php

namespace App\Http\Controllers\Painel;

use App\Http\Controllers\Controller;
use App\Models\Painel\Despesas;
use App\Models\Painel\Filiais;
use App\Models\Painel\TpDespesas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DespesasController extends Controller
{
    private $Despesa;
    
    public function __construct(Despesas $Despesa)
    {
        $this->Despesas = $Despesa;
    }

    public function index()
    {
        $title = 'Lista de Despesas';
        $Despesas = $this->Despesas->join('tb_filiais', 'tb_despesas.filial_id', '=', 'tb_filiais.id')
                                    ->join('tb_tpdespesas', 'tb_despesas.tp_desp_id', '=', 'tb_tpdespesas.id')
                                    ->where('ativo','=', '1')
                                    ->select('tb_despesas.*', 'tb_filiais.fantasia','tb_tpdespesas.descricao as desc_tipo')
                                    ->get();

        return view('painel.despesas.index', compact('Despesas', 'title'));
    }

    public function create()
    {
        $title = 'Cadastrar Despesas';
        $ListFiliais= Filiais::where('ativo','=','1')->get();
        $ListTpDespesas = TpDespesas::get();
        $CurrentUser =  auth()->user()->name;
        return view('painel.despesas.create-edit', compact('title','ListFiliais','ListTpDespesas', 'CurrentUser'));
    }

    public function store(Request $request)
    {
        //dd($request->all());
        //dd($request->only(['fantasia','logradouro']));
        //dd($request->except(['cnpj','ie']));
        //dd($request->input('logradouro'));
        $dataForm = $request->except('_token');
        $dataForm['fixa'] = ($dataForm['fixa'] == '') ? 0 : 1;

        $file = $request->image;
        if ($request->file('image')->isValid() && $request->hasFile('image')) {
            $name = uniqid(date('HisYmd'));
            $extension = $request->image->extension();
            $nameFile = "{$name}.{$extension}";
            $dataForm['path_comp'] = $nameFile ;
            $upload = $request->image->storeAs('despesas', $nameFile);
        }
        
        $insert = $this->Despesas->create($dataForm);
        if ($insert) {
            return redirect()->route('despesas.index');
        } else {
            return redirect()->route('despesas.create');
        }

    }

    public function show($id)
    {
        $Despesas = $this->Despesas->find($id);

        $title = "Deletando: {$Despesas->tipo_despesas->descricao}";

        return view('painel.despesas.show', compact('title', 'Despesas'));
    }

    public function edit($id)
    {
        $Despesas = $this->Despesas->find($id)
                                    ->join('tb_filiais', 'tb_despesas.filial_id', '=', 'tb_filiais.id')
                                    ->join('tb_tpdespesas', 'tb_despesas.tp_desp_id', '=', 'tb_tpdespesas.id')
                                    ->select('tb_despesas.*', 'tb_filiais.fantasia', 'tb_tpdespesas.descricao as desc_tipo')
                                    ->with('tipo_despesa')
                                    ->get();

        dd($Despesas);
        foreach($Despesas as $Desp) {
            $title = "Editar Despesa: {$Desp->tipo_despesa->descricao}";
        }
        $ListFiliais= Filiais::get();
        $ListTpDespesas = TpDespesas::get();
        $CurrentUser =  auth()->user()->name;
        return view('painel.despesas.create-edit', compact('title', 'Despesas', 'ListFiliais', 'ListTpDespesas', 'CurrentUser'));
    }

    public function update(Request $request, $id)
    {
        $dataForm = $request->except('_token');

        $dataForm['fixa'] = ($dataForm['fixa'] == 'on') ? 1 : 0;

        $Despesa = $this->Despesas->find($id); //Find para buscar pelo ID

        $update = $Despesa->update($dataForm);

        if ($update) {
            return redirect()->route('despesas.index');
        } else {
            return redirect()->route('despesas.edit', $id)->with(['errors' => 'Falha ao editar']);
        }

    }

    public function destroy($id)
    {
        $delete = $this->Despesas->destroy($id); // Colocando Array para deletar múltiplus registros.
        
        if ($delete)
            return redirect()->route('despesas.index');
        else
        return redirect()->route('despesas.show', $id)->with(['errors'=>'Falha ao deletar']);
    }
}
