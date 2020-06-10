<?php

/**
 * Copyright (c) 2018, TransChain.
 *
 * This source code is licensed under the Apache 2.0 license found in the
 * LICENSE file in the root directory of this source tree.
 */

namespace KatenaChain\Client\Entity;

use KatenaChain\Client\Serializer\Normalizer\TxDataNormalizable;

/**
 * TxData interface defines the methods a concrete TxData must implement.
 */
interface TxData extends TxDataNormalizable
{
    /**
     * @return string
     */
    public function getType(): string;

    /**
     * @param string $signerCompanyBcId
     * @return array
     */
    public function getStateIds(string $signerCompanyBcId): array;

    /**
     * @return string
     */
    public function getNamespace(): string;
}
