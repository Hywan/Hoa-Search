<?php

namespace Application\Controller {

use Hoa\Http;
use Elasticsearch\Elasticsearch;

class Search extends Generic {

    public function DefaultAction ( $language = 'en' )  {

        if(empty($_GET['q']) && empty($_POST['q'])) {

            $this->render(Http\Response::STATUS_BAD_REQUEST);

            return;
        }

        $language = strtolower($language);
        $query = empty($_POST['q']) ? $_GET['q'] : $_POST['q'];

        $this->data = array_map(
            function( $row ) { unset($row['content']); return $row; },
            $this->search($language, $query)
        );

        $this->render();

        return;
    }

    private function search( $language, $query )  {

        $query =
        [
            'query' => [
                'filtered' => [
                    'query' => [
                        'match' => ['_all' => $query]
                    ],
                    'filter' => [
                        'term' => ['lang' => self::$_languages[$language]['name']]
                    ]
                ]
            ]
        ];

        return (new Elasticsearch())->search($query);
    }
}

}
