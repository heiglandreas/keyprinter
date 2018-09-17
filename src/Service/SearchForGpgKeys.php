<?php

/*
 * Copyright (c) Andreas Heigl<andreas@heigl.org
 * 
 * Licensed under the MIT License. See LICENSE.md file in the project root
 * for full license information.
 */

namespace Org_Heigl\KeyPrinter\Service;

use DateTimeImmutable;
use DateTimeZone;
use Exception;
use gnupg;
use UnexpectedValueException;

class SearchForGpgKeys
{
    public function __invoke($server, $keyid)
    {
        $importCommand = 'gpg2 -vvvv --keyid-format LONG --batch --yes --always-trust --keyserver %1$s --search-keys %2$s 2>&1';
        exec(sprintf(
            $importCommand,
            escapeshellarg($server),
            escapeshellarg($keyid)
        ), $result, $return);

        return $this->parseSearchOutput($result);
    }

    private function parseSearchOutput(array $output) : array
    {
        $results = [];
        $current = [];
        foreach ($output as $line) {
            if (preg_match('/^\(\d+\)/', $line)) {
                $current = [];
            }

            if (preg_match('/(\w[^<]+<[^@]+@[^>]+>)/', $line, $result)) {
                $current['users'][] = preg_replace('/^\d+\)\s+/', '', $result[1]);
            }
            if (preg_match('/(\d{4}) bit (\w+) key ([A-F0-9a-f]+), created: ([0-9\-]+)(?:, expires: ([0-9\-]+))?/', $line, $result)) {
                $current['size'] = $result[1];
                $current['type'] = $result[2];
                $current['keyid'] = $result[3];
                $current['created'] = new DateTimeImmutable($result[4] . ' 12:00:00', new DateTimeZone('UTC'));
                if (isset($result[5])) {
                    $current['expires'] = new DateTimeImmutable($result[5] . ' 12:00:00', new DateTimeZone('UTC'));
                }
                $results[] = $current;
            }
        }



        return $results;
    }
}
