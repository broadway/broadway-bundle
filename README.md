Broadway Bundle
===============

Symfony bundle to integrate Broadway into your Symfony application.

> Note: this bundle is far from complete. Please let us know (or send a pull
> request) if you miss any configuration options, etc!

## Usage

Register the bundle in your application kernel:

```
$bundles = array(
    // ..
    new Broadway\Bundle\BroadwayBundle\BroadwayBundle(),
);

```

> Note: in order to use the bundle you need some additional dependencies. See
> the suggest key of the composer.json file.

## Services

Once enabled the bundle will expose several services, such as:

- `broadway.command_handling.command_bus` command bus to inject if you use commands
- `broadway.event_store` alias to the active event store
- `broadway.uuid.generator` active uuid generator

## Event Store

By default the [InMemoryEventStore](https://github.com/broadway/broadway/blob/master/src/Broadway/EventStore/InMemoryEventStore.php) is
used.

Broadway provides a persisting event store implementation using `doctrine/dbal`
in [broadway/event-store-dbal](https://github.com/broadway/event-store-dbal).

This can be installed using composer:

```
$ composer require broadway/event-store-dbal
```

You will need to enabel an event store in your application's configuration:

```yaml
# config.yml
broadway:
  event_store:
    dbal:
      enabled: true
```

To generate the mysql schema for the event store use the following command

```bash
bin/console broadway:event-store:schema:init
```

The schema can be dropped using

```bash
bin/console broadway:event-store:schema:drop
```

## Read models

By default the [in memory](https://github.com/broadway/broadway/tree/master/src/Broadway/ReadModel/InMemory) 
read model implementation is used.

Broadway provides a persisting read model implementation using `Elasticsearch`
in [broadway/read-model-elasticsearch](https://github.com/broadway/read-model-elasticsearch).

This can be installed using composer:

```
$ composer require broadway/read-model-elasticsearch
```

You need to configure its read model repository factory in you application:

```xml
<!-- services.xml -->
<service id="my_read_model_repository_factory" class="Broadway\ReadModel\ElasticSearch\ElasticSearchRepositoryFactory">
    <argument type="service" id="my_elasticsearch_client" />
    <argument type="service" id="broadway.serializer.readmodel" />
</service>

<service id="my_elasticsearch_client" class="Elasticsearch\Client">
    <factory service="broadway.elasticsearch.client_factory" method="create" />
    <argument>%elasticsearch%</argument>
</service>

<service id="broadway.elasticsearch.client_factory" class="Broadway\ReadModel\ElasticSearch\ElasticSearchClientFactory" public="false" />
```

And tell the Broadway bundle to use it:

```yaml
# config.yml
broadway:
  read_model: "my_read_model_repository_factory"
```

## Tags

The bundle provides several tags to use in your service configuration.

### Command handler

Register command handler using `broadway.command_handler` service tag:
```xml
<service class="TestCommandHandler">
    <tag name="broadway.command_handler" />
</service>
```

### Domain event listeners

Register listeners (such as projectors) that respond and act on domain events:

```xml
<tag name="broadway.domain.event_listener" />
```

### Event listeners

For example an event listener that collects successfully executed commands:

```xml
<tag name="broadway.event_listener"
    event="broadway.command_handling.command_success"
    method="onCommandHandlingSuccess" />
```

## Metadata enrichers

It is possible to add additional metadata to persisted events. This is useful
for recording extra contextual (auditing) data such as the currently logged in
user, an ip address or some request token.

```xml
<tag name="broadway.metadata_enricher" />
```

### Sagas

Broadway provides a saga implementation using `MongoDB`
in [broadway/broadway-saga](https://github.com/broadway/broadway-saga).

This can be installed using composer:

```
$ composer require broadway/broadway-saga
```

To enable it, add the following configuration:

```yaml
# config.yml
broadway:
  saga:
    enabled: true
```

Be default its [in memory](https://github.com/broadway/broadway-saga/blob/master/src/State/InMemoryRepository.php) state repository is configured.

To use the MongoDB implementation you need to configure it:

```xml
<!-- services.xml -->
<service id="my_saga_state_repository" class="Broadway\Saga\State\MongoDBRepository">
    <argument type="service" id="my_mongodb_collection" />
</service>

<service id="my_mongodb_collection" class="Doctrine\MongoDB\Collection">
    <factory service="my_mongodb_database" method="createCollection" />
    <argument>saga-state</argument>
</service>

<service id="my_mongodb_database" class="Doctrine\MongoDB\Database">
    <factory service="my_mongodb_connection" method="selectDatabase" />
    <argument>%broadway.saga.mongodb.database%</argument>
</service>

<service id="my_mongodb_connection" class="Doctrine\MongoDB\Connection">
    <argument>null</argument>
    <argument type="collection" />
</service>
```

And tell the Broadway bundle to use it:

```yaml
# config.yml
broadway:
  saga:
    enabled: true
    state_repository: "my_saga_state_repository"
```

Register sagas using the `broadway.saga` service tag:
 
```xml
<!-- services.xml -->
<service class="ReservationSaga">
    <argument type="service" id="broadway.command_handling.command_bus" />
    <argument type="service" id="broadway.uuid.generator" />
    <tag name="broadway.saga" type="reservation" />
</service>
```

## Configuration

There are some basic configuration options available at this point. The
options are mostly targeted on providing different setups based on production
or testing usage.

```yml
# config.yml
broadway:
    event_store:          ~ # a service definition id implementing Broadway\EventStore\EventStore, by default the broadway.event_store.in_memory will be used
    read_model:           ~ # a service definition id implementing Broadway\ReadModel\RepositoryFactory, by default the broadway.read_model.in_memory.repository_factory will be used
    serializer:
        payload:          ~ # default: broadway.simple_interface_serializer
        readmodel:        ~ # default: broadway.simple_interface_serializer
        metadata:         ~ # default: broadway.simple_interface_serializer
    command_handling:
        logger:           false # If you want to log every command handled, provide the logger's service id here (e.g. "logger")
    saga:
        enabled:          ~ # default: false 
        state_repository: ~ # a service definition id implementing Broadway\Saga\State\RepositoryInterface, by default the broadway.saga.state.in_memory_repository will be used
```
