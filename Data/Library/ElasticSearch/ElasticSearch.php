<?php

namespace ElasticSearch {

class ElasticSearch {

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
        $params['body']['query'] = $query;

        $ESresults = $this->client->search($params);
        if(!empty($ESresults['hits']['hits'])) {
            foreach ($ESresults['hits']['hits'] as $result) {
                $results[] = $result['_source'];
            }
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
                            'en_snowball' => [
                                'type' => 'snowball',
                                'language' => 'English'
                            ],
                            'worddelimiter' => [
                                'type' => 'word_delimiter'
                            ],
                            'en_stopwords' => [
                                'type' => 'stop',
                                'stopwords' => [ '_english' ],
                                'ignore_case' => true
                            ]
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
                                'tokenizer' => 'nGram',
                                'filter' => [
                                    'en_stopwords',
                                    'asciifolding',
                                    'lowercase',
                                    'en_snowball',
                                    'worddelimiter'
                                ]
                            ],
                            'default_search' => [
                                'type' => 'custom',
                                'tokenizer' => 'standard',
                                'filter' => [
                                    'en_stopwords',
                                    'asciifolding',
                                    'lowercase',
                                    'en_snowball',
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