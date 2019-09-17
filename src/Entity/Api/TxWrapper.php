<?php

/**
 * Copyright (c) 2018, TransChain.
 *
 * This source code is licensed under the Apache 2.0 license found in the
 * LICENSE file in the root directory of this source tree.
 */

namespace KatenaChain\Client\Entity\Api;

use KatenaChain\Client\Entity\Tx;

/**
 * TxWrapper wraps a tx and its status.
 */
class TxWrapper
{
    /**
     * @var Tx
     */
    protected $tx;

    /**
     * @var TxStatus
     */
    protected $status;

    /**
     * @param Tx $tx
     * @return TxWrapper
     */
    public function setTx(Tx $tx): TxWrapper
    {
        $this->tx = $tx;
        return $this;
    }

    /**
     * @return Tx
     */
    public function getTx(): Tx
    {
        return $this->tx;
    }

    /**
     * @param TxStatus $status
     * @return TxWrapper
     */
    public function setStatus(TxStatus $status): TxWrapper
    {
        $this->status = $status;
        return $this;
    }

    /**
     * @return TxStatus
     */
    public function getStatus(): TxStatus
    {
        return $this->status;
    }
}
