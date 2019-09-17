<?php

/**
 * Copyright (c) 2018, TransChain.
 *
 * This source code is licensed under the Apache 2.0 license found in the
 * LICENSE file in the root directory of this source tree.
 */

namespace KatenaChain\Client\Api;

use Exception;
use GuzzleHttp\Exception\BadResponseException;
use GuzzleHttp\Exception\GuzzleException;
use KatenaChain\Client\Entity\Api\TxStatus;
use KatenaChain\Client\Entity\Api\TxWrapper;
use KatenaChain\Client\Entity\Api\TxWrappers;
use KatenaChain\Client\Entity\Tx;
use KatenaChain\Client\Exceptions\ApiException;
use KatenaChain\Client\Serializer\Serializer;

/**
 * Handler provides helper methods to send and retrieve tx without directly interacting with the HTTP Client.
 */
class Handler
{
    const ROUTE_CERTIFICATES = "certificates";
    const ROUTE_SECRETS      = "secrets";
    const PATH_CERTIFY       = "certify";
    const PATH_HISTORY       = "history";

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
     * accepts a tx and sends it to the appropriate certificate API route.
     * @param Tx $tx
     *
     * @return TxStatus
     * @throws ApiException
     * @throws GuzzleException
     */
    public function sendCertificate(Tx $tx): TxStatus
    {
        return $this->sendTx(vsprintf("/%s/%s", [self::ROUTE_CERTIFICATES, self::PATH_CERTIFY]), $tx);
    }

    /**
     * accepts a tx and sends it to the appropriate API route.
     * @param Tx $tx
     *
     * @return TxStatus
     * @throws ApiException
     * @throws GuzzleException
     */
    public function sendSecret(Tx $tx): TxStatus
    {
        return $this->sendTx(vsprintf("/%s/%s", [self::ROUTE_SECRETS, self::PATH_CERTIFY]), $tx);
    }

    /**
     * fetches the API and returns a tx wrapper.
     * @param string $id
     *
     * @return TxWrapper
     * @throws ApiException
     * @throws GuzzleException
     * @throws Exception
     */
    public function retrieveCertificate(string $id): TxWrapper
    {
        try {
            $apiResponse = $this->apiClient->get(
                vsprintf(
                    "/%s/%s",
                    [
                        self::ROUTE_CERTIFICATES,
                        $id
                    ]
                )
            );
        } catch (BadResponseException $e) {
            throw ApiException::fromJSON($e->getResponse()->getBody()->getContents());
        }

        return $this->serializer->deserialize($apiResponse->getBody(), TxWrapper::class);
    }

    /**
     * fetches the API and returns a tx wrappers.
     * @param string $id
     *
     * @return TxWrappers
     * @throws ApiException
     * @throws GuzzleException
     * @throws Exception
     */
    public function retrieveCertificatesHistory(string $id): TxWrappers
    {
        try {
            $apiResponse = $this->apiClient->get(
                vsprintf(
                    "/%s/%s/%s",
                    [
                        self::ROUTE_CERTIFICATES,
                        $id,
                        self::PATH_HISTORY
                    ]
                )
            );
        } catch (BadResponseException $e) {
            throw ApiException::fromJSON($e->getResponse()->getBody()->getContents());
        }

        return $this->serializer->deserialize($apiResponse->getBody(), TxWrappers::class);
    }

    /**
     * fetches the API and returns a tx wrapper list.
     * @param string $id
     *
     * @return TxWrappers
     * @throws ApiException
     * @throws GuzzleException
     * @throws Exception
     */
    public function retrieveSecrets(string $id): TxWrappers
    {
        try {
            $apiResponse = $this->apiClient->get(
                vsprintf(
                    "/%s/%s",
                    [
                        self::ROUTE_SECRETS,
                        $id
                    ]
                )
            );
        } catch (BadResponseException $e) {
            throw ApiException::fromJSON($e->getResponse()->getBody()->getContents());
        }

        return $this->serializer->deserialize($apiResponse->getBody(), TxWrappers::class);
    }

    /**
     * tries to send a tx to the API and returns a tx status or throws an api error.
     * @param string $route
     * @param Tx $tx
     *
     * @return TxStatus
     * @throws ApiException
     * @throws GuzzleException
     */
    public function sendTx(string $route, Tx $tx): TxStatus
    {
        try {
            $apiResponse = $this->apiClient->post($route, $this->serializer->serialize($tx));
        } catch (BadResponseException $e) {
            throw ApiException::fromJSON($e->getResponse()->getBody()->getContents());
        }

        return $this->serializer->deserialize($apiResponse->getBody(), TxStatus::class);
    }
}
