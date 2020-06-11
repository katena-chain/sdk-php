<?php

/**
 * Copyright (c) 2018, TransChain.
 *
 * This source code is licensed under the Apache 2.0 license found in the
 * LICENSE file in the root directory of this source tree.
 */

require_once 'vendor/autoload.php';

use GuzzleHttp\Exception\GuzzleException;
use KatenaChain\Client\Exceptions\ApiException;
use KatenaChain\Client\Transactor;
use KatenaChain\Examples\Common\Log;
use KatenaChain\Examples\Common\Settings;

function main()
{
    // Alice wants to retrieve a key and its related txs

    // Load default configuration
    $settings = Settings::defaultSettings();

    // Common Katena network information
    $apiUrl = $settings->apiUrl;

    // Alice Katena network information
    $aliceCompanyBcId = $settings->company->bcId;

    // Create a Katena API helper
    $transactor = new Transactor($apiUrl);

    // Key id Alice wants to retrieve
    $keyId = $settings->keyId;

    try {

        // Retrieve txs related to the key fqid
        $txResults = $transactor->retrieveKeyTxs($aliceCompanyBcId, $keyId, 1, $settings->txPerPage);

        Log::println("Tx list :");
        Log::printlnJson($txResults);

        // Retrieve the last tx related to the key fqid
        $txResult = $transactor->retrieveLastKeyTx($aliceCompanyBcId, $keyId);

        Log::println("Last Tx :");
        Log::printlnJson($txResult);

        // Retrieve the last state of a key with that fqid
        $key = $transactor->retrieveKey($aliceCompanyBcId, $keyId);

        Log::println("Key :");
        Log::printlnJson($key);

    } catch (ApiException $e) {
        echo $e;
    } catch (GuzzleException|Exception $e) {
        echo $e->getCode() . " " . $e->getMessage();
    }
}

main();
