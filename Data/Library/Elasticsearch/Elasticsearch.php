<?php

namespace Elasticsearch {

class Elasticsearch {

    const HOST  = '188.165.240.124:9200';
    const INDEX = 'hoa-project';
    const TYPE  = 'page';

    protected $client;

    public function __construct( ) {

        $this->client = new \Elasticsearch\Client(array('hosts' => array(self::HOST)));
    }

    public function addPage( $page, $id ) {

        $document = array();
        $document['id']    = $id;
        $document['index'] = self::INDEX;
        $document['type']  = self::TYPE;
        $document['body']  = $page;

        $this->client->index($document);
    }

    public function search( $query ) {

        $results = array();

        $params = array();
        $params['index'] = self::INDEX;
        $params['type']  = self::TYPE;
        $params['body']  = $query;

        $ESresults = $this->client->search($params);
        if(!empty($ESresults['hits']['hits'])) {
            $results = array_column($ESresults['hits']['hits'], '_source');
        }

        return $results;
    }

    public function init( ) {

        $params = [
            'index' => self::INDEX,
            'body' => [
                'settings' => [
                    'number_of_shards' => 2,
                    'number_of_replicas' => 1,
                    'analysis' => [
                        'filter' => [
                            'worddelimiter' => [
                                'type' => 'word_delimiter'
                            ],
                        ],
                        'tokenizer' => [
                            'nGram' => [
                                'type' => 'nGram',
                                'min_gram' => 3,
                                'max_gram' => 20,
                            ]
                        ],
                        'analyzer' => [
                            'default_index' => [
                                'type' => 'custom',
                                'char_filter' => ['html_strip'],
                                'tokenizer' => 'nGram',
                                'filter' => [
                                    'asciifolding',
                                    'lowercase',
                                    'worddelimiter'
                                ]
                            ],
                            'default_search' => [
                                'type' => 'custom',
                                'tokenizer' => 'standard',
                                'filter' => [
                                    'asciifolding',
                                    'lowercase',
                                    'worddelimiter'
                                ]
                            ],
                        ]
                    ]
                ]
            ]
        ];

        $this->client->indices()->create($params);
    }
}

}