Extend the entities
===================

Example:

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

Above is for the Import entity but it's the same implementation for the ImportLog entity


[Index](index.md)