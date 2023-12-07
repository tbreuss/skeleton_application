<?php

namespace aint\system;

function defined_classes(): array
{
    $classes = array_filter(
        get_declared_classes(),
        fn($class) => str_starts_with($class, 'app\\') || str_starts_with($class, 'aint\\')
    );
    sort($classes);

    return $classes;
}

function defined_constants(): array
{
    $constants = [];
    foreach (get_defined_constants() as $key => $value) {
        if (str_starts_with($key, 'app\\') || str_starts_with($key, 'aint\\')) {
            if (is_array($value)) {
                $value = json_encode($value);
            }
            $constants[$key] = $value;
        }
    }
    ksort($constants);
    return $constants;
}

function defined_functions(): array
{
    $functions = array_filter(
        get_defined_functions()['user'],
        fn($class) => str_starts_with($class, 'app\\') || str_starts_with($class, 'aint\\')
    );
    sort($functions);

    return $functions;
}
