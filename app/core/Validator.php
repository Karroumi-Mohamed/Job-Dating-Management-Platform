<?php

namespace App\Core;

class Validator
{
    protected static $errors = [];

    protected static $rules = [
        'required' => 'The :field field is required',
        'email' => 'The :field must be a valid email',
        'min' => 'The :field must be at least :param characters',
        'max' => 'The :field must not exceed :param characters',
        'numeric' => 'The :field must be numeric',
        'match' => 'The :field must match :param',
        'image' => 'The :field must be an image (jpg, jpeg, png)',
        'unique' => 'The :field is already taken'
    ];

    public static function validate($data, $rules)
    {
        self::$errors = [];

        foreach ($rules as $field => $fieldRules) {
            $fieldRules = explode('|', $fieldRules);
            $value = $data[$field] ?? null;

            foreach ($fieldRules as $rule) {
                $params = [];

                if (strpos($rule, ':') !== false) {
                    [$rule, $param] = explode(':', $rule);
                    $params = explode(',', $param);
                }

                $method = 'validate' . ucfirst($rule);
                if (method_exists(self::class, $method)) {
                    self::$method($field, $value, ...$params);
                }
            }
        }

        return empty(self::$errors);
    }

    public static function getErrors()
    {
        return self::$errors;
    }

    public static function getFirstError()
    {
        return reset(self::$errors)[0] ?? '';
    }

    protected static function validateRequired($field, $value)
    {
        if (empty($value) && $value !== '0') {
            self::addError($field, 'required');
        }
    }

    protected static function validateEmail($field, $value)
    {
        if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
            self::addError($field, 'email');
        }
    }

    protected static function validateMin($field, $value, $min)
    {
        if (strlen($value) < $min) {
            self::addError($field, 'min', ['param' => $min]);
        }
    }

    protected static function validateMax($field, $value, $max)
    {
        if (strlen($value) > $max) {
            self::addError($field, 'max', ['param' => $max]);
        }
    }

    protected static function validateImage($field, $value)
    {
        if (!empty($value['tmp_name'])) {
            $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg'];
            if (!in_array($value['type'], $allowedTypes)) {
                self::addError($field, 'image');
            }
        }
    }

    protected static function validateUnique($field, $value, $table, $column, $except = null)
    {
        $query = \Illuminate\Database\Capsule\Manager::table($table)
            ->where($column, $value);

        if ($except) {
            $query->where('id', '!=', $except);
        }

        if ($query->exists()) {
            self::addError($field, 'unique');
        }
    }

    protected static function addError($field, $rule, $params = [])
    {
        $message = self::$rules[$rule];
        $message = str_replace(':field', ucfirst($field), $message);

        foreach ($params as $key => $value) {
            $message = str_replace(':' . $key, $value, $message);
        }

        self::$errors[$field][] = $message;
    }
}