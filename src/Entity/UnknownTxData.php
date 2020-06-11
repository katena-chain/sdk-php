<?php

/**
 * Copyright (c) 2018, TransChain.
 *
 * This source code is licensed under the Apache 2.0 license found in the
 * LICENSE file in the root directory of this source tree.
 */

namespace KatenaChain\Client\Entity;

use DateTime;
use Symfony\Component\Serializer\Annotation\SerializedName;

/**
 * UnknownTxData is useful to deserialize and serialize back a tx data of unknown type.
 */
class UnknownTxData implements TxData
{
    /**
     * @var string
     */
    protected $type;

    /**
     * @var array
     */
    protected $value;

    /**
     * @param string $type
     * @return UnknownTxData
     */
    public function setType(string $type): UnknownTxData
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @param array $value
     * @return UnknownTxData
     */
    public function setValue(array $value): UnknownTxData
    {
        $this->value = $value;
        return $this;
    }

    /**
     * @return array
     */
    public function getValue(): array
    {
        return $this->value;
    }

    public function getStateIds(string $signerCompanyBcId): array
    {
        return array();
    }

    public function getNamespace(): string
    {
        return "";
    }
}
