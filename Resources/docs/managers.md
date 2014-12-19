Using the managers
==================

You can use the managers to manage the import and import log entities which you have
configured in your `app/config/config.yaml` file.

## Usage

Import manager:

```php
$this->get('bluetea_import.import_manager')->findImport($yourId);
```

Import log manager:

```php
$this->get('bluetea_import.import_log_manager')->findImport($yourId);
```


[Index](index.md)