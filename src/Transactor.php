<?php

/**
 * Copyright (c) 2018, TransChain.
 *
 * This source code is licensed under the Apache 2.0 license found in the
 * LICENSE file in the root directory of this source tree.
 */

namespace KatenaChain\Client;

use DateTime;
use GuzzleHttp\Exception\GuzzleException;
use KatenaChain\Client\Api\Handler;
use KatenaChain\Client\Crypto\Ed25519\PrivateKey;
use KatenaChain\Client\Crypto\Ed25519\PublicKey as Ed25519PubKey;
use KatenaChain\Client\Crypto\Nacl\PublicKey as NaclPubKey;
use KatenaChain\Client\Entity\Api\TxStatus;
use KatenaChain\Client\Entity\Api\TxWrapper;
use KatenaChain\Client\Entity\Api\TxWrappers;
use KatenaChain\Client\Entity\Bytes;
use KatenaChain\Client\Entity\Certify\CertificateEd25519V1;
use KatenaChain\Client\Entity\Certify\CertificateRawV1;
use KatenaChain\Client\Entity\Certify\SecretNaclBoxV1;
use KatenaChain\Client\Entity\Tx;
use KatenaChain\Client\Entity\TxData;
use KatenaChain\Client\Exceptions\ApiException;
use KatenaChain\Client\Exceptions\ClientException;
use KatenaChain\Client\Serializer\Serializer;
use SodiumException;

/**
 * Transactor provides helper methods to hide the complexity of Tx creation, signature and API dialog.
 */
class Transactor
{
    const FORMAT_ID = "%s-%s";

    /**
     * @var PrivateKey
     */
    protected $txSigner;

    /**
     * @var Handler
     */
    protected $apiHandler;

    /**
     * @var string
     */
    protected $companyChainId;

    /**
     * @var string
     */
    protected $chainId;

    /**
     * Transactor constructor.
     * @param string $apiUrl
     * @param string $chainId
     * @param string $companyChainId
     * @param PrivateKey $txSigner
     */
    public function __construct(
        string $apiUrl,
        string $chainId = "",
        string $companyChainId = "",
        ?PrivateKey $txSigner = null
    )
    {
        $this->serializer = new Serializer();
        $this->apiHandler = new Handler($apiUrl, $this->serializer);
        $this->chainId = $chainId;
        $this->txSigner = $txSigner;
        $this->companyChainId = $companyChainId;
    }

    /**
     * @var Serializer
     */
    private $serializer;

    /**
     * creates a CertificateRaw (V1), wraps in a tx and sends it to the API.
     * @param string $uuid
     * @param string $value
     *
     * @return TxStatus
     * @throws ApiException
     * @throws GuzzleException
     * @throws SodiumException
     * @throws ClientException
     */
    public function sendCertificateRawV1(string $uuid, string $value): TxStatus
    {
        $certificate = new CertificateRawV1();
        $certificate->setId($this->formatBcid($this->companyChainId, $uuid))
            ->setValue(new Bytes($value));

        $tx = $this->getTx($certificate);

        return $this->apiHandler->sendCertificate($tx);
    }

    /**
     * creates a CertificateEd25519 (V1), wraps in a tx and sends it to the API.
     * @param string $uuid
     * @param Ed25519PubKey $signer
     * @param string $signature
     *
     * @return TxStatus
     * @throws ApiException
     * @throws GuzzleException
     * @throws SodiumException
     * @throws ClientException
     */
    public function sendCertificateEd25519V1(string $uuid, Ed25519PubKey $signer, string $signature): TxStatus
    {
        $certificate = new CertificateEd25519V1();
        $certificate->setId($this->formatBcid($this->companyChainId, $uuid))
            ->setSigner($signer)
            ->setSignature(new Bytes($signature));

        $tx = $this->getTx($certificate);

        return $this->apiHandler->sendCertificate($tx);
    }

    /**
     * fetches the API to find the corresponding tx and return a tx wrapper.
     * @param string $companyChainID
     * @param string $uuid
     *
     * @return TxWrapper
     * @throws ApiException
     * @throws GuzzleException
     */
    public function retrieveCertificate(string $companyChainID, string $uuid): TxWrapper
    {
        return $this->apiHandler->retrieveCertificate($this->formatBcid($companyChainID, $uuid));
    }

    /**
     * fetches the API to find the corresponding txs and returns tx wrappers or an error.
     * @param string $companyChainID
     * @param string $uuid
     *
     * @return TxWrappers
     * @throws ApiException
     * @throws GuzzleException
     */
    public function retrieveCertificatesHistory(string $companyChainID, string $uuid): TxWrappers
    {
        return $this->apiHandler->retrieveCertificatesHistory($this->formatBcid($companyChainID, $uuid));
    }

    /**
     * creates a SecretNaclBox (V1), wraps in a tx and sends it to the API.
     * @param string $uuid
     * @param NaclPubKey $sender
     * @param Bytes $nonce
     * @param Bytes $content
     *
     * @return TxStatus
     * @throws ApiException
     * @throws GuzzleException
     * @throws SodiumException
     * @throws ClientException
     */
    public function sendSecretNaclBoxV1(
        string $uuid,
        NaclPubKey $sender,
        Bytes $nonce,
        Bytes $content
    ): TxStatus
    {
        $secret = new SecretNaclBoxV1();
        $secret->setId($this->formatBcid($this->companyChainId, $uuid))
            ->setNonce($nonce)
            ->setSender($sender)
            ->setContent($content);

        $tx = $this->getTx($secret);

        return $this->apiHandler->sendSecret($tx);
    }

    /**
     * fetches the API to find the corresponding txs and returns tx wrappers.
     * @param string $companyChainId
     * @param string $uuid
     *
     * @return TxWrappers
     * @throws ApiException
     * @throws GuzzleException
     */
    public function retrieveSecrets(string $companyChainId, string $uuid): TxWrappers
    {
        return $this->apiHandler->retrieveSecrets($this->formatBcid($companyChainId, $uuid));
    }

    /**
     * signs a tx data and returns a new tx ready to be sent.
     * @param TxData $txData
     *
     * @return Tx
     * @throws ClientException
     * @throws SodiumException
     */
    public function getTx(TxData $txData): Tx
    {
        if (!$this->txSigner || !$this->companyChainId) {
            throw new ClientException("impossible to create txs without a private key or company chain id");
        }

        $nonceTime = new DateTime();
        $txDataState = $this->getTxDataState($this->chainId, $nonceTime, $txData);

        $tx = new Tx();
        $tx->setSigner($this->txSigner->getPublicKey())
            ->setSignature(new Bytes($this->txSigner->sign($txDataState)))
            ->setData($txData)
            ->setNonceTime($nonceTime);

        return $tx;
    }

    /**
     * returns the sorted and marshaled json representation of a TxData ready to be signed.
     * @param string $chainId
     * @param DateTime $nonceTime
     * @param TxData $txData
     *
     * @return string
     */
    public function getTxDataState(string $chainId, DateTime $nonceTime, TxData $txData): string
    {
        return $this->serializer->serialize(
            [
                'chain_id'   => $chainId,
                'data'       => $txData,
                'nonce_time' => $nonceTime,
            ]
        );
    }

    /**
     * concatenates a company chain id and a uuid into a bcid.
     * @param string $companyChainId
     * @param string $uuid
     *
     * @return string
     */
    protected function formatBcid(string $companyChainId, string $uuid): string
    {
        return vsprintf(self::FORMAT_ID, [$companyChainId, $uuid]);
    }
}
