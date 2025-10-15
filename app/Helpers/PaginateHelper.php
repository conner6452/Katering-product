<?php

namespace App\Helpers;

use Illuminate\Pagination\LengthAwarePaginator;

class PaginateHelper
{
    /**
     * Get to paginate array.
     *
     * @param LengthAwarePaginator $paginator
     * @return array
     */
    public static function getPaginate(LengthAwarePaginator $paginator): array
    {
        return [
            'path' => self::path($paginator),
            'per_page' => self::perPage($paginator),
            'current_page' => self::currentPage($paginator),
            'total' => self::total($paginator),
            'last_page' => self::lastPage($paginator),
            'next_page_url' => self::nextPageUrl($paginator),
            'prev_page_url' => self::previousPageUrl($paginator),
            'from' => self::from($paginator),
            'to' => self::to($paginator),
        ];
    }

    /**
     * Get the current path.
     *
     * @param LengthAwarePaginator $paginator
     * @return string
     */
    public static function path(LengthAwarePaginator $paginator): string
    {
        return $paginator->path();
    }

    /**
     * Get the current per page.
     *
     * @param LengthAwarePaginator $paginator
     * @return int
     */
    public static function perPage(LengthAwarePaginator $paginator): int
    {
        return $paginator->perPage();
    }

    /**
     * Get the current page.
     *
     * @param LengthAwarePaginator $paginator
     * @return int
     */
    public static function currentPage(LengthAwarePaginator $paginator): int
    {
        return $paginator->currentPage();
    }

    /**
     * Get the total number of items.
     *
     * @param LengthAwarePaginator $paginator
     * @return int
     */
    public static function total(LengthAwarePaginator $paginator): int
    {
        return $paginator->total();
    }

    /**
     * Get the last page number.
     *
     * @param LengthAwarePaginator $paginator
     * @return int
     */
    public static function lastPage(LengthAwarePaginator $paginator): int
    {
        return $paginator->lastPage();
    }

    /**
     * Get the next page URL.
     *
     * @param LengthAwarePaginator $paginator
     * @return string|null
     */
    public static function nextPageUrl(LengthAwarePaginator $paginator): string|null
    {
        return $paginator->nextPageUrl();
    }

    /**
     * Get the previous page URL.
     *
     * @param LengthAwarePaginator $paginator
     * @return string|null
     */
    public static function previousPageUrl(LengthAwarePaginator $paginator): string|null
    {
        return $paginator->previousPageUrl();
    }

    /**
     * Get the starting item number of the current page.
     *
     * @param LengthAwarePaginator $paginator
     * @return int|null
     */
    public static function from(LengthAwarePaginator $paginator): int|null
    {
        return $paginator->firstItem();
    }

    /**
     * Get the ending item number of the current page.
     *
     * @param LengthAwarePaginator $paginator
     * @return int|null
     */
    public static function to(LengthAwarePaginator $paginator): int|null
    {
        return $paginator->lastItem();
    }
}
