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
use KatenaChain\Client\Utils\Common;
use KatenaChain\Examples\Common\Log;
use KatenaChain\Examples\Common\Settings;

function main()
{
    // Alice wants to retrieve txs related to a certificate

    // Load default configuration
    $settings = Settings::defaultSettings();

    // Common Katena network information
    $apiUrl = $settings->apiUrl;

    // Alice Katena network information
    $aliceCompanyBcId = $settings->company->bcId;

    // Create a Katena API helper
    $transactor = new Transactor($apiUrl);

    // Certificate id Alice wants to retrieve
    $certificateId = $settings->certificateId;


    try {

        // Retrieve txs related to the certificate fqid
        $txResults = $transactor->retrieveCertificateTxs($aliceCompanyBcId, $certificateId, 1, Common::DEFAULT_PER_PAGE_PARAM);

        Log::println("Tx list :");
        Log::printlnJson($txResults);

        // Retrieve the last tx related to the certificate fqid
        $txResult = $transactor->retrieveLastCertificateTx($aliceCompanyBcId, $certificateId);

        Log::println("Last Tx :");
        Log::printlnJson($txResult);

    } catch (ApiException $e) {
        echo $e;
    } catch (GuzzleException|Exception $e) {
        echo $e->getCode() . " " . $e->getMessage();
    }
}

main();
