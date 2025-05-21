<?php

namespace Totocsa\Icseusd\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Totocsa\TailwindcssHelper\TailwindcssHelper;

class IcseusdController extends Controller
{
    public $dbDriver;
    public $ilikeORLike;

    public $vuePageDir = '';
    public $configName = '';
    public $modelClassName = '';
    public $keyName;

    public $routePrefix = '';
    public $routeController;
    public $routeParameterName;

    public $paging = [
        'page' => 1,
        'per_page' => 10,
        'per_pages' => [10, 25, 50, 100],
    ];

    public $filters = [];
    public $sort = [];

    public $orders = [
        'index' => [
            'filters' => [],
            'sorts' => [],
            'item' => [
                'fields' => [],
            ],
            'itemButtons' => ['show', 'edit', 'destroy'],
        ],
        'form' => [
            'item' => [
                'fields' => [],
            ],
        ],
        'show' => [
            'item' => [
                'fields' => [],
            ],
        ],
    ];

    public $doNotSaveOnUpdate = [];

    protected $vueComponents = [];

    public $itemButtons = [
        'destroy' => [
            'url' => [
                'type' => 'route',
                'route' => '{{props.routePrefix}}{{props.routeController}}.destroy',
                'routeParams' => [
                    '{{props.routeParameterName}}' => '{{item.id}}'
                ],
            ],
            'label' => ['category' => '', 'subtitle' => ''],
            'icon' => [
                'name' => 'TrashIcon',
                'type' => 'outline',
            ],
            'showIcon' => true,
            'showText' => false,
            'cssClass' => TailwindcssHelper::IcseusdItemButtonsDestroy,
        ],
        'edit' => [
            'url' => [
                'type' => 'route',
                'route' => '{{props.routePrefix}}{{props.routeController}}.edit',
                'routeParams' => [
                    '{{props.routeParameterName}}' => '{{item.id}}'
                ],
            ],
            'label' => ['category' => '', 'subtitle' => ''],
            'icon' => [
                'name' => 'PencilIcon',
                'type' => 'outline',
            ],
            'showIcon' => true,
            'showText' => false,
            'cssClass' => TailwindcssHelper::IcseusdItemButtonsEdit,
        ],
        'show' => [
            'url' => [
                'type' => 'route',
                'route' => '{{props.routePrefix}}{{props.routeController}}.show',
                'routeParams' => [
                    '{{props.routeParameterName}}' => '{{item.id}}'
                ],
            ],
            'label' => ['category' => '', 'subtitle' => ''],
            'icon' => [
                'name' => 'EyeIcon',
                'type' => 'outline',
            ],
            'showIcon' => true,
            'showText' => false,
            'cssClass' => TailwindcssHelper::IcseusdItemButtonsShow,
        ],
    ];

    public function __construct($vueComponentsPrefix = '', $routeControllerPrefix = '')
    {
        if (method_exists(parent::class, '__construct')) {
            parent::__construct();
        }

        $this->dbDriver = DB::getDriverName();
        $this->ilikeORLike = $this->dbDriver === 'pgsql' ? 'ilike' : 'like';

        $this->keyName = (new $this->modelClassName())->getKeyName();
        $this->setRouteController($routeControllerPrefix);
        $this->setRouteParameterName();
        $this->setVueComponents($vueComponentsPrefix);
    }

    public function index(Request $request)
    {
        if (is_null($request)) {
            $request = new Request();
        }

        foreach ($this->filters as $k => $v) {
            $this->filters[$k] = request()->input("filters.{$k}", '') ?? '';
        }

        $this->sort['field'] = $request->input('sort.field', $this->sort['field']);
        $this->sort['direction'] = $request->input('sort.direction', $this->sort['direction']);
        $this->paging['page'] = $request->input('paging.page', 1);
        $this->paging['per_page'] = $request->input('paging.per_page', 10);

        $indexItems = $this->indexQuery();

        if (!$indexItems->hasMorePages()) {
            $this->paging['page'] = $indexItems->lastPage();
            $indexItems = $this->indexQuery();
        }

        $params = array_merge($this->commonParams(), [
            'modelClassName' => $this->modelClassName,
            'items' => $indexItems,
            'filters' => $this->filters,
            'orders' => $this->orders['index'],
            'sort' => $this->sort,
            'fields' => $this->fields(),
            'per_pages' => $this->paging['per_pages'],
            'itemButtons' => $this->itemButtons,
            'routes' => $this->getRoutes(),
            'editableResults' => [],
            'additionalData' => $this->additionalIndexData(),
        ]);

        $params['paramNames']['index'] = array_merge(array_keys($params), ['paramNames']);

        return Inertia::render($this->vueComponents['index'],  $params);
    }

