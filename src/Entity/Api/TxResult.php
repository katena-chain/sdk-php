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
 * TxResult is returned by a GET request to retrieve a tx with useful information about its processing.
 */
class TxResult
{
    /**
     * @var string
     */
    private $hash;

    /**
     * @var int
     */
    private $height;

    /**
     * @var int
     */
    private $index;

    /**
     * @var TxStatus
     */
    private $status;

    /**
     * @var Tx
     */
    private $tx;

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
     * @return int
     */
    public function getHeight(): int
    {
        return $this->height;
    }

    /**
     * @param int $height
     */
    public function setHeight(int $height): void
    {
        $this->height = $height;
    }

    /**
     * @return int
     */
    public function getIndex(): int
    {
        return $this->index;
    }

    /**
     * @param int $index
     */
    public function setIndex(int $index): void
    {
        $this->index = $index;
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

    /**
     * @return Tx
     */
    public function getTx(): Tx
    {
        return $this->tx;
    }

    /**
     * @param Tx $tx
     */
    public function setTx(Tx $tx): void
    {
        $this->tx = $tx;
    }
}

