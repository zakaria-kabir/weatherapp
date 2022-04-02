<?php
$names = json_decode(file_get_contents("http://country.io/names.json"), true);
date_default_timezone_set('UTC');