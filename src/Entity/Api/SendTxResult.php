<?php

/**
 * Copyright (c) 2018, TransChain.
 *
 * This source code is licensed under the Apache 2.0 license found in the
 * LICENSE file in the root directory of this source tree.
 */

namespace KatenaChain\Client\Entity\Api;

/**
 * SendTxResult is returned by a POST request to retrieve the tx status and its hash.
 */
class SendTxResult
{
    /**
     * @var string
     */
    private $hash;

    /**
     * @var TxStatus
     */
    private $status;

    /**
     * @return string
     */
    public function getHash(): string
    {
        return $this->hash;
    }

    /**
     * @param string $hash
     */
    public function setHash(string $hash): void
    {
        $this->hash = $hash;
    }

    /**
     * @return TxStatus
     */
    public function getStatus(): TxStatus
    {
        return $this->status;
    }

    /**
     * @param TxStatus $status
     */
    public function setStatus(TxStatus $status): void
    {
        $this->status = $status;
    }

}