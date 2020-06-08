<?php

/**
 * Copyright (c) 2018, TransChain.
 *
 * This source code is licensed under the Apache 2.0 license found in the
 * LICENSE file in the root directory of this source tree.
 */

namespace KatenaChain\Client\Entity\Account;

use KatenaChain\Client\Crypto\Ed25519\PublicKey;
use KatenaChain\Client\Entity\TxData;
use KatenaChain\Client\Utils\Common;

/**
 * KeyRotateV1 is the first version of a key rotate message.
 */
class KeyRotateV1 implements TxData
{
    /**
     * @var string
     */
    protected $id;

    /**
     * @var PublicKey
     */
    protected $publicKey;

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @param string $id
     * @return KeyRotateV1
     */
    public function setId(string $id): KeyRotateV1
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return PublicKey
     */
    public function getPublicKey(): PublicKey
    {
        return $this->publicKey;
    }

    /**
     * @param PublicKey $publicKey
     * @return KeyRotateV1
     */
    public function setPublicKey(PublicKey $publicKey): KeyRotateV1
    {
        $this->publicKey = $publicKey;
        return $this;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return Account::getKeyRotateV1Type();
    }

    /**
     * @param string $signerCompanyBcId
     * @return array
     */
    public function getStateIds(string $signerCompanyBcId): array
    {
        return array(
            Account::getKeyIdKey() => Common::concatFqId($signerCompanyBcId, $this->getId())
        );
    }

    /**
     * @return string
     */
    public function getNamespace(): string {
        return Account::NAMESPACE;
    }
}