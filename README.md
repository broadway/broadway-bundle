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

To generate the mysql schema for the event store use the following command

```bash
bin/console broadway:event-store:schema:init
```

The schema can be dropped using

```bash
bin/console broadway:event-store:schema:drop
```

## Tags

The bundle provides several tags to use in your service configuration.

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

Register sagas using the `broadway.saga` service tag:
 
```xml
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
broadway:
    event_store:      ~ # default: broadway.event_store.in_memory
    read_model:       ~ # default: broadway.read_model.in_memory.repository_factory
    saga:             ~ # default: broadway.saga.state.in_memory_repository
    command_handling:
        logger:       false # If you want to log every command handled, provide the logger's service id here (e.g. "logger")
    serializer:
        payload:      ~ # default: broadway.simple_interface_serializer
        readmodel:    ~ # default: broadway.simple_interface_serializer
        metadata:     ~ # default: broadway.simple_interface_serializer
```

## Event store DBAL

```xml
<service id="broadway.event_store.dbal" class="Broadway\EventStore\DBALEventStore">
            <argument type="service" id="broadway.event_store.dbal.connection" />
            <argument type="service" id="broadway.serializer.payload" />
            <argument type="service" id="broadway.serializer.metadata" />
            <argument>%broadway.event_store.dbal.table%</argument>
            <argument>%broadway.event_store.dbal.use_binary%</argument>
            <argument type="service" id="broadway.uuid.converter" />
        </service>
```        

## Read model Elasticsearch

```xml
<service id="broadway.elasticsearch.client" class="Elasticsearch\Client">
            <factory service="broadway.elasticsearch.client_factory" method="create" />
            <argument>%elasticsearch%</argument>
        </service>

        <service id="broadway.elasticsearch.client_factory" class="Broadway\ReadModel\ElasticSearch\ElasticSearchClientFactory" public="false" />

        <service id="broadway.read_model.elasticsearch.repository_factory" class="Broadway\ReadModel\ElasticSearch\ElasticSearchRepositoryFactory">
            <argument type="service" id="broadway.elasticsearch.client" />
            <argument type="service" id="broadway.serializer.readmodel" />
        </service>
        ```


## Saga MongoDb

```xml
<service id="broadway.saga.state.mongodb_repository" class="Broadway\Saga\State\MongoDBRepository">
            <argument type="service" id="broadway.saga.state.mongodb_collection" />
        </service>

        <service id="broadway.saga.state.mongodb_database" class="Doctrine\MongoDB\Database">
            <factory service="broadway.saga.state.mongodb_connection" method="selectDatabase" />
            <argument>%broadway.saga.mongodb.database%</argument>
        </service>

        <service id="broadway.saga.state.mongodb_connection" class="Doctrine\MongoDB\Connection">
            <argument>null</argument>
            <argument type="collection" />
        </service>

        <service id="broadway.saga.state.mongodb_collection" class="Doctrine\MongoDB\Collection">
            <factory service="broadway.saga.state.mongodb_database" method="createCollection" />
            <argument>saga-state</argument>
        </service>
        ```
