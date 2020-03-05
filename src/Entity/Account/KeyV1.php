<?php

/**
 * Copyright (c) 2018, TransChain.
 *
 * This source code is licensed under the Apache 2.0 license found in the
 * LICENSE file in the root directory of this source tree.
 */

namespace KatenaChain\Client\Entity\Account;

use KatenaChain\Client\Crypto\Ed25519\PublicKey;

/**
 * KeyV1 is the first version of a key.
 */
class KeyV1
{
    /**
     * @var string
     */
    protected $companyBcid;

    /**
     * @var PublicKey
     */
    protected $publicKey;

    /**
     * @var bool
     */
    protected $isActive;

    /**
     * @var string
     */
    protected $role;

    /**
     * @return string
     */
    public function getCompanyBcid(): string
    {
        return $this->companyBcid;
    }

    /**
     * @param string $companyBcid
     * @return KeyV1
     */
    public function setCompanyBcid(string $companyBcid): KeyV1
    {
        $this->companyBcid = $companyBcid;
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
     * @return KeyV1
     */
    public function setPublicKey(PublicKey $publicKey): KeyV1
    {
        $this->publicKey = $publicKey;
        return $this;
    }

    /**
     * @return bool
     */
    public function getIsActive(): bool
    {
        return $this->isActive;
    }

    /**
     * @param bool $isActive
     * @return KeyV1
     */
    public function setIsActive(bool $isActive): KeyV1
    {
        $this->isActive = $isActive;
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
     * @return KeyV1
     */
    public function setRole(string $role): KeyV1
    {
        $this->role = $role;
        return $this;
    }

}
