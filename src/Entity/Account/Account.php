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

    /**
     * @return string
     */
    public static function getCategoryKeyCreate(): string
    {
        return vsprintf("%s.%s.%s", [self::NAMESPACE, self::TYPE_KEY, self::TYPE_CREATE]);
    }

    /**
     * @return string
     */
    public static function getCategoryKeyRevoke(): string
    {
        return vsprintf("%s.%s.%s", [self::NAMESPACE, self::TYPE_KEY, self::TYPE_REVOKE]);
    }

    /**
     * @return string
     */
    public static function getTypeKeyCreateV1(): string
    {
        return vsprintf("%s.%s", [self::getCategoryKeyCreate(), "v1"]);
    }

    /**
     * @return string
     */
    public static function getTypeKeyRevokeV1(): string
    {
        return vsprintf("%s.%s", [self::getCategoryKeyRevoke(), "v1"]);
    }
}
