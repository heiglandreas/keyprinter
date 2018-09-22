<?php

/*
 * Copyright (c) Andreas Heigl<andreas@heigl.org
 * 
 * Licensed under the MIT License. See LICENSE.md file in the project root
 * for full license information.
 */

namespace Org_Heigl\KeyPrinterTest\Filter;

use Org_Heigl\KeyPrinter\Filter\ChunkSplit;
use PHPUnit\Framework\TestCase;

class ChunkSplitTest extends TestCase
{
    /**
     * @param string $input
     * @param string $expectedOutput
     * @param int    $chunksize
     * @param string $separator
     * @dataProvider invokationProvider
     * @covers \Org_Heigl\KeyPrinter\Filter\ChunkSplit::__invoke
     */
    public function testInvokation(string $input, string $expectedOutput, int $chunksize, string $separator)
    {
        self::assertSame($expectedOutput, (new ChunkSplit())($input, $chunksize, $separator));
    }

    public function invokationProvider() : array
    {
        return [
            ['test', 'test', 4, "-"],
            ['test', 'tes-t', 3, "-"],
            ['test', 'te-st', 2, "-"],
        ];
    }
}
