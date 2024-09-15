<?php

namespace Walnut\Lib\HttpRequestHandler;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class NoCacheHandler implements MiddlewareInterface {

	public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface {
		return $this->addHeaders(
			$handler->handle($request)
		);
	}

	private function addHeaders(ResponseInterface $response): ResponseInterface {
		return $response
			->withAddedHeader('Pragma', 'no-cache');
	}
}
