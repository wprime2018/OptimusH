<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Schema;
use Illuminate\Contracts\Events\Dispatcher;
use JeroenNoten\LaravelAdminLte\Events\BuildingMenu;
use app\user;
use App\Models\Painel\TpDespesas;
use App\Models\Painel\Filiais;
use App\Models\Painel\Despesas;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(Dispatcher $events)
    {
        View::share('key', 'value');
        Schema::defaultStringLength(191);

        $events->listen(BuildingMenu::class, function (BuildingMenu $event) {
            // $event->menu->add('MAIN NAVIGATION');
            $event->menu->add([
                'text'        => 'Filiais',
                'url'         => 'filial/',
                'icon'        => 'file',
                'label'       => Filiais::where('ativo','1')->count(),
            ]);
            $event->menu->add([
                'text'    => 'Despesas',
                'icon'    => 'credit-card',
                'submenu' => [
                    [
                        'text'        => 'Lista',
                        'url'         => 'despesas/',
                        'label'       => Despesas::count(),
                        'label_color' => 'success',
                    ],
                    [
                        'text'        => 'Tipos de Despesas',
                        'url'         => 'tpDespesa/',
                        'label'       => TpDespesas::count(),
                        'label_color' => 'success',     
                    ]
                ]
            ]);
            $event->menu->add([
                'text'    => 'Vendas',
                'icon'    => 'area-chart',
                'submenu' => [
                    [
                        'text'        => 'Lista',
                        'url'         => 'vendas/',
                    ],
                    [
                        'text'        => 'Comissões de Vendedores',
                        'url'         => 'vendas/comissoes',
                    ],
                    [
                        'text'        => 'Resumo de Forma de PGTO',
                        'url'         => 'vendas/fpgto',
                    ],
                    [
                        'text'        => 'Tipos de Pagamentos',
                        'url'         => 'vendas/tp_pgto',
                    ]
                ]
            ]);
            $event->menu->add([
                'text'    => 'Produtos',
                'icon'    => 'product-hunt',
                'submenu' => [
                    [
                        'text'        => 'Estoque Atual',
                        'url'         => 'produtos/EstoqueAtual',
                    ],
                    [
                        'text'        => 'Pedido de Compras',
                        'url'         => 'produtos/pedComprar',
                    ],
                    [
                        'text'        => 'Ranking dos Vendidos',
                        'url'         => 'produtos/MaisVendidos',
                    ],
                ]
            ]);
            $event->menu->add([
                'text'    => 'Usuários',
                'icon'    => 'users',
                'submenu' => [
                    [
                        'text' => 'Cadastrar',
                        'url'  => 'user/',
                        'icon_color' => 'aqua',
                    ],
                    [
                        'text' => 'Funções',
                        'url'  => '#',
                        'icon_color' => 'aqua',
                    ],
                    [
                        'text' => 'Permissões',
                        'url'  => '#',
                        'icon_color' => 'aqua',
                    ],
                    [
                        'text' => 'Change Password',
                        'url'  => 'admin/settings',
                        'icon' => 'lock',
                    ],
                ]
            ]);
    
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
