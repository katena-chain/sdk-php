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
use KatenaChain\Client\Entity\Account\KeyV1;
use KatenaChain\Client\Entity\Api\TxResults;
use KatenaChain\Client\Entity\Api\SendTxResult;
use KatenaChain\Client\Entity\Api\TxResult;
use KatenaChain\Client\Entity\Bytes;
use KatenaChain\Client\Entity\Tx;
use KatenaChain\Client\Entity\TxData;
use KatenaChain\Client\Entity\TxSigner;
use KatenaChain\Client\Exceptions\ApiException;
use KatenaChain\Client\Serializer\Serializer;
use KatenaChain\Client\Utils\Common;
use SodiumException;

/**
 * Handler provides helper methods to send and retrieve tx without directly interacting with the HTTP Client.
 */
class Handler
{
    const LAST_PATH         = "last";
    const STATE_PATH        = "state";
    const TXS_PATH          = "txs";
    const CERTIFICATES_PATH = "certificates";
    const SECRETS_PATH      = "secrets";
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
     * fetches the API to return the last tx related to a certificate fqid.
     * @param string $fqId
     * @return TxResult
     * @throws ApiException
     * @throws GuzzleException
     * @throws Exception
     */
    public function retrieveLastCertificateTx(string $fqId): TxResult
    {
        try {
            $apiResponse = $this->apiClient->get(
                vsprintf(
                    "/%s/%s/%s",
                    [
                        self::CERTIFICATES_PATH,
                        $fqId,
                        self::LAST_PATH
                    ]
                )
            );
        } catch (BadResponseException $e) {
            throw ApiException::fromJSON($e->getResponse()->getBody()->getContents());
        }

        return $this->serializer->deserialize($apiResponse->getBody(), TxResult::class);
    }

    /**
     * fetches the API to return all txs related to a certificate fqid.
     * @param string $fqId
     * @param int $page
     * @param int $txPerPage
     * @return TxResults
     * @throws ApiException
     * @throws GuzzleException
     * @throws Exception
     */
    public function retrieveCertificateTxs(string $fqId, int $page, int $txPerPage): TxResults
    {
        try {
            $queryParams = Common::getPaginationQueryParams($page, $txPerPage);
            $apiResponse = $this->apiClient->get(
                vsprintf(
                    "/%s/%s",
                    [
                        self::CERTIFICATES_PATH,
                        $fqId
                    ]
                ),
                $queryParams
            );
        } catch (BadResponseException $e) {
            throw ApiException::fromJSON($e->getResponse()->getBody()->getContents());
        }
        return $this->serializer->deserialize($apiResponse->getBody(), TxResults::class);
    }

    /**
     * fetches the API to return the last tx related to a secret fqid.
     * @param string $fqId
     * @return TxResult
     * @throws ApiException
     * @throws GuzzleException
     * @throws Exception
     */
    public function retrieveLastSecretTx(string $fqId): TxResult
    {
        try {
            $apiResponse = $this->apiClient->get(
                vsprintf(
                    "/%s/%s/%s",
                    [
                        self::SECRETS_PATH,
                        $fqId,
                        self::LAST_PATH
                    ]
                )
            );
        } catch (BadResponseException $e) {
            throw ApiException::fromJSON($e->getResponse()->getBody()->getContents());
        }

        return $this->serializer->deserialize($apiResponse->getBody(), TxResult::class);
    }

    /**
     * fetches the API to return all txs related to a secret fqid.
     * @param string $fqId
     * @param int $page
     * @param int $txPerPage
     * @return TxResults
     * @throws ApiException
     * @throws GuzzleException
     * @throws Exception
     */
    public function retrieveSecretTxs(string $fqId, int $page, int $txPerPage): TxResults
    {
        try {
            $queryParams = Common::getPaginationQueryParams($page, $txPerPage);
            $apiResponse = $this->apiClient->get(
                vsprintf(
                    "/%s/%s",
                    [
                        self::SECRETS_PATH,
                        $fqId
                    ]
                ),
                $queryParams
            );
        } catch (BadResponseException $e) {
            throw ApiException::fromJSON($e->getResponse()->getBody()->getContents());
        }

        return $this->serializer->deserialize($apiResponse->getBody(), TxResults::class);
    }

    /**
     * fetches the API to return the last tx related to a key fqid.
     * @param string $fqId
     * @return TxResult
     * @throws ApiException
     * @throws GuzzleException
     * @throws Exception
     */
    public function retrieveLastKeyTx(string $fqId): TxResult
    {
        try {
            $apiResponse = $this->apiClient->get(
                vsprintf(
                    "/%s/%s/%s",
                    [
                        self::KEYS_PATH,
                        $fqId,
                        self::LAST_PATH
                    ]
                )
            );
        } catch (BadResponseException $e) {
            throw ApiException::fromJSON($e->getResponse()->getBody()->getContents());
        }

        return $this->serializer->deserialize($apiResponse->getBody(), TxResult::class);
    }

