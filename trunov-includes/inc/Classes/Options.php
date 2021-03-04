<?php

namespace rybkinevg\trunov;

class Options
{
    public static function set_option(string $name, mixed $var)
    {
        add_option($name, $var);
    }

    public static function get_option(string $name)
    {
        return get_option($name);
    }

    public static function update_option(string $name, mixed $var)
    {
        update_option($name, $var);
    }
}
