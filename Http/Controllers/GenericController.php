<?php

namespace Totocsa\Icseusd\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Pagination\LengthAwarePaginator;
use Totocsa\Icseusd\Http\Controllers\IcseusdController;
use Totocsa\Icseusd\Services\GenericConfigLoader;

class GenericController extends IcseusdController
{
    public $config;
    public $routePrefix = 'icseusd.';

    public function __construct(Request $request)
    {
        $this->configName = $request->configName ?? null;
        if (!is_null($this->configName)) {
            $this->loadConfig();

            if (method_exists(parent::class, '__construct')) {
                parent::__construct();
            }

            $components = $request->components ??      [];
            foreach ($this->vueComponents as $k => $v) {
                $this->vueComponents[$k] = $components[$k] ?? "Icseusd/$v";
            }
        }
    }

    public function indexQuery(): LengthAwarePaginator
    {
        return $this->config['indexQuery']($this);
    }

    public function fields()
    {
        return $this->config['fields']();
    }

    public function loadConfig()
    {
        try {
            $this->config = GenericConfigLoader::get($this->configName); // pl. config/icseusd_generic_configs/User.php vagy csomagból
        } catch (\Exception $e) {
            abort(404, $e->getMessage());
            return null;
        }

        foreach ($this->config as $k => $v) {
            $this->{$k} = $v;
        }
    }
}
