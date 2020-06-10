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
use KatenaChain\Client\Crypto\Ed25519\PublicKey as Ed25519PubKey;
use KatenaChain\Client\Crypto\Nacl\PublicKey as NaclPubKey;
use KatenaChain\Client\Entity\Account\KeyCreateV1;
use KatenaChain\Client\Entity\Account\KeyRevokeV1;
use KatenaChain\Client\Entity\Account\KeyRotateV1;
use KatenaChain\Client\Entity\Account\KeyV1;
use KatenaChain\Client\Entity\Api\TxResults;
use KatenaChain\Client\Entity\Api\SendTxResult;
use KatenaChain\Client\Entity\Api\TxResult;
use KatenaChain\Client\Entity\Bytes;
use KatenaChain\Client\Entity\Certify\CertificateEd25519V1;
use KatenaChain\Client\Entity\Certify\CertificateRawV1;
use KatenaChain\Client\Entity\Certify\SecretNaclBoxV1;
use KatenaChain\Client\Entity\TxData;
use KatenaChain\Client\Entity\TxSigner;
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
     * @var TxSigner
     */
    protected $txSigner;

    /**
     * @var Handler
     */
    protected $apiHandler;

    /**
     * @var string
     */
    protected $chainID;

    /**
     * Transactor constructor.
     * @param string $apiUrl
     * @param string $chainID
     * @param TxSigner $txSigner
     */
    public function __construct(
        string $apiUrl,
        string $chainID = "",
        ?TxSigner $txSigner = null
    )
    {
        $this->serializer = new Serializer();
        $this->apiHandler = new Handler($apiUrl, $this->serializer);
        $this->chainID = $chainID;
        $this->txSigner = $txSigner;
    }

    /**
     * @var Serializer
     */
    private $serializer;

    /**
     * creates a CertificateRaw (V1) and sends it to the API.
     * @param string $id
     * @param string $value
     * @return SendTxResult
     * @throws ApiException
     * @throws GuzzleException
     * @throws SodiumException
     * @throws ClientException
     */
    public function sendCertificateRawV1Tx(string $id, string $value): SendTxResult
    {
        $certificate = new CertificateRawV1();
        $certificate->setId($id)
            ->setValue(new Bytes($value));
        return $this->sendTx($certificate);
    }

    /**
     * creates a CertificateEd25519 (V1) and sends it to the API.
     * @param string $id
     * @param Ed25519PubKey $signer
     * @param string $signature
     * @return SendTxResult
     * @throws ApiException
     * @throws GuzzleException
     * @throws SodiumException
     * @throws ClientException
     */
    public function sendCertificateEd25519V1Tx(string $id, Ed25519PubKey $signer, string $signature): SendTxResult
    {
        $certificate = new CertificateEd25519V1();
        $certificate->setId($id)
            ->setSigner($signer)
            ->setSignature(new Bytes($signature));
        return $this->sendTx($certificate);
    }

    /**
     * creates a SecretNaclBox (V1) and sends it to the API.
     * @param string $id
     * @param NaclPubKey $sender
     * @param Bytes $nonce
     * @param Bytes $content
     * @return SendTxResult
     * @throws ApiException
     * @throws GuzzleException
     * @throws SodiumException
     * @throws ClientException
     */
    public function sendSecretNaclBoxV1Tx(
        string $id,
        NaclPubKey $sender,
        Bytes $nonce,
        Bytes $content
    ): SendTxResult
    {
        $secret = new SecretNaclBoxV1();
        $secret->setId($id)
            ->setNonce($nonce)
            ->setSender($sender)
            ->setContent($content);
        return $this->sendTx($secret);
    }

    /**
     * creates a KeyCreate (V1) and sends it to the API.
     * @param string $id
     * @param Ed25519PubKey $publicKey
     * @param string $role
     * @return SendTxResult
     * @throws ApiException
     * @throws ClientException
     * @throws GuzzleException
     * @throws SodiumException
     */
    public function sendKeyCreateV1Tx(string $id, Ed25519PubKey $publicKey, string $role): SendTxResult
    {
        $keyCreate = new KeyCreateV1();
        $keyCreate->setId($id)
            ->setPublicKey($publicKey)
            ->setRole($role);
        return $this->sendTx($keyCreate);
    }

    /**
     * creates a KeyRotate (V1) and sends it to the API.
     * @param string $id
     * @param Ed25519PubKey $publicKey
     * @return SendTxResult
     * @throws ApiException
     * @throws ClientException
     * @throws GuzzleException
     * @throws SodiumException
     */
    public function sendKeyRotateV1Tx(string $id, Ed25519PubKey $publicKey): SendTxResult
    {
        $keyRotate = new KeyRotateV1();
        $keyRotate->setId($id)
            ->setPublicKey($publicKey);
        return $this->sendTx($keyRotate);
    }

    /**
     * creates a KeyRevoke (V1) and sends it to the API.
     * @param string $id
     * @return SendTxResult
     * @throws ApiException
     * @throws ClientException
     * @throws GuzzleException
     * @throws SodiumException
     */
    public function sendKeyRevokeV1Tx(string $id): SendTxResult
    {
        $keyRevoke = new KeyRevokeV1();
        $keyRevoke->setId($id);
        return $this->sendTx($keyRevoke);
    }

    /**
     * creates a tx from a tx data and the provided tx signer info and chain id, signs it, encodes it and sends it
     * to the api.
     * @param TxData $txData
     * @return SendTxResult
     * @throws ApiException
     * @throws ClientException
     * @throws GuzzleException
     * @throws SodiumException
     */
    public function sendTx(TxData $txData): SendTxResult
    {
        if (!$this->txSigner || !$this->chainID) {
            throw new ClientException("impossible to create txs without a tx signer info or chain id");
        }
        $tx = $this->apiHandler->signTx($this->txSigner, $this->chainID, new DateTime(), $txData);
        return $this->apiHandler->sendTx($tx);
    }

    /**
     * fetches the API and returns the last tx related to a certificate fqid.
     * @param string $companyBcId
     * @param string $id
     * @return TxResult
     * @throws ApiException
     * @throws GuzzleException
     */
    public function retrieveLastCertificateTx(string $companyBcId, string $id): TxResult
    {
        return $this->apiHandler->retrieveLastCertificateTx(Common::concatFqId($companyBcId, $id));
    }

    /**
     * fetches the API and returns all txs related to a certificate fqid.
     * @param string $companyBcId
     * @param string $id
     * @param int $page
     * @param int $txPerPage
     * @return TxResults
     * @throws ApiException
     * @throws GuzzleException
     */
    public function retrieveCertificateTxs(string $companyBcId, string $id, int $page, int $txPerPage): TxResults
    {
        return $this->apiHandler->retrieveCertificateTxs(Common::concatFqId($companyBcId, $id), $page, $txPerPage);
    }

    /**
     * fetches the API and returns the last tx related to a secret fqid.
     * @param string $companyBcId
     * @param string $id
     * @return TxResult
     * @throws ApiException
     * @throws GuzzleException
     */
    public function retrieveLastSecretTx(string $companyBcId, string $id): TxResult
    {
        return $this->apiHandler->retrieveLastSecretTx(Common::concatFqId($companyBcId, $id));
    }

    /**
     * fetches the API and returns all txs related to a secret fqid.
     * @param string $companyBcId
     * @param string $id
     * @param int $page
     * @param int $txPerPage
     * @return TxResults
     * @throws ApiException
     * @throws GuzzleException
     */
    public function retrieveSecretTxs(string $companyBcId, string $id, int $page, int $txPerPage): TxResults
    {
        return $this->apiHandler->retrieveSecretTxs(Common::concatFqId($companyBcId, $id), $page, $txPerPage);
    }

    /**
     * fetches the API and returns the last tx related to a key fqid.
     * @param string $companyBcId
     * @param string $id
     * @return TxResult
     * @throws ApiException
     * @throws GuzzleException
     */
    public function retrieveLastKeyTx(string $companyBcId, string $id): TxResult
    {
        return $this->apiHandler->retrieveLastKeyTx(Common::concatFqId($companyBcId, $id));
    }

    /**
     * fetches the API and returns all txs related to a key fqid.
     * @param string $companyBcId
     * @param string $id
     * @param int $page
     * @param int $txPerPage
     * @return TxResults
     * @throws ApiException
     * @throws GuzzleException
     */
    public function retrieveKeyTxs(string $companyBcId, string $id, int $page, int $txPerPage): TxResults
    {
        return $this->apiHandler->retrieveKeyTxs(Common::concatFqId($companyBcId, $id), $page, $txPerPage);
    }

    /**
     * fetches the API and return any tx by its hash.
     * @param string $hash
     * @return TxResult
     * @throws ApiException
     * @throws GuzzleException
     */
    public function retrieveTx(string $hash): TxResult
    {
        return $this->apiHandler->retrieveTx($hash);
    }

    /**
     * fetches the API and returns a key from the state.
     * @param string $companyBcId
     * @param string $id
     * @return KeyV1
     * @throws ApiException
     * @throws GuzzleException
     */
    public function retrieveKey(string $companyBcId, string $id): KeyV1
    {
        return $this->apiHandler->retrieveKey(Common::concatFqId($companyBcId, $id));
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
        return $this->apiHandler->retrieveCompanyKeys($companyBcId, $page, $txPerPage);
    }
}
