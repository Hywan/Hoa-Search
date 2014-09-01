<?php

namespace {

from('Hoa')
-> import('Dispatcher.Kit')
-> import('File.Read')
-> import('Http.Response.~')
-> import('Json.~');

from('Application')
-> import('Model.Visitor')
-> import('Controller.Exception');

}

namespace Application\Controller {

class Generic extends \Hoa\Dispatcher\Kit {

    protected static $_languages = array(
        'en' => array(
            'name'    => 'english'
        ),
        'fr' => array(
            'name'    => 'french'
        )
    );

    public function construct ( ) {

        return;
    }

    public function render ( $status = \Hoa\Http\Response::STATUS_OK ) {

        $response = new \Hoa\Http\Response();
        $response->sendStatus($status);

        echo json_encode($this->data);
        exit(0);
    }

    public static function isLanguageAllowed ( $language ) {

        return true === array_key_exists($language, static::$_languages);
    }

}

}
