Broadway Bundle
===============

Symfony bundle to integrate Broadway into your Symfony application.

[![Build Status](https://travis-ci.org/broadway/broadway-bundle.svg?branch=master)](https://travis-ci.org/broadway/broadway-bundle)

## Installation

The easiest way to install and configure the BroadwayBundle with Symfony is by using
[Symfony Flex](https://github.com/symfony/flex):

```
$ composer require symfony/flex ^1.0
$ composer config extra.symfony.allow-contrib true
$ composer require broadway/broadway-bundle
```

Symfony Flex will automatically register and configure the bundle.

By default in-memory implementations of the event store and read models are used.
You can install one of the persistent implementations as described in the
`Event store` and `Read model` sections of the documentation.

## Documentation
You can find detailed documentation of the Broadway bundle on [broadway.github.io/broadway-bundle](https://broadway.github.io/broadway-bundle/).

Feel free to join #qandidate on freenode with questions and remarks!
