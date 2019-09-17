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
use KatenaChain\Client\Utils\Crypto;

function main()
{
    // Alice wants to certify raw off-chain information

    // Common Katena network information
    $apiUrl = "https://api.test.katena.transchain.io/api/v1";
    $chainId = "katena-chain-test";

    // Alice Katena network information
    $aliceSignPrivateKeyBase64 = "7C67DeoLnhI6jvsp3eMksU2Z6uzj8sqZbpgwZqfIyuCZbfoPcitCiCsSp2EzCfkY52Mx58xDOyQLb1OhC7cL5A==";
    $aliceCompanyChainId = "abcdef";
    $aliceSignPrivateKey = Crypto::createPrivateKeyEd25519FromBase64($aliceSignPrivateKeyBase64);

    // Create a Katena API helper
    $transactor = new Transactor($apiUrl, $chainId, $aliceCompanyChainId, $aliceSignPrivateKey);

    // Off-chain information Alice wants to send
    $certificateUuid = "2075c941-6876-405b-87d5-13791c0dc53a";
    $dataRawSignature = "off_chain_data_raw_signature_from_php";

    try {

        // Send a version 1 of a certificate raw on Katena
        $txStatus = $transactor->sendCertificateRawV1($certificateUuid, $dataRawSignature);

        echo "Transaction status" . PHP_EOL;
        echo sprintf("  Code    : %d" . PHP_EOL, $txStatus->getCode());
        echo sprintf("  Message : %s" . PHP_EOL, $txStatus->getMessage());

    } catch (ApiException $e) {
        echo $e;
    } catch (GuzzleException|Exception $e) {
        echo $e->getCode() . " " . $e->getMessage();
    }
}

main();
