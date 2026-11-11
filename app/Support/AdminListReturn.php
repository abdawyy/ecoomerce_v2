<?php

namespace App\Support;

class AdminListReturn
{
    public static function isSafe(?string $url): bool
    {
        if (! is_string($url) || $url === '') {
            return false;
        }

        $path = parse_url($url, PHP_URL_PATH) ?: '';
        if (! str_starts_with($path, '/admin')) {
            return false;
        }

        $host = parse_url($url, PHP_URL_HOST);
        if ($host && ! in_array($host, [parse_url((string) config('app.url'), PHP_URL_HOST), request()->getHost()], true)) {
            return false;
        }

        return true;
    }

    public static function remembered(string $listKey): ?string
    {
        $url = session('admin.list_url.'.$listKey);

        return self::isSafe(is_string($url) ? $url : null) ? $url : null;
    }

    public static function fieldValue(string $listKey): string
    {
        $fromRequest = request('return');
        if (self::isSafe(is_string($fromRequest) ? $fromRequest : null)) {
            return $fromRequest;
        }

        return self::remembered($listKey) ?? '';
    }

    public static function addUrl(string $route, string $listKey, array $query = []): string
    {
        $url = route($route);
        $return = self::remembered($listKey);
        if ($return) {
            $query['return'] = $return;
        }

        return $query === [] ? $url : $url.'?'.http_build_query($query);
    }

    public static function withoutPage(string $url): string
    {
        $fragment = '';
        if (str_contains($url, '#')) {
            [$url, $fragment] = explode('#', $url, 2);
            $fragment = '#'.$fragment;
        }

        $parts = parse_url($url);
        if (! is_array($parts)) {
            return $url.$fragment;
        }

        parse_str($parts['query'] ?? '', $query);
        unset($query['page']);

        $rebuilt = ($parts['scheme'] ?? null)
            ? ($parts['scheme'].'://'.($parts['host'] ?? ''))
            : '';
        if (! empty($parts['port'])) {
            $rebuilt .= ':'.$parts['port'];
        }
        $rebuilt .= $parts['path'] ?? '';
        if ($query !== []) {
            $rebuilt .= '?'.http_build_query($query);
        }

        return $rebuilt.$fragment;
    }
}