    public function create()
    {
        $modelClassName = $this->modelClassName;

        $item = [$this->keyName => null];
        foreach ($this->orders['form']['item']['fields'] as $v) {
            $tableField = explode('-', $v);
            $item[$v] = null;
        }

        $orders = $this->orders['form'];
        $fields = $this->fields();
        $additionalData = $this->additionalCreateData();

        $params = array_merge($this->commonParams(), compact('modelClassName', 'item', 'orders', 'fields', 'additionalData'));
        return Inertia::render($this->vueComponents['create'], $params);
    }

    public function store(Request $request)
    {
        $item = $request->all();
        $modelClassName = $this->modelClassName;

        $attributes = $this->setFormAttributes($item);
        $this->storeFillFixValues($attributes);
        [$errors, $allValid] = $this->formValidation($attributes);

        $id = $allValid ? $this->saveItem($attributes) : null;

        $orders = $this->orders['form'];
        $fields = $this->fields();
        $additionalData = $this->additionalCreateData();

        $params = array_merge($this->commonParams(), compact('modelClassName', 'item', 'orders', 'fields', 'id', 'additionalData', 'errors'));
        return Inertia::render($this->vueComponents['create'], $params);
    }

    public function edit($id)
    {
        $modelClassName = $this->modelClassName;
        $item = $this->loadItem($id);

        $orders = $this->orders['form'];
        $fields = $this->fields();
        $additionalData = $this->additionalEditData();

        $params = array_merge($this->commonParams(), compact('modelClassName', 'item', 'orders', 'fields', 'additionalData'));
        return Inertia::render($this->vueComponents['edit'], $params);
    }

    public function update(Request $request, $id)
    {
        $data = $request->all();
        $modelClassName = $this->modelClassName;
        $item = $this->loadItem($id);

        $attributes = $this->setFormAttributes($item, 'update', $data);
        [$errors, $allValid] = $this->formValidation($attributes);

        $id = $allValid ? $this->saveItem($attributes, 'update') : null;

        $orders = $this->orders['form'];
        $fields = $this->fields();
        $additionalData = $this->additionalCreateData();

        $params = array_merge($this->commonParams(), compact('modelClassName', 'item', 'orders', 'fields', 'additionalData', 'errors'));
        return Inertia::render($this->vueComponents['edit'], $params);
    }

    public function show($id)
    {
        $item = $this->loadItem($id);

        $modelClassName = $this->modelClassName;
        $orders = $this->orders['show'];
        $fields = $this->fields();
        $additionalData = $this->additionalShowData();

        $params = array_merge($this->commonParams(), compact('modelClassName', 'item', 'orders', 'fields', 'additionalData'));
        return Inertia::render($this->vueComponents['show'], $params);
    }

    public function destroy(Request $request, $id)
    {
        $modelClassName = $this->modelClassName;
        //$modelClassName::findOrFail($id)->delete();
        $modelClassName::query()->where('id', $id)->delete();

        return redirect()->action(
            [$this::class, 'index'],
            $request->all()
        );
    }

