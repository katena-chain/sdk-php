<?php

/**
 * Copyright (c) 2018, TransChain.
 *
 * This source code is licensed under the Apache 2.0 license found in the
 * LICENSE file in the root directory of this source tree.
 */

namespace KatenaChain\Client\Serializer\Normalizer;

use DateTime;
use DateTimeZone;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer as BaseNormalizer;

class DateTimeNormalizer extends BaseNormalizer
{
    /**
     * @param $obj
     * @param null $format
     * @param array $context
     * @return array|bool|float|int|string
     */
    public function normalize($obj, $format = null, array $context = [])
    {
        /**
         * @var DateTime $obj
         */
        $obj->setTimezone(new DateTimeZone("UTC"));

        return $obj->format("Y-m-d\TH:i:s.u\Z");
    }

    /**
     * @param $data
     * @param $type
     * @param null $format
     * @param array $context
     * @return array|bool|DateTime|\DateTimeImmutable|false|mixed|object
     */
    public function denormalize($data, $type, $format = null, array $context = [])
    {
        return new $type($data);
    }
}
