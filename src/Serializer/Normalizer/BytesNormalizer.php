<?php

/**
 * Copyright (c) 2018, TransChain.
 *
 * This source code is licensed under the Apache 2.0 license found in the
 * LICENSE file in the root directory of this source tree.
 */

namespace KatenaChain\Client\Serializer\Normalizer;

use KatenaChain\Client\Entity\Bytes;
use Symfony\Component\Serializer\Normalizer\PropertyNormalizer;

class BytesNormalizer extends PropertyNormalizer
{
    /**
     * @param $obj
     * @param null $format
     * @param array $context
     * @return array|bool|float|int|mixed|string
     */
    public function normalize($obj, $format = null, array $context = [])
    {
        /**
         * @var Bytes $obj
         */
        return base64_encode($obj->getData());
    }

    /**
     * @param $data
     * @param $type
     * @param null $format
     * @param array $context
     * @return array|mixed|object
     */
    public function denormalize($data, $type, $format = null, array $context = [])
    {
        return new $type(base64_decode($data));
    }

    /**
     * @param $data
     * @param null $format
     * @return bool
     */
    public function supportsNormalization($data, $format = null)
    {
        return ($data instanceof Bytes);
    }

    /**
     * @param $data
     * @param $type
     * @param null $format
     * @return bool
     */
    public function supportsDenormalization($data, $type, $format = null)
    {
        return ($type == Bytes::class);
    }
}
