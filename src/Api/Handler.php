<?php

/**
 * Copyright (c) 2018, TransChain.
 *
 * This source code is licensed under the Apache 2.0 license found in the
 * LICENSE file in the root directory of this source tree.
 */

namespace KatenaChain\Client\Api;

use DateTime;
use Exception;
use GuzzleHttp\Exception\BadResponseException;
use GuzzleHttp\Exception\GuzzleException;
use KatenaChain\Client\Crypto\Ed25519\PrivateKey;
use KatenaChain\Client\Entity\Account\KeyV1;
use KatenaChain\Client\Entity\Api\TxStatus;
use KatenaChain\Client\Entity\Api\TxWrapper;
use KatenaChain\Client\Entity\Api\TxWrappers;
use KatenaChain\Client\Entity\Bytes;
use KatenaChain\Client\Entity\Tx;
use KatenaChain\Client\Entity\TxData;
use KatenaChain\Client\Exceptions\ApiException;
use KatenaChain\Client\Serializer\Serializer;
use KatenaChain\Client\Utils\Common;
use SodiumException;

/**
 * Handler provides helper methods to send and retrieve tx without directly interacting with the HTTP Client.
 */
class Handler
{
    const CERTIFICATES_PATH = "certificates";
    const SECRETS_PATH      = "secrets";
    const LAST_PATH         = "last";
    const TXS_PATH          = "txs";
    const COMPANIES_PATH    = "companies";
    const KEYS_PATH         = "keys";

    /**
     * @var Client
     */
    protected $apiClient;

    /**
     * @var Serializer
     */
    private $serializer;

    /**
     * Handler constructor.
     * @param string $apiUrl
     * @param Serializer $serializer
     */
    public function __construct(string $apiUrl, Serializer $serializer)
    {
        $this->apiClient = new Client($apiUrl);
        $this->serializer = $serializer;
    }

    /**
     * accepts a tx and sends it to the Api to return a tx status or throws an error.
     * @param Tx $tx
     * @return TxStatus
     * @throws ApiException
     * @throws GuzzleException
     */
    public function sendTx(Tx $tx): TxStatus
    {
        try {
            $apiResponse = $this->apiClient->post(self::TXS_PATH, $this->serializer->serialize($tx));
        } catch (BadResponseException $e) {
            throw ApiException::fromJSON($e->getResponse()->getBody()->getContents());
        }

        return $this->serializer->deserialize($apiResponse->getBody(), TxStatus::class);
    }

    /**
     * fetches the API and returns a tx wrapper or throws an error.
     * @param string $id
     * @return TxWrapper
     * @throws ApiException
     * @throws GuzzleException
     * @throws Exception
     */
    public function retrieveLastCertificate(string $id): TxWrapper
    {
        try {
            $apiResponse = $this->apiClient->get(
                vsprintf(
                    "/%s/%s/%s",
                    [
                        self::CERTIFICATES_PATH,
                        $id,
                        self::LAST_PATH
                    ]
                )
            );
        } catch (BadResponseException $e) {
            throw ApiException::fromJSON($e->getResponse()->getBody()->getContents());
        }

        return $this->serializer->deserialize($apiResponse->getBody(), TxWrapper::class);
    }

    /**
     * fetches the API and returns a tx wrapper list or throws an error.
     * @param string $id
     * @param int $page
     * @param int $txPerPage
     * @return TxWrappers
     * @throws ApiException
     * @throws GuzzleException
     * @throws Exception
     */
    public function retrieveCertificates(string $id, int $page, int $txPerPage): TxWrappers
    {
        try {
            $queryParams = Common::getPaginationQueryParams($page, $txPerPage);
            $apiResponse = $this->apiClient->get(
                vsprintf(
                    "/%s/%s",
                    [
                        self::CERTIFICATES_PATH,
                        $id
                    ]
                ),
                $queryParams
            );
        } catch (BadResponseException $e) {
            throw ApiException::fromJSON($e->getResponse()->getBody()->getContents());
        }

        return $this->serializer->deserialize($apiResponse->getBody(), TxWrappers::class);
    }

