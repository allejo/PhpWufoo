<?php

namespace
{
    use allejo\Wufoo\WufooForm;

    if (!file_exists(__DIR__ . "/../vendor/autoload.php"))
    {
        die(
            "\n[ERROR] You need to run composer before running the test suite.\n".
            "To do so run the following commands:\n".
            "    curl -s http://getcomposer.org/installer | php\n".
            "    php composer.phar install\n\n"
        );
    }

    require_once __DIR__ . '/../vendor/autoload.php';

    WufooForm::configureApi('fishbowl', 'AOI6-LFKL-VM1Q-IEX9');
}

namespace GuzzleHttp\Handler
{
    function curl_setopt_array($handle, array $options)
    {
        $options[CURLOPT_SSL_VERIFYHOST] = false;
        $options[CURLOPT_SSL_VERIFYPEER] = false;

        \curl_setopt_array($handle, $options);
    }
}
