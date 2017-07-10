<?php

use allejo\Wufoo\WufooForm;
use VCR\VCR;

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

VCR::turnOn();
VCR::turnOff();

WufooForm::configureApi('fishbowl', 'AOI6-LFKL-VM1Q-IEX9');
