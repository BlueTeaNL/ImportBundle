Event Listeners
===============

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