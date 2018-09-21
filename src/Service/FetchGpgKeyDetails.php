<?php

/*
 * Copyright (c) Andreas Heigl<andreas@heigl.org
 * 
 * Licensed under the MIT License. See LICENSE.md file in the project root
 * for full license information.
 */

namespace Org_Heigl\KeyPrinter\Service;

use DateTimeImmutable;
use Exception;
use gnupg;
use UnexpectedValueException;

class FetchGpgKeyDetails
{
    public function __invoke($server, $keyid)
    {
        $importCommand = 'gpg2 -vvvv --keyserver-options=debug --batch --yes --always-trust --keyserver %1$s --recv-keys %2$s 2>&1';
        exec(sprintf(
            $importCommand,
            escapeshellarg($server),
            escapeshellarg($keyid)
        ), $result, $return);

        if ($return !== 0) {
            error_log($return);
            error_log(print_r($result, true));
            throw new UnexpectedValueException(implode ("\n", $result));
        }

        $gpg = new gnupg();
        $key = $gpg->keyinfo($keyid);
        $infos = [
            'name' => $key[0]['uids'][0]['name'],
            'email' => $key[0]['uids'][0]['email'],
            'keysize' => '',
            'keytype' => '',
            'uid' => '',
            'fingerprint' => '',
            'expires' => null,
        ];

        if (! isset($key[0]['subkeys'])) {
            error_log(print_r($key, true));
            throw new UnexpectedValueException('No Subkeys available');
        }
        foreach ($key[0]['subkeys'] as $item) {
            if ($item['keyid'] !== $keyid) {
                continue;
            }

            $infos['uid'] = $item['keyid'];
            $infos['fingerprint'] = $item['fingerprint'];
            if ($item['expires']) {
                $infos['expires'] = new DateTimeImmutable('@' . $item['expires']);
            }
        }

        $removeCommand = 'gpg2 --batch --yes --delete-key %1$s';
        exec(sprintf(
            $removeCommand,
            escapeshellarg($keyid)
        ), $result, $return);
        if ($return !== 0) {
            throw new UnexpectedValueException(implode ("\n", $result));
        }

        return $infos;
    }
}
