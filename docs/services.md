# Services

Once enabled the bundle will expose several services, such as:

- `broadway.command_handling.command_bus` command bus to inject if you use commands
- `broadway.event_store` alias to the active event store
- `broadway.uuid.generator` active uuid generator

For a full list of services, run:

```
./bin/console debug:container | grep broadway
```
