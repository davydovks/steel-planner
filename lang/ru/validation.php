<?php

return [
    'attributes' => [
        'name' => 'имя',
        'email' => 'email',
        'password' => 'пароль',
        'TIN' => 'ИНН',
        'position' => 'позиция',
    ],
    'confirmed' => ':Attribute и подтверждение не совпадают',
    'date' => 'Укажите корректную дату',
    'max' => [
        'numeric_f' => ':Attribute должна иметь значение не более :max.',
        'string' => ':Attribute должен иметь длину не более :max символов',
        'string_f' => ':Attribute должна иметь длину не более :max символов',
        'string_n' => ':Attribute должно иметь длину не более :max символов',
    ],
    'min' => [
        'string' => ':Attribute должен иметь длину не менее :min символов',
    ],
    'numeric' => 'Нужно ввести число',
    'required' => 'Это обязательное поле',
    'string' => 'Поле :attribute должно содержать строку',
    'unique' => 'Такой :attribute уже используется',
    'unique_n' => 'Такое :attribute уже используется',
    'unique_name' => ':Attribute с таким именем уже существует',
];
