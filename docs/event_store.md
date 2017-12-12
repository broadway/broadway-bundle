# Event store

Broadway provides several event store implementations.
By default the [InMemoryEventStore](https://github.com/broadway/broadway/blob/master/src/Broadway/EventStore/InMemoryEventStore.php) is
used.

There a several optional persisting event store implementations:
* [broadway/event-store-dbal](https://github.com/broadway/event-store-dbal) using [Doctrine DBAL](https://github.com/doctrine/dbal)
* [broadway/event-store-mongodb](https://github.com/broadway/event-store-mongodb) using MongoDB

These can be very easily installed using [Symfony Flex](https://github.com/symfony/flex).

## Custom event store

You can also create your own event store implementation by implementing the 
[EventStore](https://github.com/broadway/broadway/blob/master/src/Broadway/EventStore/EventStore.php) interface.

Next, create a service definition for it:

```xml
<service id="my_event_store" class="MyEventStore" />
```

And configure it in the bundle config:

```yaml
# config.yml
broadway:
  # a service definition id implementing Broadway\EventStore\EventStore,
  event_store: "my_event_store"
```
