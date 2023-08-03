# ElasticsearchDSL

Introducing Elasticsearch DSL library to provide objective query builder for [elasticsearch-php](https://github.com/elastic/elasticsearch-php) client. You can easily build any Elasticsearch query and transform it to an array.

[![Build Status](https://travis-ci.org/ongr-io/ElasticsearchDSL.svg?branch=master)](https://travis-ci.org/ongr-io/ElasticsearchDSL)
[![codecov](https://codecov.io/gh/ongr-io/ElasticsearchDSL/branch/master/graph/badge.svg)](https://codecov.io/gh/ongr-io/ElasticsearchDSL)
[![Latest Stable Version](https://poser.pugx.org/ongr/elasticsearch-dsl/v/stable)](https://packagist.org/packages/ongr/elasticsearch-dsl)
[![Total Downloads](https://poser.pugx.org/ongr/elasticsearch-dsl/downloads)](https://packagist.org/packages/ongr/elasticsearch-dsl)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/ongr-io/ElasticsearchDSL/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/ongr-io/ElasticsearchDSL/?branch=master)


If you like this library, help me to develop it by buying a cup of coffee

<a href="https://www.buymeacoffee.com/zIKBXRc" target="_blank"><img src="https://www.buymeacoffee.com/assets/img/custom_images/orange_img.png" alt="Buy Me A Coffee" style="height: 41px !important;width: 174px !important;box-shadow: 0px 3px 2px 0px rgba(190, 190, 190, 0.5) !important;-webkit-box-shadow: 0px 3px 2px 0px rgba(190, 190, 190, 0.5) !important;" ></a>

## Version matrix

| Elasticsearch version | ElasticsearchDSL version |
|-----------------------|--------------------------|
| >= 8.0                | >= 8.0                   |
| >= 7.0                | >= 7.0                   |
| >= 6.0, < 7.0         | >= 6.0                   |
| >= 5.0, < 6.0         | >= 5.0                   |
| >= 2.0, < 5.0         | >= 2.0 (not supported)   |
| >= 1.0, < 2.0         | 1.x (not supported)      |
| <= 0.90.x             | not supported            |

## Documentation

[The online documentation of the bundle is here](docs/index.md)

## Try it!

### Installation

Install library with [composer](https://getcomposer.org):

```bash
$ composer require bistrosk/elasticsearch-dsl
```

> [elasticsearch-php](https://github.com/elastic/elasticsearch-php) client is defined in the composer requirements, no need to install it.

### Search

The library is standalone and is not coupled with any framework. You can use it in any PHP project, the only
requirement is composer.  Here's the example:

Create search:

```php
 <?php
  require 'vendor/autoload.php'; //Composer autoload

  $client = ClientBuilder::create()->build(); //elasticsearch-php client
  
  $matchAll = new ONGR\ElasticsearchDSL\Query\MatchAllQuery();
  
  $search = new ONGR\ElasticsearchDSL\Search();
  $search->addQuery($matchAll);
  
  $params = [
    'index' => 'your_index',
    'body' => $search->toArray(),
  ];
  
  $results = $client->search($params);
```

Elasticsearch DSL covers every elasticsearch query, all examples can be found in [the documentation](docs/index.md)
