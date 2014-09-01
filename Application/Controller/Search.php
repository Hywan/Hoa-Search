<?php

namespace {

    from('Application')
        -> import('Controller.Generic')
        -> import('Library.Crawler.Hoa');

}

namespace Application\Controller {

class Search extends Generic {


    public function DefaultAction ( $language )  {

        if(empty($_POST['q'])) {
            $this->render(\Hoa\Http\Response::STATUS_BAD_REQUEST);

            return;
        }

        $query = $_POST['q'];
        $this->data = array_map(
            function( $row ) { unset($row['content']); return $row; },
            $this->search($language, $query)
        );

        $this->render();

        return;
    }

    private function search( $language, $query )  {

        $query = array(
            'bool' => array(
                'must' => array(
                    array('term' => array('lang' => self::$_languages[$language]['name'])),
                    array('query_string' => array('query' => $query))
                )
            )
        );

        return (new \Application\Library\ElasticSearch())->search($query);
    }
}

}
