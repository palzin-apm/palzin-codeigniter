<?php

use Palzin\CodeIgniter\Palzin;

if (! function_exists('palzin')) {
    /**
     * Provides a convenience interface to the Palzin service.
     *
     * @param mixed|null $value
     *
     * @return array|bool|float|int|object|Settings|string|void|null
     */
    function palzin(?callable $func = null, string $type = '', string $label = '')
    {
        /** @var Palzin $palzin */
        $palzin = service('palzin');

        if (empty($func) || count(func_get_args()) < 2) {
            return $palzin;
        }

        if (count(func_get_args()) >= 2) {
            return $palzin->addSegment($func, $type, $label);
        }
    }
}
