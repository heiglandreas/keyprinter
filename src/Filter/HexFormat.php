<?php

/*
 * Copyright (c) Andreas Heigl<andreas@heigl.org
 * 
 * Licensed under the MIT License. See LICENSE.md file in the project root
 * for full license information.
 */

namespace Org_Heigl\KeyPrinter\Filter;

use function trim;
use function explode;
use function chunk_split;
use function implode;

class HexFormat
{
    const NAME = 'hexformat';

    public function __invoke(string $string) : string
    {
        $lines = [];
        foreach (explode("\n", $string) as $line) {
            $lines[] = trim(chunk_split(trim($line),4, ' '));
        }

        return implode("\r\n", $lines);
    }
}
