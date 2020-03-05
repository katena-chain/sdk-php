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

/**
 * KeyCreateV1 is the first version of a key create message.
 */
class KeyCreateV1 implements TxData
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
     * @var string
     */
    protected $role;

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @param string $id
     * @return KeyCreatev1
     */
    public function setId(string $id): KeyCreateV1
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
     * @return KeyCreateV1
     */
    public function setPublicKey(PublicKey $publicKey): KeyCreateV1
    {
        $this->publicKey = $publicKey;
        return $this;
    }

    /**
     * @return string
     */
    public function getRole(): string
    {
        return $this->role;
    }

    /**
     * @param string $role
     * @return KeyCreateV1
     */
    public function setRole(string $role): KeyCreateV1
    {
        $this->role = $role;
        return $this;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return Account::getTypeKeyCreateV1();
    }
}
