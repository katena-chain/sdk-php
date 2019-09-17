<?php

/**
 * Copyright (c) 2018, TransChain.
 *
 * This source code is licensed under the Apache 2.0 license found in the
 * LICENSE file in the root directory of this source tree.
 */

namespace KatenaChain\Client\Entity\Certify;

use KatenaChain\Client\Entity\Bytes;
use KatenaChain\Client\Crypto\Ed25519\PublicKey;
use KatenaChain\Client\Entity\TxData;

/**
 * CertificateEd25519V1 is the first version of an ed25519 certificate.
 */
class CertificateEd25519V1 implements TxData
{
    use Certifiable;

    /**
     * @var string
     */
    protected $id;

    /**
     * @var PublicKey
     */
    protected $signer;

    /**
     * @var Bytes
     */
    protected $signature;

    /**
     * @return string
     */
    public function getType(): string
    {
        return Certifiable::getTypeCertificateEd25519V1();
    }

    /**
     * @param string $id
     * @return CertificateEd25519V1
     */
    public function setId(string $id): CertificateEd25519V1
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @param PublicKey $signer
     * @return CertificateEd25519V1
     */
    public function setSigner(PublicKey $signer): CertificateEd25519V1
    {
        $this->signer = $signer;
        return $this;
    }

    /**
     * @return PublicKey
     */
    public function getSigner(): PublicKey
    {
        return $this->signer;
    }

    /**
     * @param Bytes $signature
     * @return CertificateEd25519V1
     */
    public function setSignature(Bytes $signature): CertificateEd25519V1
    {
        $this->signature = $signature;
        return $this;
    }

    /**
     * @return Bytes
     */
    public function getSignature(): Bytes
    {
        return $this->signature;
    }
}
