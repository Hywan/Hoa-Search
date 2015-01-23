<?php

namespace Application\Controller;

use Hoa\Dispatcher;
use Hoa\Http;

class Generic extends Dispatcher\Kit {

    protected static $_languages = [
        'en' => ['name' => 'english'],
        'fr' => ['name' => 'french']
    ];

    public function render ( $status = Http\Response::STATUS_OK ) {

        $response = new Http\Response();
        $response->sendStatus($status);
        $response->sendHeader('Content-Type', 'application/json');
        $response->writeAll(json_encode($this->data));

        exit(0);
    }

    public static function isLanguageAllowed ( $language ) {

        return true === array_key_exists($language, static::$_languages);
    }
}
