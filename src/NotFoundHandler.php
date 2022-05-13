<?php

namespace Walnut\Lib\HttpRequestHandler;

use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class NotFoundHandler implements RequestHandlerInterface {

	public function __construct(
		private readonly ResponseFactoryInterface $responseFactory,
	) {}

	public function handle(ServerRequestInterface $request): ResponseInterface {
		return $this->responseFactory->createResponse(404);
	}
}
