# Http Request Handler and Middleware class set

TODO

## Example
```php
$requestHandler = new CompositeHandler(
    $notFoundHandler, [
        $noCacheHandler,
        $corsHandler,
        $requestLogger,
        $uncaughtExceptionHandler,
        $controllerExceptionHandler,
        $routePathFinder,
        $lookupRouter
    ]
);
$response = $requestLogger->handle($request);
```