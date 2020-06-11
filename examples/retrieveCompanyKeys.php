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
    // Alice wants to retrieve the keys of its company

    // Load default configuration
    $settings = Settings::defaultSettings();

    // Common Katena network information
    $apiUrl = $settings->apiUrl;

    // Alice Katena network information
    $aliceCompanyBcId = $settings->company->bcId;

    // Create a Katena API helper
    $transactor = new Transactor($apiUrl);

    try {

        // Retrieve the keys from Katena
        $keys = $transactor->retrieveCompanyKeys($aliceCompanyBcId, 1, $settings->txPerPage);

        Log::println("Keys list :");
        Log::printlnJson($keys);

    } catch (ApiException $e) {
        echo $e;
    } catch (GuzzleException|Exception $e) {
        echo $e->getCode() . " " . $e->getMessage();
    }
}

main();
