<?php

/**
 * Copyright (c) 2018, TransChain.
 *
 * This source code is licensed under the Apache 2.0 license found in the
 * LICENSE file in the root directory of this source tree.
 */

require_once 'vendor/autoload.php';

use GuzzleHttp\Exception\GuzzleException;
use KatenaChain\Client\Entity\Certify\SecretNaclBoxV1;
use KatenaChain\Client\Exceptions\ApiException;
use KatenaChain\Client\Transactor;
use KatenaChain\Client\Utils\Crypto;
use KatenaChain\Examples\Common\Log;
use KatenaChain\Examples\Common\Settings;

function main()
{
    // Bob wants to read a nacl box secret from Alice to decrypt an off-chain data

    // Load default configuration
    $settings = Settings::defaultSettings();

    // Common Katena network information
    $apiUrl = $settings->apiUrl;

    // Alice Katena network information
    $aliceCompanyBcId = $settings->company->bcId;

    // Create a Katena API helper
    $transactor = new Transactor($apiUrl);

    // Nacl box information
    $bobCryptKeyInfo = $settings->offChain->x25519Keys->bob;
    $bobCryptPrivateKey = Crypto::createPrivateKeyX25519FromBase64($bobCryptKeyInfo->privateKeyStr);

    // Secret id Bob wants to retrieve
    $secretId = $settings->secretId;

    try {

        // Retrieve txs related to the secret fqid
        $txResults = $transactor->retrieveSecretTxs($aliceCompanyBcId, $secretId, 1, $settings->txPerPage);

        Log::println("Tx list :");
        Log::printlnJson($txResults);

        // Retrieve the last tx related to the secret fqid
        $txResult = $transactor->retrieveLastSecretTx($aliceCompanyBcId, $secretId);

        Log::println("Last Tx :");
        Log::printlnJson($txResult);

        /**
         * @var SecretNaclBoxV1 $txData
         */
        $txData = $txResult->getTx()->getData();
        // Bob will use its private key and the sender's public key (needs to be Alice's) to decrypt a message
        $decryptedContent = $bobCryptPrivateKey->open(
            $txData->getContent()->getData(),
            $txData->getSender(),
            $txData->getNonce()->getData()
        );

        if ($decryptedContent === "") {
            $decryptedContent = "Unable to decrypt";
        }
        Log::println(sprintf("Decrypted content for last Tx : %s", $decryptedContent));

    } catch (ApiException $e) {
        echo $e;
    } catch (GuzzleException|Exception $e) {
        echo $e->getCode() . " " . $e->getMessage();
    }
}

main();
