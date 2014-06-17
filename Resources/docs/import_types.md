Add import types
================

The import types implement the ImportInterface and extend the BaseImport class.
The BaseImport class implement default business logic like the import, deleteFromCache,
persisAndFlush and deleteEntity methods. The import type has only one mandatory method: importLine.

The ImportLine method receives an one-dimensional array. Example:

```php
array(
    'reference',
    'name',
    'another reference for a relationship'
),
```

You can find an example of an import type [here](../../Import/Type/ExampleImportType.php).

Then, add the import type as a service:

```yaml
acme_import.your_import_type:
    class: Acme\ImportBundle\Import\Type\YourImportType
    arguments:
        - @service_container
        - @hotflo_admin_import.import_entity_manager
    tags:
        - { name: bluetea_import.import_type, description: 'your import type' }
```


[Index](index.md)