<?php

require_once dirname(dirname(__DIR__)) .
    DIRECTORY_SEPARATOR . 'Data' .
    DIRECTORY_SEPARATOR . 'Core.link.php';

require_once dirname(dirname(__DIR__)) .
    DIRECTORY_SEPARATOR . 'vendor' .
    DIRECTORY_SEPARATOR . 'autoload.php';

\Hoa\Core::enableErrorHandler();
\Hoa\Core::enableExceptionHandler();

from('Hoa')
-> import('Console.Cursor')
;

from('Data')
-> import('Library.ElasticSearch.~')
-> import('Library.Crawler.Hoa')
-> import('Library.Page.*')
;

$elasticSearch = new \ElasticSearch\ElasticSearch();

$elasticSearch->init();