Event Listeners
===============

## Events

The ImportBundle fires events when starting and finishing an import.

**BlueteaImportEvents::IMPORT_INITIALIZE**

This event is fired when initializing an import and allows you to get the import entity before running the import.
The event listener method receives a Bluetea\ImportBundle\Events\GetImportEvent instance.

**BlueteaImportEvents::IMPORT_SUCCESS**

This event is fired when an import is successful and allows you to get the import entity before running the import.
The event listener method receives a Bluetea\ImportBundle\Events\GetImportLogEvent instance.

**BlueteaImportEvents::IMPORT_FAILED**

This event is fired when an import is finished and allows you to get the import entity before running the import.
The event listener method receives a Bluetea\ImportBundle\Events\GetImportLogEvent instance.

**BlueteaImportEvents::IMPORT_COMPLETED**

This event is fired when an import is finished and allows you to get the import entity before running the import.
The event listener method receives a Bluetea\ImportBundle\Events\GetImportLogEvent instance.


```php
namespace Acme\UserBundle\EventListener;

use Bluetea\ImportBundle\BlueteaImportEvents;
use Bluetea\ImportBundle\Event\ImportEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Listener responsible to change the redirection at the end of the password resetting
 */
class PasswordResettingListener implements EventSubscriberInterface
{
    /**
     * {@inheritDoc}
     */
    public static function getSubscribedEvents()
    {
        return array(
            BlueteaImportEvents::IMPORT_SUCCESS => 'sendMail',
        );
    }

    public function sendMail(ImportEvent $event)
    {
        // Do something
    }
}
```

Define the listener in your services

```yaml
services:
    acme_import.send_mail_after_import:
        class: Acme\ImportBundle\EventListener\SendMailListener
        tags:
            - { name: kernel.event_subscriber }
```

[Index](index.md)