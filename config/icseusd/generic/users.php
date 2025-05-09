<?php

use Illuminate\Pagination\LengthAwarePaginator;
use App\Models\User;

return [
    'modelClassName' => User::class,
    'sort' => [
        'field' => 'users-name',
        'direction' => 'asc',
    ],
    'orders' => [
        'index' => [
            'filters' => ['users-name', 'users-email'],
            'sorts' => [
                'users-name' => ['users-name', 'users-email'],
                'users-email' => ['users-email', 'users-name'],
            ],
            'item' => [
                'fields' => ['users-name', 'users-email'],
            ],
            'itemButtons' => [],
        ],
        'form' => [
            'fields' => ['users-name', 'users-email'],
        ],
        'show' => [
            'fields' => ['users-name', 'users-email'],
        ]
    ],
    'filters' => [
        'users-name' => '',
        'users-email' => '',
    ],
    'conditions' => [
        'users-name' => [
            'operator' => 'ilike',
            'value' => "%{{users-name}}%",
            'boolean' => 'and',
        ],
        'users-email' => [
            'operator' => 'ilike',
            'value' => "%{{users-email}}%",
            'boolean' => 'and',
        ],
    ],
    'fields' => function () {
        return [
            'users-name' => [
                'editableOnIndex' => true,
                'form' => [
                    'tag' => 'input',
                    'attributes' => [
                        'type' => 'text',
                    ],
                ],
                'filter' => [
                    'tag' => 'input',
                    'attributes' => [
                        'type' => 'text',
                    ],
                ],
            ],
            'users-email' => [
                'form' => [
                    'tag' => 'input',
                    'attributes' => [
                        'type' => 'email',
                    ],
                ],
                'filter' => [
                    'tag' => 'input',
                    'attributes' => [
                        'type' => 'text',
                    ],
                ],
            ],
            'current_team_id' => [
                'form' => [
                    'tag' => 'select',
                    'options' => ['additionalData', 'current_team_idValueTexts'],
                    'attributes' => [],
                ],
                'filter' => [
                    'tag' => 'select',
                    'options' => ['additionalData', 'current_team_idValueTexts'],
                    'attributes' => [],
                ],
            ],
        ];
    },
    'indexQuery' => function ($caller): LengthAwarePaginator {
        /* @var $query \App\Models\XModel */
        $t0 = 'users';

        $query = $caller->modelClassName::query()
            ->select([
                "$t0.id",
                "$t0.name as $t0-name",
                "$t0.email as $t0-email",
            ]);

        foreach ($caller->conditions as $k => $v) {
            if ($caller->filters[$k] > 0) {
                $cond = $caller->conditions[$k];
                $value = strtr($cond['value'], $caller->replaceFieldToValue());
                $query->where(str_replace('-', '.', $k), $cond['operator'], $value, $cond['boolean']);
            }
        }

        foreach ($caller->orders['index']['sorts'][$caller->sort['field']] as $v) {
            $query->orderBy($v, $caller->sort['direction']);
        }

        $results = $query->paginate($caller->paging['per_page'], ['*'], null, $caller->paging['page']);

        return $results;
    }
];
