<?php

/**
 * Copyright 2010 - 2015, Cake Development Corporation (http://cakedc.com)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright 2010 - 2015, Cake Development Corporation (http://cakedc.com)
 * @license MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
use Cake\Core\Configure;


$config = [
    'Calendar' => [

        'clientSecret' => [

            "web" => [

                "client_id" => "229082176425-7gj807o0a2mmhde3gucfqdcdlh18nmuo.apps.googleusercontent.com",

                "project_id" =>"coach-155204",

                "auth_uri" => "https://accounts.google.com/o/oauth2/auth",

                "token_uri" => "https://accounts.google.com/o/oauth2/token",

                "auth_provider_x509_cert_url" => "https://www.googleapis.com/oauth2/v1/certs",

                "client_secret" => "V5hGmt9WzXViiAfnZ10JTbKm",

                "redirect_uris" => ["http://localhost:8765/calendars/save-token","http://localhost:8765/calendars/saveToken"]
            ]
        ],

        'defaultAccount' => [

            "access_token" => "ya29.Ci_WA_4C0ubape2-TeFYRjx9JaFhviwsH6bf80x4R1XxIkTQNQbnL83ra9QfzQOPQQ",

            "token_type" => "Bearer",

            "expires_in" => 3600,

            "refresh_token" => "1/9y-KFgWT8sHWvC_74LuNmgFac_1ohlZV-lx0vh-aXXE",

            "created" => 1484631996
        ]
    ]
];

return $config;
