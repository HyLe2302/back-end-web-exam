<?php

namespace App\Core;

use Illuminate\Database\Capsule\Manager as Capsule;

class Model
{
    protected static $capsule;

    public function __construct()
    {
        if (!self::$capsule) {
            $capsule = new Capsule;

            $capsule->addConnection([
                "driver" => env('DB_DRIVER', 'mysql'),
                "host" => env('DB_HOST', '127.0.0.1'),
                "database" => env('DB_DATABASE', 'recheck_exam'),
                "username" => env('DB_USERNAME', 'root'),
                "password" => env('DB_PASSWORD', ''),
                "port" => env('DB_PORT', 3306),
                "charset" => "utf8",
                "collation" => "utf8_unicode_ci",
                "prefix" => "",
            ]);

            // Cho phép dùng global như Laravel
            $capsule->setAsGlobal();

            // Bật Eloquent ORM
            $capsule->bootEloquent();

            self::$capsule = $capsule;
        }
    }
}