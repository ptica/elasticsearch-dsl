# ElasticsearchDSL

[![codecov](https://codecov.io/github/bistrosk/elasticsearch-dsl/branch/master/graph/badge.svg?token=CPGXKV2LN4)](https://codecov.io/github/bistrosk/elasticsearch-dsl)
[![Latest Stable Version](http://poser.pugx.org/bistrosk/elasticsearch-dsl/v)](https://packagist.org/packages/bistrosk/elasticsearch-dsl) [![Total Downloads](http://poser.pugx.org/bistrosk/elasticsearch-dsl/downloads)](https://packagist.org/packages/bistrosk/elasticsearch-dsl) [![Latest Unstable Version](http://poser.pugx.org/bistrosk/elasticsearch-dsl/v/unstable)](https://packagist.org/packages/bistrosk/elasticsearch-dsl) [![License](http://poser.pugx.org/bistrosk/elasticsearch-dsl/license)](https://packagist.org/packages/bistrosk/elasticsearch-dsl) [![PHP Version Require](http://poser.pugx.org/bistrosk/elasticsearch-dsl/require/php)](https://packagist.org/packages/bistrosk/elasticsearch-dsl)

## Fork of [ongr-io/ElasticsearchDSL](https://github.com/ongr-io/ElasticsearchDSL) library with updated PHP and Elasticsearch version.

Introducing Elasticsearch DSL library to provide objective query builder for [elasticsearch-php](https://github.com/elastic/elasticsearch-php) client. You can easily build any Elasticsearch query and transform it to an array.

## Version matrix

| Elasticsearch version | ElasticsearchDSL version |
|-----------------------|--------------------------|
| >= 8.0                | >= 8.0                   |

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
