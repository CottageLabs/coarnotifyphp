<?php

namespace Tests\fixtures;

class URIFixtureFactory
{
    public static function generate($schemes = null, $hosts = null, $ports = null, $paths = null, $queries = null, $fragments = null)
    {
        $schemes = $schemes !== null ? $schemes : self::deepCopy(self::DEFAULT_SCHEMES);
        $hosts = $hosts !== null ? $hosts : self::deepCopy(self::DEFAULT_HOSTS);
        $ports = $ports !== null ? $ports : self::deepCopy(self::DEFAULT_PORTS);
        $paths = $paths !== null ? $paths : self::deepCopy(self::DEFAULT_PATHS);
        $queries = $queries !== null ? $queries : self::deepCopy(self::DEFAULT_QUERIES);
        $fragments = $fragments !== null ? $fragments : self::deepCopy(self::DEFAULT_FRAGMENTS);

        $uris = [];
        foreach ($schemes as $scheme) {
            foreach ($hosts as $host) {
                foreach ($ports as $port) {
                    foreach ($paths as $path) {
                        foreach ($queries as $query) {
                            foreach ($fragments as $fragment) {
                                $uris[] = self::generateUri($scheme, $host, $port, $path, $query, $fragment);
                            }
                        }
                    }
                }
            }
        }

        return $uris;
    }

    public static function generateUri($scheme, $host, $port, $path, $query, $fragment)
    {
        if ($host !== null && strpos($host, ":") !== false && $port !== null && $port !== "") {
            $host = "[$host]";
        }
        $url = $scheme !== null && $scheme !== "" ? $scheme . "://" : "";
        $url .= $host !== null ? $host : "";
        $url .= $port !== null && $port !== "" ? ":" . $port : "";
        $url .= $path !== null ? $path : "";
        $url .= $query !== null && $query !== "" ? "?" . $query : "";
        $url .= $fragment !== null && $fragment !== "" ? "#" . $fragment : "";
        return $url;
    }

    private static function deepCopy($array)
    {
        return json_decode(json_encode($array), true);
    }

    private const DEFAULT_SCHEMES = [
        "http",
        "https"
    ];

    private const DEFAULT_HOSTS = [
        "example.com",
        "localhost",
        "192.168.0.1",
        "2001:db8::7"
    ];

    private const DEFAULT_PORTS = [
        "",
        "80",
        "8080"
    ];

    private const DEFAULT_PATHS = [
        "",
        "/",
        "/path",
        "/path/to/file"
    ];

    private const DEFAULT_QUERIES = [
        "",
        "query",
        "query=string&o=1",
    ];

    private const DEFAULT_FRAGMENTS = [
        "",
        "fragment"
    ];
}