    public function saveEditable(Request $request)
    {
        $data = $request->all();
        $validator = $this->validateEditable($data);

        if ($validator->passes()) {
            $editableResults = [];
            $tableField = explode('-', $data['editable']['field']);

            $modelClassNameArray = explode('\\', $this->modelClassName);
            array_pop($modelClassNameArray);
            array_push($modelClassNameArray, Str::singular(ucfirst($tableField[0])));
            $modelClassName = implode('\\', $modelClassNameArray);

            $keyName = (new $modelClassName())->getKeyName();

            $attributes = [$tableField[1] => $data['editable']['value']];

            $model = $modelClassName::findOrFail($data['editable']['id']);

            $rules[$tableField[1]] = $modelClassName::rules($model->getAttributes())[$tableField[1]];
            $validator = Validator::make($attributes, $rules);
            $errors = $validator->messages('translatable');

            if ($validator->passes('translatable')) {
                $model->update($attributes);
                $editableResults[$model->{$keyName}][$data['editable']['field']] = [
                    'value' => $data['editable']['value'],
                    'hasError' => false,
                    'errors' => [],
                ];
            } else {
                /*if (isset($errors[$tableField[1]])) {
                    $errors[$data['editable']['field']] = $errors[$tableField[1]];
                    unset($errors[$tableField[1]]);
                }*/

                $errors[$data['editable']['field']][0]['invalidValue'] = $data['editable']['value'];

                $editableResults[$model->{$keyName}] = [
                    $data['editable']['field'] => [
                        'value' => $data['editable']['value'],
                        'hasError' => true,
                        'errors' => $errors[$tableField[1]],
                    ]
                ];
            }

            return Inertia::render($this->vueComponents['index'],  compact('editableResults'));
        } else {
            $routeArray = explode('.', Route::currentRouteName());
            $routeArray[1] = 'index';
            return redirect()->route(implode('.', $routeArray));
        }
    }

    public function validateEditable(&$data)
    {
        $isEditableValue = key_exists('editable', $data) && key_exists('value', $data['editable']);
        if ($isEditableValue && is_null($data['editable']['value'])) {
            $data['editable']['value'] = '';
        }

        $rules = [
            'editable.id' => ['required', 'integer'],
            'editable.field' => ['required', 'string'],
            'editable.value' => $isEditableValue ? ['string'] : ['required', 'string'],
        ];

        return Validator::make($data, $rules);
    }

    public function commonParams()
    {
        return [
            'configName' => $this->configName,
            'routePrefix' => $this->routePrefix,
            'routeController' => $this->routeController,
            'routeParameterName' => $this->routeParameterName,
        ];
    }

    public function indexQuery(): LengthAwarePaginator
    {
        return new \Illuminate\Pagination\LengthAwarePaginator([], 0, 0);
    }

    public function replaceFieldToValue()
    {
        $replace = array_combine(
            array_map(fn($key) => "{{{$key}}}", array_keys($this->filters)),
            array_values($this->filters)
        );

        return $replace;
    }

    public function getRoutes()
    {
        $controllerName = $this::class;
        $allowedMethods = ['index', 'create', 'store', 'edit', 'update', 'show', 'destroy'];
        $routes = Route::getRoutes();

        $controllerRoutes = collect($routes)->filter(function ($route) use ($controllerName, $allowedMethods) {
            // Lekérdezzük, hogy a route a keresett controllerhez tartozik-e
            // és hogy az action metódus szerepel-e a kívánt metódusok között
            return strpos($route->getActionName(), $controllerName) !== false &&
                in_array($route->getActionMethod(), $allowedMethods);
        })->mapWithKeys(function ($route) {
            // A route URI-ja és az action neve (metódus) lesz a tömb kulcs-érték párja
            $actionName = $route->getActionMethod(); // Az action neve
            return [$actionName => $route->uri()]; // Kulcs=actionName, érték=route URI
        })->toArray();

        return $controllerRoutes;
    }

    public function conditions()
    {
        return   [];
    }

    public function fields()
    {
        return [
            'filter' => [],
            'item' => [],
            'form' => [],
            'show' => [],
        ];
    }

    public function fixValues(): array
    {
        return [];
    }

    public function setRouteController($prefix)
    {
        if (is_null($this->routeController)) {
            $name = class_basename($this::class);
            $name = strtolower($name);
            $name = str_replace('controller', '', $name);

            $this->routeController = $prefix . Str::plural($name);
        }
    }

    public function setRouteParameterName()
    {
        if (is_null($this->routeParameterName)) {
            $name = class_basename($this::class);
            $name = strtolower($name);
            $this->routeParameterName = Str::singular(str_replace('controller', '', $name));
        }
    }

