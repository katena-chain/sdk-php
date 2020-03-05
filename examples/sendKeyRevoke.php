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
    // Alice wants to revoke a key for its company

    // Common Katena network information
    $apiUrl = "https://nodes.test.katena.transchain.io/api/v1";
    $chainID = "katena-chain-test";

    // Alice Katena network information
    $aliceSignPrivateKeyBase64 = "7C67DeoLnhI6jvsp3eMksU2Z6uzj8sqZbpgwZqfIyuCZbfoPcitCiCsSp2EzCfkY52Mx58xDOyQLb1OhC7cL5A==";
    $aliceCompanyChainID = "abcdef";
    $aliceSignPrivateKey = Crypto::createPrivateKeyEd25519FromBase64($aliceSignPrivateKeyBase64);

    // Create a Katena API helper
    $transactor = new Transactor($apiUrl, $chainID, $aliceCompanyChainID, $aliceSignPrivateKey);

    try {
        // Information Alice want to send
        $keyRevokeUuid = "2075c941-6876-405b-87d5-13791c0dc53a";
        $publicKeyBase64 = "gaKih+STp93wMuKmw5tF5NlQvOlrGsahpSmpr/KwOiw=";
        $publicKey = Crypto::createPublicKeyEd25519FromBase64($publicKeyBase64);

        // Send a version 1 of a key revoke on Katena
        $txStatus = $transactor->sendKeyRevokeV1($keyRevokeUuid, $publicKey);

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
