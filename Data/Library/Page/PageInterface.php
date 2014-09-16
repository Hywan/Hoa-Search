<?php

namespace Page {

interface PageInterface {

    public function __construct( $link, \Symfony\Component\DomCrawler\Crawler $crawler );

}

}