<?php

/**
 * Copyright (c) 2018, TransChain.
 *
 * This source code is licensed under the Apache 2.0 license found in the
 * LICENSE file in the root directory of this source tree.
 */

require_once 'vendor/autoload.php';

use GuzzleHttp\Exception\GuzzleException;
use KatenaChain\Client\Entity\Certify\Certify;
use KatenaChain\Client\Entity\Certify\CertificateEd25519V1;
use KatenaChain\Client\Entity\Certify\CertificateRawV1;
use KatenaChain\Client\Exceptions\ApiException;
use KatenaChain\Client\Transactor;
use KatenaChain\Client\Utils\Common;

function main()
{
    // Alice wants to retrieve the keys of its company

    // Common Katena network information
    $apiUrl = "https://nodes.test.katena.transchain.io/api/v1";

    // Alice Katena network information
    $aliceCompanyBcid = "abcdef";

    // Create a Katena API helper
    $transactor = new Transactor($apiUrl);

    try {

        // Retrieve the keys from Katena
        $keys = $transactor->retrieveCompanyKeys($aliceCompanyBcid, 1, Common::DEFAULT_PER_PAGE_PARAM);

        foreach ($keys as $index => $key) {
            echo "KeyV1" . PHP_EOL;
            echo sprintf("  Company bcid : %s" . PHP_EOL, $key->getCompanyBcid());
            echo sprintf("  Public key   : %s" . PHP_EOL, base64_encode($key->getPublicKey()->getKey()));
            echo sprintf("  Is active    : %s" . PHP_EOL, $key->getIsActive() ? "true" : "false");
            echo sprintf("  Role         : %s" . PHP_EOL, $key->getRole());
            echo PHP_EOL;
        }

    } catch (ApiException $e) {
        echo $e;
    } catch (GuzzleException|Exception $e) {
        echo $e->getCode() . " " . $e->getMessage();
    }
}

main();
