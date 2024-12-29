<?php

return array(
    "driver" => "smtp",
    "host" => "app.debugmail.io",
    "port" => "25",
    "from" => array(
        "address" => "john.doe@example.org",
        "name" => "John Doe"
    ),
    "encryption" => "tls",
    "username" => "f8112924-9ab7-40a9-84a5-8d5035c42d4a",
    "password" => "2c7e5ee9-c348-4d8a-bd69-b6df892ed877",
    "sendmail" => "/usr/sbin/sendmail -bs",
    "pretend" => false
);
