<?php

/**
 * Copyright (c) 2018, TransChain.
 *
 * This source code is licensed under the Apache 2.0 license found in the
 * LICENSE file in the root directory of this source tree.
 */

namespace KatenaChain\Client\Serializer\Normalizer;

interface TxDataNormalizable
{
    /**
     * @return string
     */
    public function getType(): string;
}
