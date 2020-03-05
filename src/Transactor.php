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
use KatenaChain\Client\Entity\Account\Account;
use KatenaChain\Client\Entity\Account\KeyCreateV1;
use KatenaChain\Client\Entity\Account\KeyRevokeV1;
use KatenaChain\Client\Entity\Account\KeyV1;
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
use KatenaChain\Client\Utils\Common;
use SodiumException;

/**
 * Transactor provides helper methods to hide the complexity of Tx creation, signature and API dialog.
 */
class Transactor
{

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
    protected $companyBcid;

    /**
     * @var string
     */
    protected $chainID;

    /**
     * Transactor constructor.
     * @param string $apiUrl
     * @param string $chainID
     * @param string $companyBcid
     * @param PrivateKey $txSigner
     */
    public function __construct(
        string $apiUrl,
        string $chainID = "",
        string $companyBcid = "",
        ?PrivateKey $txSigner = null
    )
    {
        $this->serializer = new Serializer();
        $this->apiHandler = new Handler($apiUrl, $this->serializer);
        $this->chainID = $chainID;
        $this->txSigner = $txSigner;
        $this->companyBcid = $companyBcid;
    }

    /**
     * @var Serializer
     */
    private $serializer;

    /**
     * creates a CertificateRaw (V1) and sends it to the API.
     * @param string $uuid
     * @param string $value
     * @return TxStatus
     * @throws ApiException
     * @throws GuzzleException
     * @throws SodiumException
     * @throws ClientException
     */
    public function sendCertificateRawV1(string $uuid, string $value): TxStatus
    {
        $certificate = new CertificateRawV1();
        $certificate->setId(Common::formatTxid($this->companyBcid, $uuid))
            ->setValue(new Bytes($value));
        return $this->sendTx($certificate);
    }

    /**
     * creates a CertificateEd25519 (V1) and sends it to the API.
     * @param string $uuid
     * @param Ed25519PubKey $signer
     * @param string $signature
     * @return TxStatus
     * @throws ApiException
     * @throws GuzzleException
     * @throws SodiumException
     * @throws ClientException
     */
    public function sendCertificateEd25519V1(string $uuid, Ed25519PubKey $signer, string $signature): TxStatus
    {
        $certificate = new CertificateEd25519V1();
        $certificate->setId(Common::formatTxid($this->companyBcid, $uuid))
            ->setSigner($signer)
            ->setSignature(new Bytes($signature));
        return $this->sendTx($certificate);
    }

    /**
     * creates a KeyCreate (V1) and sends it to the API.
     * @param string $uuid
     * @param Ed25519PubKey $publicKey
     * @param string $role
     * @return TxStatus
     * @throws ApiException
     * @throws ClientException
     * @throws GuzzleException
     * @throws SodiumException
     */
    public function sendKeyCreateV1(string $uuid, Ed25519PubKey $publicKey, string $role): TxStatus
    {
        $keyCreate = new KeyCreateV1();
        $keyCreate->setId(Common::formatTxid($this->companyBcid, $uuid))
            ->setPublicKey($publicKey)
            ->setRole($role);
        return $this->sendTx($keyCreate);
    }

    /**
     * creates a KeyRevoke (V1) and sends it to the API.
     * @param string $uuid
     * @param Ed25519PubKey $publicKey
     * @return TxStatus
     * @throws ApiException
     * @throws ClientException
     * @throws GuzzleException
     * @throws SodiumException
     */
    public function sendKeyRevokeV1(string $uuid, Ed25519PubKey $publicKey): TxStatus
    {
        $keyRevoke = new KeyRevokeV1();
        $keyRevoke->setId(Common::formatTxid($this->companyBcid, $uuid))
            ->setPublicKey($publicKey);
        return $this->sendTx($keyRevoke);
    }

