# Configuration

The Broadway bundle does not necessarily need to be configured because it uses sane default values.
If you installed the bundle using [Symfony Flex](https://github.com/symfony/flex) you can find the default configuration
in the `config/packages/broadway.yaml` file in your application.

The full default configuration is as follows:

```yaml
# config/packages/broadway.yaml
# from ./bin/console debug:config broadway
broadway:

    # a service definition id implementing Broadway\EventStore\EventStore
    event_store: broadway.event_store.in_memory

    # a service definition id implementing Broadway\ReadModel\RepositoryFactory
    read_model: broadway.read_model.in_memory.repository_factory

    # service definition ids implementing Broadway\Serializer\Serializer
    serializer:
        payload: broadway.simple_interface_serializer
        readmodel: broadway.simple_interface_serializer
        metadata: broadway.simple_interface_serializer

    command_handling:
        dispatch_events: false

        # a service definition id implementing Psr\Log\LoggerInterface
        logger: ~

    saga:
        enabled: false

        # a service definition id implementing Broadway\Saga\State\RepositoryInterface
        state_repository: broadway.saga.state.in_memory_repository
```
