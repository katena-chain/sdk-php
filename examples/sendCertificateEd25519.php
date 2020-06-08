<?php

/**
 * Copyright (c) 2018, TransChain.
 *
 * This source code is licensed under the Apache 2.0 license found in the
 * LICENSE file in the root directory of this source tree.
 */

require_once 'vendor/autoload.php';

use GuzzleHttp\Exception\GuzzleException;
use KatenaChain\Client\Entity\TxSigner;
use KatenaChain\Client\Exceptions\ApiException;
use KatenaChain\Client\Transactor;
use KatenaChain\Client\Utils\Common;
use KatenaChain\Client\Utils\Crypto;
use KatenaChain\Examples\Common\Log;
use KatenaChain\Examples\Common\Settings;

function main()
{
    // Alice wants to certify an ed25519 signature of an off-chain data

    // Load default configuration
    $settings = Settings::defaultSettings();

    // Common Katena network information
    $apiUrl = $settings->apiUrl;
    $chainID = $settings->chainId;

    // Alice Katena network information
    $aliceCompanyBcId = $settings->company->bcId;
    $aliceSignKeyInfo = $settings->company->ed25519Keys->alice;
    $aliceSignPrivateKey = Crypto::createPrivateKeyEd25519FromBase64($aliceSignKeyInfo->privateKeyStr);
    $aliceSignPrivateKeyId = $aliceSignKeyInfo->id;

    // Create a Katena API helper
    $txSigner = new TxSigner(Common::concatFqId($aliceCompanyBcId, $aliceSignPrivateKeyId), $aliceSignPrivateKey);
    $transactor = new Transactor($apiUrl, $chainID, $txSigner);

    try {
        // Off-chain information Alice want to send
        $certificateId = $settings->certificateId;
        $dataSignature = $aliceSignPrivateKey->sign("off_chain_data_to_sign_from_php");

        // Send a version 1 of a certificate ed25519 on Katena
        $txResult = $transactor->sendCertificateEd25519V1Tx(
            $certificateId,
            $aliceSignPrivateKey->getPublicKey(),
            $dataSignature
        );

        Log::println("Result :");
        Log::printlnJson($txResult);

    } catch (ApiException $e) {
        echo $e;
    } catch (GuzzleException|Exception $e) {
        echo $e->getCode() . " " . $e->getMessage();
    }
}

main();
