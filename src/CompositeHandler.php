<?php

namespace Walnut\Lib\HttpRequestHandler;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class CompositeHandler implements RequestHandlerInterface {
	/**
	 * @param RequestHandlerInterface $defaultHandler
	 * @param MiddlewareInterface[] $middlewares
	 */
	public function __construct(
		private readonly RequestHandlerInterface $defaultHandler,
		private readonly array $middlewares
	) {}

	public function handle(ServerRequestInterface $request): ResponseInterface {
		if (!$this->middlewares) {
			return $this->defaultHandler->handle($request);
		}
		$middleware = $this->middlewares[0];
		return $middleware->process($request, new self(
			$this->defaultHandler, array_slice($this->middlewares, 1)
		));
	}
}
