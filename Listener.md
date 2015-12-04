# Path configuration by listener

Service declaration for the listener :

```xml
    <parameters>
        <parameter key="app_bundle.front_listener.class">AppBundle\Listener\FrontListener</parameter>
    </parameters>

    <services>
        <service id="app_bundle.front_listener" class="%app_bundle.front_listener.class%">
            <tag name="kernel.event_listener" event="front_render.before_render" method="onBeforeRender"/>
        </service>
    </services>
```

Set the front path on the event in the listener :

```php
<?php

namespace AppBundle\Listener;

use Chris\Bundle\FrontRenderBundle\Event\BeforeRenderEvent;

class FrontListener
{
    // ...

    public function onBeforeRender(BeforeRenderEvent $event)
    {
        // ...

        $event->setFrontPath($frontPath);
    }
}
```
