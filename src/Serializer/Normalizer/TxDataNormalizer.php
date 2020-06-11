<?php

/**
 * Copyright (c) 2018, TransChain.
 *
 * This source code is licensed under the Apache 2.0 license found in the
 * LICENSE file in the root directory of this source tree.
 */

namespace KatenaChain\Client\Serializer\Normalizer;

use KatenaChain\Client\Entity\Account\Account;
use KatenaChain\Client\Entity\Account\KeyCreateV1;
use KatenaChain\Client\Entity\Account\KeyRevokeV1;
use KatenaChain\Client\Entity\Account\KeyRotateV1;
use KatenaChain\Client\Entity\Certify\CertificateEd25519V1;
use KatenaChain\Client\Entity\Certify\CertificateRawV1;
use KatenaChain\Client\Entity\Certify\Certify;
use KatenaChain\Client\Entity\Certify\SecretNaclBoxV1;
use KatenaChain\Client\Entity\UnknownTxData;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class TxDataNormalizer extends ObjectNormalizer
{

    /**
     * @return array
     */
    public static function getAvailableTypes(): array
    {
        return [
            Certify::getCertificateRawV1Type()     => CertificateRawV1::class,
            Certify::getCertificateEd25519V1Type() => CertificateEd25519V1::class,
            Certify::getSecretNaclBoxV1Type()      => SecretNaclBoxV1::class,
            Account::getKeyCreateV1Type()          => KeyCreateV1::class,
            Account::getKeyRotateV1Type()          => KeyRotateV1::class,
            Account::getKeyRevokeV1Type()          => KeyRevokeV1::class,
        ];
    }

    /**
     * @param $obj
     * @param null $format
     * @param array $context
     * @return array|bool|float|int|mixed|string
     * @throws ExceptionInterface
     */
    public function normalize($obj, $format = null, array $context = [])
    {
        /**
         * @var TxDataNormalizable $obj
         */
        $value = parent::normalize($obj, $format, $context);
        if (is_array($value)) {
            ksort($value);
        }

        if ($obj instanceof UnknownTxData) {
            $value = $value["value"];
        }

        return [
            "type"  => $obj->getType(),
            "value" => $value
        ];
    }

    /**
     * @param $data
     * @param $type
     * @param null $format
     * @param array $context
     * @return array|object
     * @throws ExceptionInterface
     */
    public function denormalize($data, $type, $format = null, array $context = [])
    {
        if (is_subclass_of($type, TxDataNormalizable::class)
            && $data["type"] && array_key_exists($data["type"], self::getAvailableTypes())) {
            return parent::denormalize($data["value"], self::getAvailableTypes()[$data["type"]], $format, $context);
        }

        $unknownTxData = new UnknownTxData();
        return $unknownTxData->setType($data["type"])->setValue($data["value"]);
    }

    /**
     * @param $data
     * @param null $format
     * @return bool
     */
    public function supportsNormalization($data, $format = null)
    {
        return is_object($data) && $data instanceof TxDataNormalizable;
    }

    /**
     * @param $data
     * @param $type
     * @param null $format
     * @return bool
     */
    public function supportsDenormalization($data, $type, $format = null)
    {
        return (is_subclass_of($type, TxDataNormalizable::class));
    }
}
