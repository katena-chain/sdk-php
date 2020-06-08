<?php

/**
 * Copyright (c) 2018, TransChain.
 *
 * This source code is licensed under the Apache 2.0 license found in the
 * LICENSE file in the root directory of this source tree.
 */

namespace KatenaChain\Client\Entity\Account;

class Account
{
    const NAMESPACE             = "account";
    const TYPE_KEY              = "key";
    const DEFAULT_ROLE_ID       = "default";
    const COMPANY_ADMIN_ROLE_ID = "company_admin";
    const TYPE_CREATE           = "create";
    const TYPE_REVOKE           = "revoke";
    const TYPE_ROTATE           = "rotate";

    /**
     * @return string
     */
    public static function getKeyCreateV1Type(): string
    {
        return vsprintf("%s.%s.%s", [self::getKeyIdKey(), self::TYPE_CREATE, "v1"]);
    }

    /**
     * @return string
     */
    public static function getKeyRevokeV1Type(): string
    {
        return vsprintf("%s.%s.%s", [self::getKeyIdKey(), self::TYPE_REVOKE, "v1"]);
    }

    /**
     * @return string
     */
    public static function getKeyRotateV1Type(): string
    {
        return vsprintf("%s.%s.%s", [self::getKeyIdKey(), self::TYPE_ROTATE, "v1"]);
    }

    /**
     * @return string
     */
    public static function getKeyIdKey(): string
    {
        return vsprintf("%s.%s", [self::NAMESPACE, self::TYPE_KEY]);
    }
}
