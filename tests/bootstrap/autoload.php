<?php

passthru("php artisan migrate:refresh --force");

require __DIR__ . '/../../bootstrap/autoload.php';