<?php

namespace coarnotify\validate;

use coarnotify\exceptions\ValueError;

#############################################
## URI validator

const URI_RE = '~^(([^:/?#]+):)?(//([^/?#]*))?([^?#]*)(\?([^#]*))?(#(.*))?~';
const SCHEME = '/^[a-zA-Z][a-zA-Z0-9+\-.]*$/';
const IPv6 = '/(?:^|(?<=\s))\[{0,1}(([0-9a-fA-F]{1,4}:){7,7}[0-9a-fA-F]{1,4}|([0-9a-fA-F]{1,4}:){1,7}:|([0-9a-fA-F]{1,4}:){1,6}:[0-9a-fA-F]{1,4}|([0-9a-fA-F]{1,4}:){1,5}(:[0-9a-fA-F]{1,4}){1,2}|([0-9a-fA-F]{1,4}:){1,4}(:[0-9a-fA-F]{1,4}){1,3}|([0-9a-fA-F]{1,4}:){1,3}(:[0-9a-fA-F]{1,4}){1,4}|([0-9a-fA-F]{1,4}:){1,2}(:[0-9a-fA-F]{1,4}){1,5}|[0-9a-fA-F]{1,4}:((:[0-9a-fA-F]{1,4}){1,6})|:((:[0-9a-fA-F]{1,4}){1,7}|:)|fe80:(:[0-9a-fA-F]{0,4}){0,4}%[0-9a-zA-Z]{1,}|::(ffff(:0{1,4}){0,1}:){0,1}((25[0-5]|(2[0-4]|1{0,1}[0-9]){0,1}[0-9])\.){3,3}(25[0-5]|(2[0-4]|1{0,1}[0-9]){0,1}[0-9])|([0-9a-fA-F]{1,4}:){1,4}:((25[0-5]|(2[0-4]|1{0,1}[0-9]){0,1}[0-9])\.){3,3}(25[0-5]|(2[0-4]|1{0,1}[0-9]){0,1}[0-9]))]{0,1}(?=\s|$)/';
const HOSTPORT = '/^(?:(?:[A-Z0-9](?:[A-Z0-9-]{0,61}[A-Z0-9])?\.)+(?:[A-Z]{2,6}\.?|[A-Z0-9-]{2,}\.?)|localhost|\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}|(?:^|(?<=\s))(([0-9a-fA-F]{1,4}:){7,7}[0-9a-fA-F]{1,4}|([0-9a-fA-F]{1,4}:){1,7}:|([0-9a-fA-F]{1,4}:){1,6}:[0-9a-fA-F]{1,4}|([0-9a-fA-F]{1,4}:){1,5}(:[0-9a-fA-F]{1,4}){1,2}|([0-9a-fA-F]{1,4}:){1,4}(:[0-9a-fA-F]{1,4}){1,3}|([0-9a-fA-F]{1,4}:){1,3}(:[0-9a-fA-F]{1,4}){1,4}|([0-9a-fA-F]{1,4}:){1,2}(:[0-9a-fA-F]{1,4}){1,5}|[0-9a-fA-F]{1,4}:((:[0-9a-fA-F]{1,4}){1,6})|:((:[0-9a-fA-F]{1,4}){1,7}|:)|fe80:(:[0-9a-fA-F]{0,4}){0,4}%[0-9a-zA-Z]{1,}|::(ffff(:0{1,4}){0,1}:){0,1}((25[0-5]|(2[0-4]|1{0,1}[0-9]){0,1}[0-9])\.){3,3}(25[0-5]|(2[0-4]|1{0,1}[0-9]){0,1}[0-9])|([0-9a-fA-F]{1,4}:){1,4}:((25[0-5]|(2[0-4]|1{0,1}[0-9]){0,1}[0-9])\.){3,3}(25[0-5]|(2[0-4]|1{0,1}[0-9]){0,1}[0-9]))(?=\s|$))(?::\d+)?$/i';
const MARK = "-_.!~*'()";
const UNRESERVED = "a-zA-Z0-9" . MARK;
const PCHARS = UNRESERVED . ":@&=+$," . "%/;";
const PATH = "#^\/{0,1}[" . PCHARS . "]*$#";
const RESERVED = ";/?:@&=+$,";
const URIC = RESERVED . UNRESERVED . "%";
const FREE = "#^[" . URIC . "]+$#";
const USERINFO = "/^[" . UNRESERVED . "%;:&=+$,]*$/";

class Validators
{
    private static function getMatch($m, $index)
    {
        $matches = count($m);
        if ($matches < $index) {
            return null;
        }
        $val = $m[$index] ?? null;
        if ($val === "") {
            $val = null;
        }
        return $val;
    }

