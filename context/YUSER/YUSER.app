<?php

global $app_desc, $action_desc;

$app_desc = array(
    "name" => "YUSER",
    "short_name" => "Yubico Users",
    "description" => "Yubico Users",
    "access_free" => "Y",
    "displayable" => "N",
    "with_frame" => "Y",
    "tag" => "SYSTEM"
);

$action_desc = array(
    array(
        "name" => "YLOGINFORM",
        "short_name" => "Login form",
        "root" => "Y",
        "layout" => "yloginform.html"
    )
);