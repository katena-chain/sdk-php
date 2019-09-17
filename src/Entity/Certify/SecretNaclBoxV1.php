<?php

/**
 * Copyright (c) 2018, TransChain.
 *
 * This source code is licensed under the Apache 2.0 license found in the
 * LICENSE file in the root directory of this source tree.
 */

namespace KatenaChain\Client\Entity\Certify;

use KatenaChain\Client\Entity\Bytes;
use KatenaChain\Client\Crypto\Nacl\PublicKey;
use KatenaChain\Client\Entity\TxData;

/**
 * SecretNaclBoxV1 is the first version of a nacl box secret.
 */
class SecretNaclBoxV1 implements TxData
{

    /**
     * @var string
     */
    protected $id;

    /**
     * @var PublicKey
     */
    protected $sender;

    /**
     * @var Bytes
     */
    protected $nonce;

    /**
     * @var Bytes
     */
    protected $content;

    /**
     * @return string
     */
    public function getType(): string
    {
        return Certifiable::getTypeSecretNaclBoxV1();
    }

    /**
     * @param string $id
     * @return SecretNaclBoxV1
     */
    public function setId(string $id): SecretNaclBoxV1
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
     * @return string
     */
    public function getNamespace(): string
    {
        return Certifiable::getNamespaceCertify();
    }

    /**
     * @return string
     */
    public function getSubNamespace(): string
    {
        return Certifiable::getSecretSubNamespace();
    }

    /**
     * @param PublicKey $sender
     * @return SecretNaclBoxV1
     */
    public function setSender(PublicKey $sender): SecretNaclBoxV1
    {
        $this->sender = $sender;
        return $this;
    }

    /**
     * @return PublicKey
     */
    public function getSender(): PublicKey
    {
        return $this->sender;
    }

    /**
     * @param Bytes $nonce
     * @return SecretNaclBoxV1
     */
    public function setNonce(Bytes $nonce): SecretNaclBoxV1
    {
        $this->nonce = $nonce;
        return $this;
    }

    /**
     * @return Bytes
     */
    public function getNonce(): Bytes
    {
        return $this->nonce;
    }

    /**
     * @param Bytes $content
     * @return SecretNaclBoxV1
     */
    public function setContent(Bytes $content): SecretNaclBoxV1
    {
        $this->content = $content;
        return $this;
    }

    /**
     * @return Bytes
     */
    public function getContent(): Bytes
    {
        return $this->content;
    }
}
