<?php

namespace allejo\Wufoo;

use GuzzleHttp\Client;

/**
 * @internal
 */
abstract class ApiObject
{
    /** @var string */
    protected $id;

    /** @var string */
    protected static $apiKey;

    /** @var string */
    protected static $subdomain;

    /** @var Client */
    protected static $client;

    /**
     * @param string $subdomain The subdomain for your Wufoo account
     * @param string $key       The API key used to access this information
     */
    public static function configureApi($subdomain, $key)
    {
        self::$apiKey = $key;
        self::$subdomain = $subdomain;
        self::$client = new Client([
            'auth' => [
                self::$apiKey,
                'PhpWufoo' // Wufoo doesn't check the password, it just cares about the username being the API key
            ]
        ]);
    }

    protected function buildUrl($url)
    {
        return self::interpolate($url, [
            'subdomain' => self::$subdomain,
            'identifier' => $this->id,
        ]);
    }

    /**
     * Sanitize an array of query parameters to prepare the array to be converted into a query string.
     *
     * @param array $parameters
     */
    protected function prepareQueryParameters(array $parameters)
    {
        foreach ($parameters as $parameter => $value)
        {
            // Remove any null values so they won't appear in the query
            if ($value === null)
            {
                unset($parameters[$parameter]);
            }
        }
    }

    /**
     * Build a query string from an array.
     *
     * @param array $parameters
     *
     * @return string A query string ready for HTTP requests
     */
    protected function buildQuery(array $parameters)
    {
        return urldecode(http_build_query($parameters));
    }

    /**
     * Interpolates context values into the message placeholders.
     *
     * @author PHP Framework Interoperability Group
     *
     * @param string $message
     * @param array  $context
     *
     * @return string
     */
    protected static function interpolate($message, array $context)
    {
        // build a replacement array with braces around the context keys
        $replace = array();
        foreach ($context as $key => $val) {
            if (!is_array($val) && (!is_object($val) || method_exists($val, '__toString'))) {
                $replace[sprintf('{%s}', $key)] = $val;
            }
        }
        // interpolate replacement values into the message and return
        return strtr($message, $replace);
    }

    protected static function setIfNotNull(array &$query, $key, &$checkNull)
    {
        if ($checkNull != null)
        {
            $query[$key] = $checkNull;
        }
    }
}
