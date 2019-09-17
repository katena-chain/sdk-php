<?php

/**
 * Copyright (c) 2018, TransChain.
 *
 * This source code is licensed under the Apache 2.0 license found in the
 * LICENSE file in the root directory of this source tree.
 */

namespace KatenaChain\Client\Entity\Api;

/**
 * TxStatus is a tx blockchain status.
 * 0: OK
 * 1: PENDING
 * >1: ERROR WITH CORRESPONDING CODE
 */
class TxStatus
{
    /**
     * @var int
     */
    protected $code;

    /**
     * @var string
     */
    protected $message;

    /**
     * @param int $code
     * @return TxStatus
     */
    public function setCode(int $code): TxStatus
    {
        $this->code = $code;
        return $this;
    }

    /**
     * @return int
     */
    public function getCode(): int
    {
        return $this->code;
    }

    /**
     * @param string $message
     * @return TxStatus
     */
    public function setMessage(string $message): TxStatus
    {
        $this->message = $message;
        return $this;
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }
}
