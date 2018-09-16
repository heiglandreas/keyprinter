<?php

/*
 * Copyright (c) Andreas Heigl<andreas@heigl.org
 * 
 * Licensed under the MIT License. See LICENSE.md file in the project root
 * for full license information.
 */

namespace Org_Heigl\KeyPrinter\Filter;

use Twig_Environment;
use Twig_Filter;

class ChunkSplit
{
    const NAME = 'chunksplit';

    public function __construct(Twig_Environment $environment)
    {
        $environment->addFilter(new Twig_Filter(
            self::NAME,
            [$this, '__invoke']
        ));
    }

    public function __invoke(string $string, $chunksize = 76, $chunkseparator = "\r\n") : string
    {
        return chunk_split($string,$chunksize, $chunkseparator);
    }
}
