# Read models

Broadway provides several read model implementations.
By default the [in memory](https://github.com/broadway/broadway/tree/master/src/Broadway/ReadModel/InMemory) 
read model implementation is used.

There a several optional persisting read model implementations:
* [broadway/read-model-elasticsearch](https://github.com/broadway/read-model-elasticsearch) using Elasticsearch
* [broadway/read-model-mongodb](https://github.com/broadway/read-model-mongodb) using MongoDB

These can be very easily installed using [Symfony Flex](https://github.com/symfony/flex).

Of course there are other implementations and you can also create your own and configure it:

```yaml
# config.yaml
broadway:
  # a service definition id implementing Broadway\ReadModel\RepositoryFactory
  read_model: "my_read_model_repository_factory"
```
