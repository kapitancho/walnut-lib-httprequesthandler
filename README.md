# Http Request Handler and Middleware class set

TODO

## Example
```php
$requestHandler = new CompositeHandler(
    $notFoundHandler, [
        $requestLogger,
        $uncaughtExceptionHandler,
        $routePathFinder,
        $lookupRouter
    ]
);
$response = $requestLogger->handle($request);
```