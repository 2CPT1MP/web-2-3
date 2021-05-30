<?php

class UrlParser {
    public static function parseRoute(string $routeUriStr): array {
        $splitPath = explode('/', $routeUriStr);
        array_shift($splitPath);
        return $splitPath;
    }

    public static function parseQueryParams(string $queryParamsStr): array {
        $parsedListOfParams = [];
        $listOfParams = explode('&', $queryParamsStr);

        foreach ($listOfParams as $value) {
            $pair = explode('=', $value);
            if (!isset($pair[0]) || !isset($pair[1]))
                continue;
            $parsedListOfParams[$pair[0]] = $pair[1];
        }
        return $parsedListOfParams;
    }

    public static function parseRequestUrl(string $requestUrl): array {
        $paramPos = strpos($requestUrl, '?');

        if (!$paramPos)
            return [UrlParser::parseRoute($requestUrl), null];

        $pathStr = substr($requestUrl, 0, $paramPos);
        $paramsStr = substr($requestUrl, $paramPos+1);

        $route = UrlParser::parseRoute($pathStr);
        $params = UrlParser::parseQueryParams($paramsStr);

        return [$route, $params];
    }
}
