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
    // Alice wants to send a nacl box secret to Bob to encrypt an off-chain data

    // Common Katena network information
    $apiUrl = "https://api.test.katena.transchain.io/api/v1";
    $chainId = "katena-chain-test";

    // Alice Katena network information
    $aliceSignPrivateKeyBase64 = "7C67DeoLnhI6jvsp3eMksU2Z6uzj8sqZbpgwZqfIyuCZbfoPcitCiCsSp2EzCfkY52Mx58xDOyQLb1OhC7cL5A==";
    $aliceCompanyChainId = "abcdef";
    $aliceSignPrivateKey = Crypto::createPrivateKeyEd25519FromBase64($aliceSignPrivateKeyBase64);

    // Nacl box information
    $aliceCryptPrivateKeyBase64 = "nyCzhimWnTQifh6ucXLuJwOz3RgiBpo33LcX1NjMAsP1ZkQcdlDq64lTwxaDx0lq6LCQAUeYywyMUtfsvTUEeQ==";
    $aliceCryptPrivateKey = Crypto::createPrivateKeyX25519FromBase64($aliceCryptPrivateKeyBase64);
    $bobCryptPublicKeyBase64 = "KiT9KIwaHOMELcqtPMsMVJLE5Hc9P60DZDrBGQcKlk8=";
    $bobCryptPublicKey = Crypto::createPublicKeyX25519FromBase64($bobCryptPublicKeyBase64);

    // Create a Katena API helper
    $transactor = new Transactor($apiUrl, $chainId, $aliceCompanyChainId, $aliceSignPrivateKey);

    // Off-chain information Alice wants to send
    $secretUuid = "2075c941-6876-405b-87d5-13791c0dc53a";
    $content = "off_chain_secret_to_crypt_from_php";

    try {
        // Alice will use its private key and Bob's public key to encrypt a message
        $encryptedInfo = $aliceCryptPrivateKey->seal($content, $bobCryptPublicKey);

        // Send a version 1 of a secret nacl box on Katena
        $txStatus = $transactor->sendSecretNaclBoxV1($secretUuid, $aliceCryptPrivateKey->getPublicKey(),
            $encryptedInfo['nonce'], $encryptedInfo['encryptedMessage']);

        echo "Transaction status" . PHP_EOL;
        echo sprintf("  Code    : %d" . PHP_EOL, $txStatus->getCode());
        echo sprintf("  Message : %s" . PHP_EOL, $txStatus->getMessage());

    } catch (ApiException $e) {
        echo $e;
    } catch (SodiumException|GuzzleException|Exception $e) {
        echo $e->getCode() . " " . $e->getMessage();
    }
}

main();
