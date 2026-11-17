<?php
return [
    'username' => getenv('MORRINS_SMTP_USERNAME') ?: '',
    'app_password' => getenv('MORRINS_SMTP_PASSWORD') ?: '',
];
