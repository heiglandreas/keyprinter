<?php

/*
 * Copyright (c) Andreas Heigl<andreas@heigl.org
 * 
 * Licensed under the MIT License. See LICENSE.md file in the project root
 * for full license information.
 */

namespace Org_Heigl\KeyPrinterTest\Filter;

use Org_Heigl\KeyPrinter\Filter\ChunkSplit;
use Org_Heigl\KeyPrinter\Filter\HexFormat;
use PHPUnit\Framework\TestCase;

class HexFormatTest extends TestCase
{
    /**
     * @param string $input
     * @param string $expectedOutput
     * @param int    $chunksize
     * @param string $separator
     * @dataProvider invokationProvider
     * @covers \Org_Heigl\KeyPrinter\Filter\ChunkSplit::__invoke
     */
    public function testInvokation(string $input, string $expectedOutput)
    {
        self::assertSame($expectedOutput, (new HexFormat())($input));
    }

    public function invokationProvider() : array
    {
        return [
            ["abcdefghijklm\r\nnopqrstuvwxyz", "abcd efgh ijkl m\r\nnopq rstu vwxy z"],
            ['abcdefgh', 'abcd efgh'],
        ];
    }
}
