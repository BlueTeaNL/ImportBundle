Extend the entities
===================

If you want to add extra properties to the import or importLog entities you can extend them. Then configure your
entities in the bluetea_import setting in the `config.yml`.

Example Import entity:

```php
<?php
namespace Acme\ImportBundle\Entity;

use Bluetea\ImportBundle\Model\Import as BaseImport;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="import")
 */
class Import extends BaseImport
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    public function __construct()
    {
        parent::__construct();
        // your own logic
    }
}
```

[Index](index.md)