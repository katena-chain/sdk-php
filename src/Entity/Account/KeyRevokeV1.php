<?php

/**
 * Copyright (c) 2018, TransChain.
 *
 * This source code is licensed under the Apache 2.0 license found in the
 * LICENSE file in the root directory of this source tree.
 */

namespace KatenaChain\Client\Entity\Account;

use KatenaChain\Client\Entity\TxData;
use KatenaChain\Client\Utils\Common;

/**
 * KeyRevokeV1 is the first version of a key revoke message.
 */
class KeyRevokeV1 implements TxData
{
    /**
     * @var string
     */
    protected $id;


    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @param string $id
     * @return KeyRevokeV1
     */
    public function setId(string $id): KeyRevokeV1
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return Account::getKeyRevokeV1Type();
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
