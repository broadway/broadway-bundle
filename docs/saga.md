# Sagas

Broadway provides a saga implementation using `MongoDB`
in [broadway/broadway-saga](https://github.com/broadway/broadway-saga).

This can be installed using composer:

```
$ composer require broadway/broadway-saga
```

When using [Symfony Flex](https://github.com/symfony/flex) the required
services are configured automatically.

Register sagas using the `broadway.saga` service tag:

```yaml
# services.yaml
reservation_saga:
    class: ReservationSaga
    arguments:
        - "@broadway.command_handling.command_bus"
        - "@broadway.uuid.generator"
    tags:
        - { name: broadway.saga, type: reservation }
