<?php
Require './DizzyLion.php';

$server = new SoapServer('./dizzylion.wsdl');

$server->setClass('DizzyLion');

$server->handle();