<?php

namespace App\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use JeroenNoten\LaravelAdminLte\Events\BuildingMenu;
use App\Models\Painel\Despesas;

class BuildMenuListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(BuildingMenu $event)
    {
        $DespesasCount = Despesas::count();
        $title = "Despesas WPrime";

            $event->menu->add('WPrime');
            $event->menu->add([
                'text'        => 'Despesas WPrime',
                'url'         => route('despesas.index', compact($title)),
                'icon'        => 'users',
                'label'       => $DespesasCount,
                'label_color' => 'success'
            ]);
    }
}
