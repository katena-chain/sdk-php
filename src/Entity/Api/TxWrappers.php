<?php

/**
 * Copyright (c) 2018, TransChain.
 *
 * This source code is licensed under the Apache 2.0 license found in the
 * LICENSE file in the root directory of this source tree.
 */

namespace KatenaChain\Client\Entity\Api;

/**
 * TxWrappers wraps a list of TxWrapper with the total txs available.
 */
class TxWrappers
{
    /**
     * @var TxWrapper[]
     */
    protected $txs;

    /**
     * @var int
     */
    protected $total;

    /**
     * @param TxWrapper[] $txs
     * @return TxWrappers
     */
    public function setTxs(array $txs): TxWrappers
    {
        $this->txs = $txs;
        return $this;
    }

    /**
     * @return TxWrapper[]
     */
    public function getTxs(): array
    {
        return $this->txs;
    }

    /**
     * @param int $total
     * @return TxWrappers
     */
    public function setTotal(int $total): TxWrappers
    {
        $this->total = $total;
        return $this;
    }

    /**
     * @return int
     */
    public function getTotal(): int
    {
        return $this->total;
    }
}
