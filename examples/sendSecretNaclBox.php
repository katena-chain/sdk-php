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
    // Alice wants to send a nacl box secret to Bob to encrypt an off-chain data

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

    // Nacl box information
    $aliceCryptKeyInfo = $settings->offChain->x25519Keys->alice;
    $aliceCryptPrivateKey = Crypto::createPrivateKeyX25519FromBase64($aliceCryptKeyInfo->privateKeyStr);
    $bobCryptKeyInfo = $settings->offChain->x25519Keys->bob;
    $bobCryptPublicKey = Crypto::createPublicKeyX25519FromBase64($bobCryptKeyInfo->publicKeyStr);

    // Create a Katena API helper
    $txSigner = new TxSigner(Common::concatFqId($aliceCompanyBcId, $aliceSignPrivateKeyId), $aliceSignPrivateKey);
    $transactor = new Transactor($apiUrl, $chainID, $txSigner);

    // Off-chain information Alice wants to send
    $secretId = $settings->secretId;
    $content = "off_chain_secret_to_crypt_from_php";

    try {
        // Alice will use its private key and Bob's public key to encrypt a message
        $encryptedInfo = $aliceCryptPrivateKey->seal($content, $bobCryptPublicKey);

        // Send a version 1 of a secret nacl box on Katena
        $txResult = $transactor->sendSecretNaclBoxV1Tx($secretId, $aliceCryptPrivateKey->getPublicKey(),
            $encryptedInfo['nonce'], $encryptedInfo['encryptedMessage']);

        Log::println("Result :");
        Log::printlnJson($txResult);

    } catch (ApiException $e) {
        echo $e;
    } catch (SodiumException|GuzzleException|Exception $e) {
        echo $e->getCode() . " " . $e->getMessage();
    }
}

main();
