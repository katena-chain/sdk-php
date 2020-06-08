<?php

/**
 * Copyright (c) 2018, TransChain.
 *
 * This source code is licensed under the Apache 2.0 license found in the
 * LICENSE file in the root directory of this source tree.
 */

namespace KatenaChain\Client\Entity\Api;

/**
 * TxResults is returned by a GET request to retrieve a list of TxResult with the total txs available.
 */
class TxResults
{
    /**
     * @var TxResult[]
     */
    protected $txs;

    /**
     * @var int
     */
    protected $total;

    /**
     * @param TxResult[] $txs
     * @return TxResults
     */
    public function setTxs(array $txs): TxResults
    {
        $this->txs = $txs;
        return $this;
    }

    /**
     * @return TxResult[]
     */
    public function getTxs(): array
    {
        return $this->txs;
    }

    /**
     * @param int $total
     * @return TxResults
     */
    public function setTotal(int $total): TxResults
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
