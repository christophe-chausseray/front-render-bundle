[![SensioLabsInsight](https://insight.sensiolabs.com/projects/bcbdd559-f6af-48d4-a6a5-1c859f4267c6/big.png)](https://insight.sensiolabs.com/projects/bcbdd559-f6af-48d4-a6a5-1c859f4267c6)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/christophe-chausseray/front-render-bundle/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/christophe-chausseray/front-render-bundle/?branch=master)
[![Build Status](https://scrutinizer-ci.com/g/christophe-chausseray/front-render-bundle/badges/build.png?b=master)](https://scrutinizer-ci.com/g/christophe-chausseray/front-render-bundle/build-status/master)

# Front Render Bundle

Render Front Application (AngularJs, BackboneJs...) through Symfony 2 Application

Installation
============

Step 1: Download the Bundle
---------------------------

Open a command console, enter your project directory and execute the
following command to download the latest stable version of this bundle:

```bash
$ composer require chris13/front-render-bundle "~1"
```

This command requires you to have Composer installed globally, as explained
in the [installation chapter](https://getcomposer.org/doc/00-intro.md)
of the Composer documentation.

Step 2: Enable the Bundle
-------------------------

Then, enable the bundle by adding it to the list of registered bundles
in the `app/AppKernel.php` file of your project:

```php
<?php
// app/AppKernel.php

// ...
class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = array(
            // ...

            new Chris\Bundle\FrontRenderBundle\FrontRenderBundle(),
        );

        // ...
    }

    // ...
}
```

Configure the bundle
---------------------

2 differents solutions to configure the path to Front Application :

-[Configuration by the config.yml](./Config.md)

-[Configuration by listener](./Listener.md)


Usage
-----

Render your front application :

```php
<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        $frontRender = $this->get('front_render_bundle.front_render');

        //Optional
        $frontRender
            ->setParameters(
            [
                'param' => 'param',
            ]
        );

        return new Response($frontRender->render());
    }
}
```