    /**
     * creates a SecretNaclBox (V1) and sends it to the API.
     * @param string $uuid
     * @param NaclPubKey $sender
     * @param Bytes $nonce
     * @param Bytes $content
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
        $secret->setId(Common::formatTxid($this->companyBcid, $uuid))
            ->setNonce($nonce)
            ->setSender($sender)
            ->setContent($content);
        return $this->sendTx($secret);
    }

    /**
     * signs and sends a tx to the Api.
     * @param TxData $txData
     * @return TxStatus
     * @throws ApiException
     * @throws ClientException
     * @throws GuzzleException
     * @throws SodiumException
     */
    public function sendTx(TxData $txData): TxStatus
    {
        if (!$this->txSigner || !$this->chainID) {
            throw new ClientException("impossible to create txs without a private key or chain id");
        }
        $tx = $this->apiHandler->signTx($this->txSigner, $this->chainID, new DateTime(), $txData);
        return $this->apiHandler->sendTx($tx);
    }

    /**
     * fetches the API and returns a tx wrapper.
     * @param string $companyChainID
     * @param string $uuid
     * @return TxWrapper
     * @throws ApiException
     * @throws GuzzleException
     */
    public function retrieveLastCertificate(string $companyChainID, string $uuid): TxWrapper
    {
        return $this->apiHandler->retrieveLastCertificate(Common::formatTxid($companyChainID, $uuid));
    }

    /**
     * fetches the API and returns a tx wrapper list.
     * @param string $companyChainID
     * @param string $uuid
     * @param int $page
     * @param int $txPerPage
     * @return TxWrappers
     * @throws ApiException
     * @throws GuzzleException
     */
    public function retrieveCertificates(string $companyChainID, string $uuid, int $page, int $txPerPage): TxWrappers
    {
        return $this->apiHandler->retrieveCertificates(Common::formatTxid($companyChainID, $uuid), $page, $txPerPage);
    }

    /**
     * fetches the API and returns a tx wrapper list.
     * @param string $companyChainID
     * @param string $uuid
     * @param int $page
     * @param int $txPerPage
     * @return TxWrappers
     * @throws ApiException
     * @throws GuzzleException
     */
    public function retrieveKeyCreateTxs(string $companyChainID, string $uuid, int $page, int $txPerPage): TxWrappers
    {
        return $this->apiHandler->retrieveTxs(Account::getCategoryKeyCreate(), Common::formatTxid($companyChainID, $uuid), $page, $txPerPage);
    }

    /**
     * fetches the API and returns a tx wrapper list.
     * @param string $companyChainID
     * @param string $uuid
     * @param int $page
     * @param int $txPerPage
     * @return TxWrappers
     * @throws ApiException
     * @throws GuzzleException
     */
    public function retrieveKeyRevokeTxs(string $companyChainID, string $uuid, int $page, int $txPerPage): TxWrappers
    {
        return $this->apiHandler->retrieveTxs(Account::getCategoryKeyRevoke(), Common::formatTxid($companyChainID, $uuid), $page, $txPerPage);
    }

    /**
     * fetches the API and returns a tx wrapper list.
     * @param string $companyChainID
     * @param int $page
     * @param int $txPerPage
     * @return KeyV1[]
     * @throws ApiException
     * @throws GuzzleException
     */
    public function retrieveCompanyKeys(string $companyChainID, int $page, int $txPerPage): array
    {
        return $this->apiHandler->retrieveCompanyKeys($companyChainID, $page, $txPerPage);
    }

    /**
     * fetches the API and returns a tx wrapper list.
     * @param string $companyBcid
     * @param string $uuid
     * @param int $page
     * @param int $txPerPage
     * @return TxWrappers
     * @throws ApiException
     * @throws GuzzleException
     */
    public function retrieveSecrets(string $companyBcid, string $uuid, int $page, int $txPerPage): TxWrappers
    {
        return $this->apiHandler->retrieveSecrets(Common::formatTxid($companyBcid, $uuid), $page, $txPerPage);
    }

    /**
     * fetches the API and returns a tx wrapper list.
     * @param string $txCategory
     * @param string $companyBcid
     * @param string $uuid
     * @param int $page
     * @param int $txPerPage
     * @return TxWrappers
     * @throws ApiException
     * @throws GuzzleException
     */
    public function retrieveTxs(string $txCategory, string $companyBcid, string $uuid, int $page, int $txPerPage): TxWrappers
    {
        return $this->apiHandler->retrieveTxs($txCategory, Common::formatTxid($companyBcid, $uuid), $page, $txPerPage);
    }
}
