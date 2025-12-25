<?php

return [
    'required' => 'O campo :attribute é obrigatório',
    'custom' => [
        'name' => [
            'unique' => 'Já existe um registro com esse nome. Informe outro nome, por favor.',
        ],
        'field_name' => [
            'unique' => 'Já existe um campo com esse nome. Informe outro nome, por favor'
        ],
        'email' => [
            'unique' => 'Este e-mail já está cadastrado no nosso sistema, informe outro.'
        ]
    ],
];
