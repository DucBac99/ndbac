<?php
$langs = [];

// US English
$langs[] = [
    "code" => "en-US",
    "shortcode" => "en",
    "name" => "English",
    "localname" => "English"
];

// Vietnamese
$langs[] = [
    "code" => "vi-VN",
    "shortcode" => "vi",
    "name" => "Vietnamese",
    "localname" => "Vietnam"
];

Config::set("applangs", $langs);
Config::set("default_applang", "en-US");
