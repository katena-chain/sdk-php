<?php

/**
 * Copyright (c) 2018, TransChain.
 *
 * This source code is licensed under the Apache 2.0 license found in the
 * LICENSE file in the root directory of this source tree.
 */

namespace KatenaChain\Client\Serializer\Normalizer;

use KatenaChain\Client\Entity\Api\TxWrapper;
use KatenaChain\Client\Entity\Api\TxWrappers;
use Symfony\Component\Serializer\Normalizer\ArrayDenormalizer;

class TxWrappersNormalizer extends ArrayDenormalizer
{

    /**
     * @param $data
     * @param $type
     * @param null $format
     * @param array $context
     * @return array|TxWrappers|mixed|object
     * @throws \Symfony\Component\Serializer\Exception\ExceptionInterface
     */
    public function denormalize($data, $type, $format = null, array $context = [])
    {
        /**
         * @var TxWrappers $txWrappers
         */
        $txs = parent::denormalize($data['txs'], TxWrapper::class . "[]", $format, $context);
        $txWrappers = new $type();
        $txWrappers->setTotal($data['total'])
            ->setTxs($txs);
        return $txWrappers;
    }

    /**
     * @param $data
     * @param null $format
     * @return bool
     */
    public function supportsNormalization($data, $format = null)
    {
        return ($data instanceof TxWrappers);
    }

    /**
     * @param $data
     * @param $type
     * @param null $format
     * @param array $context
     * @return bool
     */
    public function supportsDenormalization($data, $type, $format = null, array $context = [])
    {
        return ($type == TxWrappers::class);
    }
}
