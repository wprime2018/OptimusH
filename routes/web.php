<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home'                      , 'HomeController@index')->name('home');
Route::get('/home/filiais'              , 'HomeController@index_Filiais')->name('DashFiliais');

//Route::post('register'                  , 'RegisterController')->middleware('auth');

Route::get('ranking_chip'               , 'Painel\Vendas@ranking_chip')->middleware('auth');
Route::get('vendas_pgto'                , 'Painel\Vendas@index_vendas_pgto')->middleware('auth');
Route::get('ranking'                    , 'Painel\Vendas@ranking_vendas')->middleware('auth');
Route::get('ranking_vend'               , 'Painel\Vendas@ranking_vendedores')->middleware('auth');
Route::get('ranking_diario'             , 'Painel\Vendas@ranking_diario')->middleware('auth');
Route::post('vendas/importVendas'       , 'Painel\SicTabEst1Controller@importTabEst3A')->middleware('auth');
Route::post('vendas/importProdVend'     , 'Painel\SicTabEst1Controller@importTabEst3B')->middleware('auth');
Route::post('vendas/vendedores'         , 'Painel\SicTabEst1Controller@importTabVend')->middleware('auth');
Route::post('vendas/recebimentos'       , 'Painel\SicTabEst1Controller@importTabEst7')->middleware('auth');
Route::get('est3B_ManyToOne_est3A'      , 'Painel\SicTabEst1Controller@est3B_ManyToOne_est3A')->middleware('auth');
Route::get('produtos'                   , 'Painel\SicTabEst1Controller@index')->middleware('auth');
Route::post('produtos/importtabest1'    , 'Painel\SicTabEst1Controller@importtabest1')->middleware('auth');
Route::post('produtos/importVendas'     , 'Painel\SicTabEst1Controller@importVendas')->middleware('auth');
Route::post('produtos/setor'            , 'Painel\SicTabEst1Controller@importTabEst8')->middleware('auth');
Route::post('produtos/edit'             , 'Painel\SicTabEst1Controller@edit')->middleware('auth');
Route::post('produtos/destroy'          , 'Painel\PedidosEstoque@destroy')->middleware('auth')->name('estoques.destroy');

Route::post('produtos/calculaEstoque'   , 'Painel\PedidosEstoque@calculaEstoque')->middleware('auth')->name('CalculaEstoque');
Route::get('produtos/pedComprar'        , 'Painel\PedidosEstoque@pedidosComprarTotal')->middleware('auth')->name('PedComprarTotal');
Route::get('produtos/EstoqueAtual'      , 'Painel\PedidosEstoque@ProdutosEstoqueAtual')->middleware('auth')->name('EstoqueAtual');
Route::get('produtos/MaisVendidos'      , 'Painel\PedidosEstoque@ProdutosMaisVendidos')->middleware('auth')->name('MaisVendidos');
Route::get('produtos/NaoVendidos'       , 'Painel\PedidosEstoque@ProdutosNaoVendidos')->middleware('auth')->name('NaoVendidos');
Route::get('produtos/transferir'        , 'Painel\PedidosEstoque@ProdutosTransferirCD')->middleware('auth')->name('Transferir');


Route::resource('tpDespesa'             , 'Painel\TpDespesasController')->middleware('auth');

Route::resource('despesas'              , 'Painel\DespesasController')->middleware('auth');

Route::resource('filial'                , 'Painel\FiliaisController')->middleware('auth');

Route::resource('user'                  , 'Painel\UserController')->middleware('auth');