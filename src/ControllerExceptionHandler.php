<?php

namespace Walnut\Lib\HttpRequestHandler;

use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Walnut\Lib\HttpController\ControllerException;
use Walnut\Lib\JsonSerializer\JsonSerializer;

final class ControllerExceptionHandler implements MiddlewareInterface {

	public function __construct(
		private readonly JsonSerializer $jsonSerializer,
		private readonly ResponseFactoryInterface $responseFactory,
		private readonly StreamFactoryInterface $streamFactory
	) {}

	public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface {
		try {
			return $handler->handle($request);
		} catch (ControllerException $e) {
			return $this->responseFactory->createResponse($e->getCode())
				->withHeader('Content-Type', 'application/json')
				->withBody($this->streamFactory->createStream(
					$this->jsonSerializer->encode(['error' => $e->getMessage()])
				));
		}
	}
}
