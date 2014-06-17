Advanced
========

If you want to use another entity manager, change the import type service.

```yaml
acme_import.import_entity_manager:
    class: Doctrine\ORM\EntityManager
    factory_service: doctrine
    factory_method: getManager
    arguments:
        - "import"

acme_import.your_import_type:
    class: Acme\ImportBundle\Import\Type\YourImportType
    arguments:
        - @service_container
        - @acme_import.import_entity_manager
    tags:
        - { name: bluetea_import.import_type, description: 'your import type' }
```


[Index](index.md)