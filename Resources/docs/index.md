Getting started with BlueteaImportBundle
========================================

The ImportBundle enables you to import data to your application. At the moment we only support CSV files but this can
be expended when adding import factories.

## Requirements

This bundle requires Symfony 2.3+ and PHP 5.3.2+

## Installation

Installation is simple:

1. Download BlueteaImportBundle using composer
2. Enable the Bundle
3. Configure the BlueteaImportBundle
4. Update your database schema

### Step 1: Download BlueteaImportBundle using composer

Add BlueteaImportBundle by running the command:

```bash
$ php composer.phar require bluetea/import-bundle '~1.2'
```

Composer will install the bundle to your project's `vendor/bluetea` directory.

### Step 2: Enable the Bundle

Enable the bundle in the kernel:

``` php
<?php
// app/AppKernel.php

public function registerBundles()
{
    $bundles = array(
        // ...
        new Bluetea\ImportBundle\BlueteaImportBundle(),
    );
}
```

### Step 3: Configure the BlueteaImportBundle

Add the following configuration to your `config.yml` file.

``` yaml
# app/config/config.yml
bluetea_import:
    import_class: Bluetea\ImportBundle\Entity\Import
    import_log_class: Bluetea\ImportBundle\Entity\ImportLog
    error_threshold: 1000 # OPTIONAL! Import is aborted if it hits the error threshold
    progress_update: 1000 # OPTIONAL! Updates the progress field every x lines
```

If you like to implement your own entities, read the [extend entities documentation](extend_entities.md).

If you haven't enabled doctrine auto mapping, add `BlueteaImportBundle` to your mappings.

### Step 4: Update your database schema

Now that the bundle is configured, the last thing you need to do is update your database schema
because the bundle adds the Import and ImportLog entity. If you're using your own entities you
should exclude the `BlueteaImportBundle` entities.

Run the following command.

``` bash
$ php app/console doctrine:schema:update --force
```

If you're using the Doctrine migration bundle, run:

``` bash
$ php app/console doctrine:migrations:diff
```

Now you're ready to use the BlueteaImportBundle!

### Next steps

* [Add import types](import_types.md)
* [Using the managers](managers.md)
* [Extend the entities](extend_entities.md)
* [Event listeners](event_listeners.md)
* [Add factories](factories.md)
* [Secondary entity manager](advanced.md)