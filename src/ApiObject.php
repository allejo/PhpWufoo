<?php

namespace allejo\Wufoo;

use GuzzleHttp\Client;

/**
 * @internal
 */
abstract class ApiObject
{
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
    protected static function interpolate ($message, array $context)
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
}