    /**
     * fetches the API and returns a tx wrapper list or throws an error.
     * @param string $id
     * @param int $page
     * @param int $txPerPage
     * @return TxWrappers
     * @throws ApiException
     * @throws GuzzleException
     * @throws Exception
     */
    public function retrieveSecrets(string $id, int $page, int $txPerPage): TxWrappers
    {
        try {
            $queryParams = Common::getPaginationQueryParams($page, $txPerPage);
            $apiResponse = $this->apiClient->get(
                vsprintf(
                    "/%s/%s",
                    [
                        self::SECRETS_PATH,
                        $id
                    ]
                ),
                $queryParams
            );
        } catch (BadResponseException $e) {
            throw ApiException::fromJSON($e->getResponse()->getBody()->getContents());
        }

        return $this->serializer->deserialize($apiResponse->getBody(), TxWrappers::class);
    }

    /**
     * fetches the API and returns the list of keyV1 for a company or throws an error.
     * @param string $companyBcid
     * @param int $page
     * @param int $txPerPage
     * @return KeyV1[]
     * @throws ApiException
     * @throws GuzzleException
     */
    public function retrieveCompanyKeys(string $companyBcid, int $page, int $txPerPage): array
    {
        try {
            $queryParams = Common::getPaginationQueryParams($page, $txPerPage);
            $apiResponse = $this->apiClient->get(
                vsprintf(
                    "/%s/%s/%s",
                    [
                        self::COMPANIES_PATH,
                        $companyBcid,
                        self::KEYS_PATH
                    ]
                ),
                $queryParams
            );
        } catch (BadResponseException $e) {
            throw ApiException::fromJSON($e->getResponse()->getBody()->getContents());
        }

        return $this->serializer->deserialize($apiResponse->getBody(), KeyV1::class . "[]");
    }

    /**
     * fetches the API and returns a tx wrapper list or throws an error.
     * @param string $txCategory
     * @param string $id
     * @param int $page
     * @param int $txPerPage
     * @return TxWrappers
     * @throws ApiException
     * @throws GuzzleException
     */
    public function retrieveTxs(string $txCategory, string $id, int $page, int $txPerPage): TxWrappers
    {
        try {
            $queryParams = Common::getPaginationQueryParams($page, $txPerPage);
            $apiResponse = $this->apiClient->get(
                vsprintf(
                    "/%s/%s/%s",
                    [
                        $txCategory,
                        self::TXS_PATH,
                        $id
                    ]
                ),
                $queryParams
            );
        } catch (BadResponseException $e) {
            throw ApiException::fromJSON($e->getResponse()->getBody()->getContents());
        }

        return $this->serializer->deserialize($apiResponse->getBody(), TxWrappers::class);
    }

    /**
     * signs a tx data and returns a new tx ready to be sent.
     * @param PrivateKey $privateKey
     * @param string $chainID
     * @param DateTime $nonceTime
     * @param TxData $txData
     * @return Tx
     * @throws SodiumException
     */
    public function signTx(PrivateKey $privateKey, string $chainID, DateTime $nonceTime, TxData $txData): Tx
    {
        $txDataState = $this->getTxDataState($chainID, $nonceTime, $txData);
        $signature = $privateKey->sign($txDataState);
        $tx = new Tx();
        $tx->setSigner($privateKey->getPublicKey())
            ->setSignature(new Bytes($signature))
            ->setData($txData)
            ->setNonceTime($nonceTime);
        return $tx;
    }

    /**
     * returns the sorted and marshaled json representation of a TxData ready to be signed.
     * @param string $chainID
     * @param DateTime $nonceTime
     * @param TxData $txData
     * @return string
     */
    public function getTxDataState(string $chainID, DateTime $nonceTime, TxData $txData): string
    {
        return $this->serializer->serialize(
            [
                'chain_id'   => $chainID,
                'data'       => $txData,
                'nonce_time' => $nonceTime,
            ]
        );
    }
}