    /**
     * Validate that the given string is an absolute URI
     *
     * @param $obj
     * @param $uri
     * @return true
     * @throws ValueError
     */
    public static function absolueURI($obj, $uri)
    {
        if (!preg_match(URI_RE, $uri, $m)) {
            throw new ValueError("Invalid URI");
        }

        if (empty($m[2])) {
            throw new ValueError("URI requires a scheme (this may be a relative rather than absolute URI)");
        }

        $scheme = Validators::getMatch($m, 2);
        $authority = Validators::getMatch($m, 4);
        $path = Validators::getMatch($m, 5);
        $query = Validators::getMatch($m, 7);
        $fragment = Validators::getMatch($m, 9);

        if ($scheme !== null && !preg_match(SCHEME, $scheme)) {
            throw new ValueError("Invalid URI scheme `{$scheme}`");
        }

        if ($authority !== null) {
            $userinfo = null;
            $hostport = $authority;
            if (strpos($authority, "@") !== false) {
                list($userinfo, $hostport) = explode("@", $authority, 2);
            }
            if ($userinfo !== null && !preg_match(USERINFO, $userinfo)) {
                throw new ValueError("Invalid URI authority `{$authority}`");
            }
            if (strpos($hostport, "[") === 0) {
                $port_separator = strrpos($hostport, "]:");
                $port = null;
                if ($port_separator !== false) {
                    $port = substr($hostport, $port_separator + 2);
                    $host = substr($hostport, 1, $port_separator - 1);
                } else {
                    $host = substr($hostport, 1, -1);
                }
                if (!preg_match(IPv6, $host)) {
                    throw new ValueError("Invalid URI authority `{$authority}`");
                }
                if ($port !== null && !is_numeric($port)) {
                    throw new ValueError("Invalid URI port `{$port}`");
                }
            } else {
                if (!preg_match(HOSTPORT, $hostport)) {
                    throw new ValueError("Invalid URI authority `{$authority}`");
                }
            }
        }

        if ($path !== null && !preg_match(PATH, $path)) {
            throw new ValueError("Invalid URI path `{$path}`");
        }

        if ($query !== null && !preg_match(FREE, $query)) {
            throw new ValueError("Invalid URI query `{$query}`");
        }

        if ($fragment !== null && !preg_match(FREE, $fragment)) {
            throw new ValueError("Invalid URI fragment `{$fragment}`");
        }

        return true;
    }

    /**
     * Validate that the given string is an absolute HTTP URI (i.e. a URL)
     *
     * @param $obj
     * @param $url
     * @return true
     * @throws ValueError
     */
    public static function url($obj, $url)
    {
        Validators::absolueURI($obj, $url);
        $o = parse_url($url);
        if (!in_array($o['scheme'], ["http", "https"])) {
            throw new ValueError("URL scheme must be http or https");
        }
        if (empty($o['host'])) {
            throw new ValueError("Does not appear to be a valid URL");
        }
        return true;
    }

    /**
     * Closure that returns a validation function that checks that the value is one of the given values
     *
     * @param array $values
     * @return \Closure
     */
    public static function oneOf(array $values)
    {
        return function ($obj, $x) use ($values) {
            if (!in_array($x, $values)) {
                throw new ValueError("`{$x}` is not one of the valid values: " . implode(", ", $values));
            }
            return true;
        };
    }

    /**
     * Closure that returns a validation function that checks that a list of values contains at least one
     * of the given values
     *
     * @param array $values
     * @return \Closure
     */
    public static function atLeastOneOf(array $values)
    {
        return function ($obj, $x) use ($values) {
            if (!is_array($x)) {
                $x = [$x];
            }

            foreach ($x as $entry) {
                if (in_array($entry, $values)) {
                    return true;
                }
            }

            throw new ValueError("`" . implode(", ", $x) . "` is not one of the valid values: " . implode(", ", $values));
        };
    }

    /**
     * Closure that returns a validation function that checks the provided values contain the required value
     *
     * @param $value
     * @return \Closure
     */
    public static function contains($value)
    {
        $values = is_array($value) ? $value : [$value];
        $values = array_flip($values);

        return function ($obj, $x) use ($values) {
            if (!is_array($x)) {
                $x = [$x];
            }
            $x = array_flip($x);

            $intersection = array_intersect_key($x, $values);
            if (count($intersection) !== count($values)) {
                throw new ValueError("`" . implode(", ", array_keys($x)) . "` does not contain the required value(s): " . implode(", ", array_keys($values)));
            }
            return true;
        };
    }

    /**
     * Validate that the given value is of the correct type for the object.  The exact behaviour of this function
     * depends on the object provided:
     *
     *  * If the object has an ``ALLOWED_TYPES`` attribute which is not an empty list, then the value must be one of the types in that list
     *  * If the object has a ``TYPE`` attribute, then the value must be, or contain, that type
     *  * In all other cases, type validation will succeed
     *
     * @param $obj
     * @param $value
     * @return true
     */
    public static function typeChecker($obj, $value)
    {
        if (property_exists($obj, "ALLOWED_TYPES")) {
            $allowed = $obj->ALLOWED_TYPES;
            if (empty($allowed)) {
                return true;
            }
            $validator = Validators::oneOf($allowed);
            $validator($obj, $value);
        } elseif (property_exists($obj, "TYPE")) {
            $ty = $obj->TYPE;
            $validator = Validators::contains($ty);
            $validator($obj, $value);
        }
        return true;
    }
}