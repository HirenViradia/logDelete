<?php

require_once 'vendor/autoload.php';

use \Ebl\Logs\LogsDelete;

$data = new LogsDelete();
return $data->logDelete('/var/www/html/iot-devices/logs/', 'size', 1024);
