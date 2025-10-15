<?php

namespace App\Helpers;

use Illuminate\Pagination\Cursor;
use Illuminate\Pagination\CursorPaginator;

class CursorPaginateHelper
{
    /**
     * Get to paginate array.
     *
     * @param CursorPaginator $paginator
     * @return array
     */

    public static function getPaginate(CursorPaginator $paginator): array
    {
        return [
            'path' => self::path($paginator),
            'per_page' => self::perPage($paginator),
            'next_cursor' => self::nextCursor($paginator),
            'next_page_url' => self::nextPageUrl($paginator),
            'prev_cursor' => self::previousCursor($paginator),
            'prev_page_url' => self::previousPageUrl($paginator),
        ];
    }

    /**
     * Get the current path.
     *
     * @param CursorPaginator $paginator
     * @return string
     */

    public static function path(CursorPaginator $paginator): string
    {
        return $paginator->path();
    }

    /**
     * Get the current per page.
     *
     * @param CursorPaginator $paginator
     * @return int
     */

    public static function perPage(CursorPaginator $paginator): int
    {
        return $paginator->perPage();
    }

    /**
     * Get the next cursor.
     *
     * @param CursorPaginator $paginator
     * @return string|null
     */
    public static function nextCursor(CursorPaginator $paginator): string|null
    {
        return $paginator->hasMorePages() ? $paginator->nextCursor()->encode() : null;
    }

    /**
     * Get the next page URL.
     *
     * @param CursorPaginator $paginator
     * @return string|null
     */
    public static function nextPageUrl(CursorPaginator $paginator): string|null
    {
        return $paginator->hasMorePages() ? $paginator->nextPageUrl() : null;
    }

    /**
     * Get the previous cursor.
     *
     * @param CursorPaginator $paginator
     * @return Cursor|null
     */
    public static function previousCursor(CursorPaginator $paginator): Cursor|null
    {
        return $paginator->hasMorePages() ? $paginator->previousCursor() : null;
    }

    /**
     * Get the previous page URL.
     *
     * @param CursorPaginator $paginator
     * @return string|null
     */
    public static function previousPageUrl(CursorPaginator $paginator): string|null
    {
        return $paginator->hasMorePages() ? $paginator->previousPageUrl() : null;
    }
}
