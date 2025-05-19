<?php

namespace Totocsa\Icseusd;

use Illuminate\Support\ServiceProvider;

class IcseusdServiceProvider extends ServiceProvider
{
    public function register()
    {
        // Ha van konfigurációs fájl, azt itt töltheted be
        //$this->mergeConfigFrom(__DIR__.'/../config/destroy-confirm-modal.php', 'destroy-confirm-modal');
    }

    public function boot()
    {
        // Publikálható migrációk
        $groupsBase = 'ice-icseusd';
        $this->publishes([__DIR__ . '/resources' =>  resource_path()], "$groupsBase-resources");
    }
}
