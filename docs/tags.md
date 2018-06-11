# Tags

The bundle provides several tags to use in your service configuration.

## Command handler

Register command handler using `broadway.command_handler` service tag:

```
# services.yaml
TestCommandHandler:
    tags: [broadway.command_handler]
```

## Domain event listeners

Register listeners (such as projectors) that respond and act on domain events:

```yaml
tags: [broadway.domain.event_listener]
```

## Event listeners

For example an event listener that collects successfully executed commands:

```yaml
tags:
    - { name: broadway.event_listener, event: broadway.command_handling.command_success, method: onCommandHandlingSuccess }
```
