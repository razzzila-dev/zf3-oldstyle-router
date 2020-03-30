# Old style router for the Zend Framework 3

[![GitHub license](https://img.shields.io/github/license/Naereen/StrapDown.js.svg)](https://choosealicense.com/licenses/mit/)
[![Ask Me Anything !](https://img.shields.io/badge/Ask%20me-anything-1abc9c.svg)](mailto:dev@nikitaisakov.com?subject=[GitHub]%20Ask%20me%20anything)
[![Contributions welcome](https://img.shields.io/badge/Contributions-welcome-brightgreen.svg?style=flat)](https://github.com/razzzila-dev/golang-server-metrix-client/issues)

From ZF3 was removed router which find the controller and action using path from request and run it.
For my own opinion this is a good thing and you should specify routes more clearly and harder.
But still we have the problem when we migrate the codebase from ZF1 to ZF3 iteratively and in the first step we don't want to refactore pathes.

This is the answer - ready "old style" router which authomatically detect if the class and action exist run it.
Also, it detect parameters in the query and allow to use them as previously (calling param method from the request).


## Installation
### Package install
#### Using Composer
Change working directory to the project root folder and run:
```BASH
composer require razzzila-dev/zf3-oldstyle-router
```

## Usage
To configure your application for using this router we have to specify it in the router configuration.
For doing so, open the your application configs/module.config.php and specify this router:
```PHP
...

use Zend\Router\RouteInvokableFactory;
use Application\Router\Http\OldStyle as OldStyleRouter;
use Application\Controller\IndexController;

return [
    'router' => [
        'routes' => [
            'default' => [
                'type' => OldStyle::class,
                'options' => [
                    'route'    => '/[:controller[/:action[/]]]',
                    'defaults' => [
                        'controller' => IndexController::class,
                        'action'     => 'index',
                    ]
                ],
            ],
        ],
    ],
    
    'service_manager' => [
        'factories' => [
            OldStyleRouter::class  => RouteInvokableFactory::class,
        ]
    ]
];
```
Under "defaults" section you can find the "controller" and "action" parameters.
Here you can specify a fallback controller and action in case if controller and action from the request didn't found.
To specify which module controllers will be used for searching in, you can use "module" option.
By default this is "Application" module which means that for request "/index/index" we will search and trigger "Application\Controller\IndexController@indexAction".

## License
[MIT](https://choosealicense.com/licenses/mit/)
