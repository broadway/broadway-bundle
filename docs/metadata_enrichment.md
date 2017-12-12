# Metadata enrichment

It is possible to add additional metadata to persisted events. This is useful
for recording extra contextual (auditing) data such as the currently logged in
user, an IP address, a user agent or some request token.

The metadata enricher is automatically registered when adding the following tag to the service definition:

```xml
<tag name="broadway.metadata_enricher" />
```

## IP address example

```php
use Broadway\Domain\Metadata;
use Broadway\EventSourcing\MetadataEnrichment\MetadataEnricher;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

class IpAddressMetadataEnricher implements MetadataEnricher
{
    private $requestStack;

    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    public function enrich(Metadata $metadata): Metadata
    {
        return $metadata->merge(
            Metadata::kv(
                'ip_address',
                $this->getClientIp()
            )
        );
    }

    private function getClientIp(): string
    {
        $currentRequest = $this->requestStack->getCurrentRequest();

        return $currentRequest instanceof Request ? $currentRequest->getClientIp() : '';
    }
}
```

```yaml
# services.yml
my_ip_address_metadata_enricher
    class: My\IpAddressMetadataEnricher
    arguments:
      - "@request_stack"
    tags:
      - { name: broadway.metadata_enricher }
```
