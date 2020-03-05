<?php

/**
 * Copyright (c) 2018, TransChain.
 *
 * This source code is licensed under the Apache 2.0 license found in the
 * LICENSE file in the root directory of this source tree.
 */

namespace KatenaChain\Client\Entity;

use KatenaChain\Client\Serializer\Normalizer\ApiTxNormalizable;

/**
 * TxData interface defines the methods a concrete TxData must implement.
 */
interface TxData extends ApiTxNormalizable
{
    /**
     * @return string
     */
    public function getId(): string;
}