    /**
     * fetches the API to return all txs related to a key fqid.
     * @param string $fqId
     * @param int $page
     * @param int $txPerPage
     * @return TxResults
     * @throws ApiException
     * @throws GuzzleException
     * @throws Exception
     */
    public function retrieveKeyTxs(string $fqId, int $page, int $txPerPage): TxResults
    {
        try {
            $queryParams = Common::getPaginationQueryParams($page, $txPerPage);
            $apiResponse = $this->apiClient->get(
                vsprintf(
                    "/%s/%s",
                    [
                        self::KEYS_PATH,
                        $fqId
                    ]
                ),
                $queryParams
            );
        } catch (BadResponseException $e) {
            throw ApiException::fromJSON($e->getResponse()->getBody()->getContents());
        }

        return $this->serializer->deserialize($apiResponse->getBody(), TxResults::class);
    }

    /**
     * fetches the API to return any tx by its hash.
     * @param string $hash
     * @return TxResult
     * @throws ApiException
     * @throws GuzzleException
     */
    public function retrieveTx(string $hash): TxResult
    {
        try {
            $apiResponse = $this->apiClient->get(
                vsprintf(
                    "/%s/%s",
                    [
                        self::TXS_PATH,
                        $hash,
                    ]
                )
            );
        } catch (BadResponseException $e) {
            throw ApiException::fromJSON($e->getResponse()->getBody()->getContents());
        }

        return $this->serializer->deserialize($apiResponse->getBody(), TxResult::class);
    }

    /**
     * fetches the API and returns a certificate from the state.
     * @param string $fqId
     * @return TxData
     * @throws ApiException
     * @throws GuzzleException
     */
    public function retrieveCertificate(string $fqId): TxData
    {
        try {
            $apiResponse = $this->apiClient->get(
                vsprintf(
                    "/%s/%s/%s",
                    [
                        self::STATE_PATH,
                        self::CERTIFICATES_PATH,
                        $fqId
                    ]
                )
            );
        } catch (BadResponseException $e) {
            throw ApiException::fromJSON($e->getResponse()->getBody()->getContents());
        }

        return $this->serializer->deserialize($apiResponse->getBody(), TxData::class);
    }

    /**
     * fetches the API and returns a secret from the state.
     * @param string $fqId
     * @return TxData
     * @throws ApiException
     * @throws GuzzleException
     */
    public function retrieveSecret(string $fqId): TxData
    {
        try {
            $apiResponse = $this->apiClient->get(
                vsprintf(
                    "/%s/%s/%s",
                    [
                        self::STATE_PATH,
                        self::SECRETS_PATH,
                        $fqId
                    ]
                )
            );
        } catch (BadResponseException $e) {
            throw ApiException::fromJSON($e->getResponse()->getBody()->getContents());
        }

        return $this->serializer->deserialize($apiResponse->getBody(), TxData::class);
    }

    /**
     * fetches the API and returns a key from the state.
     * @param string $fqId
     * @return KeyV1
     * @throws ApiException
     * @throws GuzzleException
     */
    public function retrieveKey(string $fqId): KeyV1
    {
        try {
            $apiResponse = $this->apiClient->get(
                vsprintf(
                    "/%s/%s/%s",
                    [
                        self::STATE_PATH,
                        self::KEYS_PATH,
                        $fqId
                    ]
                )
            );
        } catch (BadResponseException $e) {
            throw ApiException::fromJSON($e->getResponse()->getBody()->getContents());
        }

        return $this->serializer->deserialize($apiResponse->getBody(), KeyV1::class);
    }

    /**
     * fetches the API and returns a list of keys for a company from the state.
     * @param string $companyBcId
     * @param int $page
     * @param int $txPerPage
     * @return KeyV1[]
     * @throws ApiException
     * @throws GuzzleException
     */
    public function retrieveCompanyKeys(string $companyBcId, int $page, int $txPerPage): array
    {
        try {
            $queryParams = Common::getPaginationQueryParams($page, $txPerPage);
            $apiResponse = $this->apiClient->get(
                vsprintf(
                    "/%s/%s/%s/%s",
                    [
                        self::STATE_PATH,
                        self::COMPANIES_PATH,
                        $companyBcId,
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
     * accepts an encoded tx and sends it to the Api to return its status and its hash.
     * @param Tx $tx
     * @return SendTxResult
     * @throws ApiException
     * @throws GuzzleException
     */
    public function sendTx(Tx $tx): SendTxResult
    {
        try {
            $apiResponse = $this->apiClient->post(self::TXS_PATH, $this->serializer->serialize($tx));
        } catch (BadResponseException $e) {
            throw ApiException::fromJSON($e->getResponse()->getBody()->getContents());
        }

        return $this->serializer->deserialize($apiResponse->getBody(), SendTxResult::class);
    }

    /**
     * signs a tx data and returns a new tx ready to be sent.
     * @param TxSigner $txSigner
     * @param string $chainID
     * @param DateTime $nonceTime
     * @param TxData $txData
     * @return Tx
     * @throws SodiumException
     */
    public function signTx(TxSigner $txSigner, string $chainID, DateTime $nonceTime, TxData $txData): Tx
    {
        $txDataState = $this->getTxDataState($chainID, $nonceTime, $txData);
        $signature = $txSigner->getPrivateKey()->sign($txDataState);
        $tx = new Tx();
        $tx->setSignerFqId($txSigner->getFqId())
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
