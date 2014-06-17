Factories
=========

Example CSVFactory

```php
<?php

namespace Bluetea\ImportBundle\Factory;

class CSVFactory extends ImportFactory implements FactoryInterface
{
    protected $options = [
        'delimiter' => ';',
        'enclosure' => '"',
        'escape' => '\\'
    ];

    /**
     * Parser
     *
     * @throws \Exception
     * @return mixed
     */
    public function parse()
    {
        parent::parse();

        $fileObj = new \SplFileObject($this->importEntity->getAbsolutePath());
        $fileObj->setFlags(\SplFileObject::READ_CSV);
        $fileObj->setCsvControl(
            $this->options['delimiter'],
            $this->options['enclosure'],
            $this->options['escape']
        );

        return $fileObj;
    }
}
```

Add them to the services configuration

```yaml
bluetea_import.factory.csv:
    class: Bluetea\ImportBundle\Factory\CSVFactory

bluetea_import.csv:
    class: Bluetea\ImportBundle\Services\Import
    arguments:
        - @bluetea_import.factory.csv
        - @event_dispatcher
        - @bluetea_import.import_manager
        - @bluetea_import.import_log_manager
```


[Index](index.md)