<?php

namespace Redistats\tests\units\Server;

require_once __DIR__ . '/../../../../vendor/autoload.php';

use atoum;
use Redistats\Server\Storage as testedClass;

class Storage extends atoum {

    public function test__construct() {
        $this->object(testedClass::getInstance())->isInstanceOf('Redistats\Server\Storage');
    }
}