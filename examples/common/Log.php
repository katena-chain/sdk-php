<?php

/**
 * Copyright (c) 2018, TransChain.
 *
 * This source code is licensed under the Apache 2.0 license found in the
 * LICENSE file in the root directory of this source tree.
 */

namespace KatenaChain\Examples\Common;

use KatenaChain\Client\Serializer\Serializer;
use Ergebnis\Json\Printer\Printer;

class Log
{

    public static function printlnJson($data): void
    {
        $serializer = new Serializer();
        $encodedData = $serializer->serialize($data);

        $indent = '  ';
        $printer = new Printer();
        $printed = $printer->print($encodedData, $indent);

        self::println(sprintf("%s%s", $printed, PHP_EOL));
    }

    public static function println($data): void
    {
        echo sprintf("%s%s", $data, PHP_EOL);
    }

}