    public function setVueComponents($prefix = '')
    {
        $name = last(explode('\\', $this::class));
        $name = substr($name, 0, strlen($name) - strlen('Controller'));
        $prefix .= Str::plural($name);

        $routes = $this->getRoutes();

        $this->vueComponents = [];
        foreach ($routes as $k => $v) {
            if ($this->vuePageDir > '') {
                $vuePageDir = trim($this->vuePageDir, "/\\");
                $this->vueComponents[$k] = "../../../{$vuePageDir}/$prefix/" . ucfirst(strtolower($k));
            } else {
                $this->vueComponents[$k] = "$prefix/" . ucfirst(strtolower($k));
            }
        }
    }

    public function getModelNameByTableName($tableName)
    {
        $mClassNameArray = explode('\\', $this->modelClassName);
        array_pop($mClassNameArray);
        array_push($mClassNameArray, Str::singular(ucfirst($tableName)));
        $mClassName = implode('\\', $mClassNameArray);

        return $mClassName;
    }

    public function loadItem($id)
    {
        $order = $this->orders['show']['item']['fields'];
        $modelClassNameArray = explode('\\', $this->modelClassName);
        $tables = [Str::plural(strtolower(last($modelClassNameArray)))];
        $select = ["$tables[0].id"];

        foreach ($order as $v) {
            $tableField = explode('-', $v);

            $select[] = "$tableField[0].$tableField[1] as $v";

            if (array_search($v[0], $tables)) {
                $tables[] = $v[0];
            }
        }

        $item = $this->modelClassName::query()
            ->select($select)
            ->where("$tables[0].id", '=', $id)
            ->first()->toArray();

        return $item;
    }

    public function setFormAttributes($item, $operation = 'create', $data = [])
    {
        $attributes = [];
        foreach ($this->orders['form']['item']['fields'] as $v) {
            $tableField = explode('-', $v);
            $className = $this->getModelNameByTableName($tableField[0]);

            if ($operation === 'update' && $className === $this->modelClassName) {
                $attributes[$tableField[0]]['attrs']['id'] = $item[$this->keyName];
            }

            $attributes[$tableField[0]]['className'] = $className;
            $attributes[$tableField[0]]['attrs'][$tableField[1]] = key_exists($v, $data) ? $data[$v] : $item[$v];
        }

        return $attributes;
    }

    public function storeFillFixValues(&$attributes)
    {
        foreach ($this->fixValues() as $k => $v) {
            $tableField = explode('-', $k);
            $attributes[$tableField[0]]['attrs'][$tableField[1]] = $v;
        }
    }

    public function formValidation(&$attributes)
    {
        $errors = [];
        $allValid = true;
        foreach ($attributes as $k1 => $v1) {
            $rules = $v1['className']::rules($attributes[$k1]['attrs']);
            foreach ($rules as $k2 => $v2) {
                if (array_search("$k1-$k2", $this->doNotSaveOnUpdate) !== false) {
                    unset($rules[$k2]);
                }

                if (key_exists($k2, $v1['attrs']) && is_null($v1['attrs'][$k2]) && array_search('string', $v2) !== false) {
                    $attributes[$k1]['attrs'][$k2] = (string) $attributes[$k1]['attrs'][$k2];
                }
            }

            $validator = Validator::make($attributes[$k1]['attrs'], $rules);

            $allValid = $allValid && $validator->passes('translatable');

            $err = $validator->messages('translatable');
            foreach ($err as $k2 => $v2) {
                $errors["$k1-$k2"] = $v2;
            }
        }

        return [$errors, $allValid];
    }

    public function saveItem($attributes, $operation = 'create')
    {
        $models = [];
        foreach ($attributes as $k => $v) {
            if ($operation === 'create') {
                $models[$k] = new $v['className']($v['attrs']);
                $models[$k]->save();
            } else if ($operation === 'update') {
                $s = DB::table($k)
                    ->where($this->keyName, $v['attrs'][$this->keyName])
                    ->update($v['attrs']);
            }

            if ($v['className'] = $this->modelClassName) {
                $id = $operation === 'create' ? $models[$k]->id : $v['attrs'][$this->keyName];
            }
        }

        return $id;
    }

    public function additionalIndexData()
    {
        return [];
    }

    public function additionalCreateData()
    {
        return [];
    }

    public function additionalEditData()
    {
        return [];
    }

    public function additionalShowData()
    {
        return [];
    }
}
