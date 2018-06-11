## Installation

The easiest way to install and configure the BroadwayBundle with Symfony is by using
[Symfony Flex](https://github.com/symfony/flex).

Make sure you have Symfony Flex installed:

```
$ composer require symfony/flex ^1.0
$ composer config extra.symfony.allow-contrib true
```

Install the bundle:

```
$ composer require broadway/broadway-bundle
```

Symfony Flex will automatically register and configure the bundle.

By default in-memory implementations of the event store and read models are used.
You can install one of the persistent implementations as described in the
[Event store](https://broadway.github.io/broadway-bundle/event_store.md) and [Read models](https://broadway.github.io/broadway-bundle/read_model.md) sections of the documentation.

### Manually

Install the bundle:

```
$ composer require broadway/broadway-bundle
```

Register the bundle in your application kernel:

```
$bundles = array(
    // ..
    new Broadway\Bundle\BroadwayBundle\BroadwayBundle(),
);

```
