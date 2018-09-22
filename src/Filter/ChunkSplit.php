<?php

/*
 * Copyright (c) Andreas Heigl<andreas@heigl.org
 * 
 * Licensed under the MIT License. See LICENSE.md file in the project root
 * for full license information.
 */

namespace Org_Heigl\KeyPrinter\Filter;

use function trim;
use function chunk_split;

class ChunkSplit
{
    const NAME = 'chunksplit';

    public function __invoke(string $string, $chunksize = 76, $chunkseparator = "\r\n") : string
    {
        return trim(chunk_split($string,$chunksize, $chunkseparator), $chunkseparator);
    }
}
