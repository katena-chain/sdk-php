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
use KatenaChain\Client\Utils\Common;
use KatenaChain\Client\Utils\Crypto;

function main()
{
    // Bob wants to read a nacl box secret from Alice to decrypt an off-chain data

    // Common Katena network information
    $apiUrl = "https://nodes.test.katena.transchain.io/api/v1";

    // Alice Katena network information
    $aliceCompanyChainID = "abcdef";

    // Create a Katena API helper
    $transactor = new Transactor($apiUrl);

    // Nacl box information
    $bobCryptPrivateKeyBase64 = "quGBP8awD/J3hjSvwGD/sZRcMDks8DPz9Vw0HD4+zecqJP0ojBoc4wQtyq08ywxUksTkdz0/rQNkOsEZBwqWTw==";
    $bobCryptPrivateKey = Crypto::createPrivateKeyX25519FromBase64($bobCryptPrivateKeyBase64);

    // Secret uuid Bob wants to retrieve
    $secretUuid = "2075c941-6876-405b-87d5-13791c0dc53a";

    try {

        // Retrieve version 1 of secrets from Katena
        $txWrappers = $transactor->retrieveSecrets($aliceCompanyChainID, $secretUuid, 1, Common::DEFAULT_PER_PAGE_PARAM);

        foreach ($txWrappers->getTxs() as $index => $txWrapper) {
            /**
             * @var SecretNaclBoxV1 $txData
             */
            $txData = $txWrapper->getTx()->getData();
            echo "Transaction status" . PHP_EOL;
            echo sprintf("  Code    : %d" . PHP_EOL, $txWrapper->getStatus()->getCode());
            echo sprintf("  Message : %s" . PHP_EOL, $txWrapper->getStatus()->getMessage());

            echo "SecretNaclBoxV1" . PHP_EOL;
            echo sprintf("  Id                : %s" . PHP_EOL, $txData->getId());
            echo sprintf("  Data sender       : %s" . PHP_EOL, base64_encode($txData->getSender()->getKey()));
            echo sprintf("  Data nonce        : %s" . PHP_EOL, base64_encode($txData->getNonce()->getData()));
            echo sprintf("  Data content      : %s" . PHP_EOL, base64_encode($txData->getContent()->getData()));

            // Bob will use its private key and the sender's public key (needs to be Alice's) to decrypt a message
            $decryptedContent = $bobCryptPrivateKey->open(
                $txData->getContent()->getData(),
                $txData->getSender(),
                $txData->getNonce()->getData()
            );

            if ($decryptedContent === "") {
                $decryptedContent = "Unable to decrypt";
            }
            echo sprintf("  Decrypted content : %s" . PHP_EOL, $decryptedContent);
            echo PHP_EOL;
        }

    } catch (ApiException $e) {
        echo $e;
    } catch (GuzzleException|Exception $e) {
        echo $e->getCode() . " " . $e->getMessage();
    }
}

main();
