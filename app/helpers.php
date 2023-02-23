<?php

if (!function_exists('on_page')) {
    function on_page($path)
    {
        return request()->is($path);
    }
}

if (!function_exists('return_if')) {
    function return_if($condition, $value)
    {
        if ($condition) {
            return $value;
        }
    }
}


function active_class($path, $active = 'active')
{
    return call_user_func_array('Request::is', (array)$path) ? $active : '';
}

function is_active_route($path)
{
    return call_user_func_array('Request::is', (array)$path) ? 'true' : 'false';
}

function show_class($path)
{
    return call_user_func_array('Request::is', (array)$path) ? 'show' : '';
}

//轉置
function transpose($array_one)
{
    $array_two = [];
    foreach ($array_one as $key => $item) {
        foreach ($item as $subkey => $subitem) {
            $array_two[$subkey][$key] = $subitem;
        }
    }
    return $array_two;
}


function get_sum($array)
{
    $num = 0;
    foreach ($array as $k => $v) {
        if (is_array($v)) {
            $num += get_sum($v);
        }
    }
    return $num + array_sum($array);
}
