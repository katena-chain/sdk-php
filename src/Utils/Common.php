<?php

/**
 * Copyright (c) 2018, TransChain.
 *
 * This source code is licensed under the Apache 2.0 license found in the
 * LICENSE file in the root directory of this source tree.
 */

namespace KatenaChain\Client\Utils;

class Common
{
    const PAGE_PARAM             = "page";
    const PER_PAGE_PARAM         = "per_page";
    const DEFAULT_PER_PAGE_PARAM = 10;

    /**
     * joins the base path and paths array and adds the query values to return a new uri.
     * @param string $basePath
     * @param array $paths
     * @param array $queryValues
     * @return string
     */
    public static function getUri(string $basePath, array $paths, array $queryValues = []): string
    {
        array_walk($paths, function (&$path) {
            $path = trim($path, '/');
        });

        $fullUrl = $paths;
        array_unshift($fullUrl, rtrim($basePath, '/'));

        $fullUrl = implode('/', $fullUrl);

        if ($queryValues) {
            $fullUrl .= '?' . http_build_query($queryValues);
        }

        return $fullUrl;
    }

    /**
     * returns the query params array to request a pagination.
     * @param int $page
     * @param int $txPerPage
     * @return array
     */
    public static function getPaginationQueryParams(int $page, int $txPerPage): array
    {
        // TODO: Cast to string ?
        return array(
            self::PAGE_PARAM     => (string)$page,
            self::PER_PAGE_PARAM => (string)$txPerPage
        );
    }

    /**
     * concatenates a company bcid and a uuid into a fully qualified id.
     * @param string $companyBcId
     * @param string $uuid
     * @return string
     */
    public static function concatFqId(string $companyBcId, string $uuid): string
    {
        return vsprintf("%s-%s", [$companyBcId, $uuid]);
    }

}
