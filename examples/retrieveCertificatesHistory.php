<?php

/**
 * Copyright (c) 2018, TransChain.
 *
 * This source code is licensed under the Apache 2.0 license found in the
 * LICENSE file in the root directory of this source tree.
 */

require_once 'vendor/autoload.php';

use GuzzleHttp\Exception\GuzzleException;
use KatenaChain\Client\Entity\Certify\Certifiable;
use KatenaChain\Client\Entity\Certify\CertificateEd25519V1;
use KatenaChain\Client\Entity\Certify\CertificateRawV1;
use KatenaChain\Client\Exceptions\ApiException;
use KatenaChain\Client\Transactor;

function main()
{
    // Alice wants to retrieve a certificate history

    // Common Katena network information
    $apiUrl = "https://api.test.katena.transchain.io/api/v1";

    // Alice Katena network information
    $aliceCompanyChainId = "abcdef";

    // Create a Katena API helper
    $transactor = new Transactor($apiUrl);

    // Certificate uuid Alice wants to retrieve
    $certificateUuid = "2075c941-6876-405b-87d5-13791c0dc53a";

    try {

        // Retrieve a version 1 of a certificate history from Katena
        $txWrappers = $transactor->retrieveCertificatesHistory($aliceCompanyChainId, $certificateUuid);

        foreach ($txWrappers->getTxs() as $index => $txWrapper) {
            $txData = $txWrapper->getTx()->getData();
            echo "Transaction status" . PHP_EOL;
            echo sprintf("  Code    : %d" . PHP_EOL, $txWrapper->getStatus()->getCode());
            echo sprintf("  Message : %s" . PHP_EOL, $txWrapper->getStatus()->getMessage());

            switch ($txData->getType()) {
                case Certifiable::getTypeCertificateRawV1():
                    /**
                     * @var CertificateRawV1 $txData
                     */
                    echo "CertificateRawV1" . PHP_EOL;
                    echo sprintf("  Id    : %s" . PHP_EOL, $txData->getId());
                    echo sprintf("  Value : %s" . PHP_EOL, $txData->getValue()->getData());
                    echo PHP_EOL;
                    break;
                case Certifiable::getTypeCertificateEd25519V1():
                    /**
                     * @var CertificateEd25519V1 $txData
                     */
                    echo "CertificateEd25519V1" . PHP_EOL;
                    echo sprintf("  Id             : %s" . PHP_EOL, $txData->getId());
                    echo sprintf("  Data signer    : %s" . PHP_EOL, base64_encode($txData->getSigner()->getKey()));
                    echo sprintf("  Data signature : %s" . PHP_EOL, base64_encode($txData->getSignature()->getData()));
                    echo PHP_EOL;
                    break;
                default:
                    throw new \Exception('Unexpected txData type');
            }
        }

    } catch (ApiException $e) {
        echo $e;
    } catch (GuzzleException|Exception $e) {
        echo $e->getCode() . " " . $e->getMessage();
    }
}

main();
