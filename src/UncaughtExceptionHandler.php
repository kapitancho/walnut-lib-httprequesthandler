<?php

namespace Walnut\Lib\HttpRequestHandler;

use Exception;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Walnut\Lib\JsonSerializer\JsonSerializer;
use Walnut\Lib\JsonSerializer\JsonSerializerException;

final class UncaughtExceptionHandler implements MiddlewareInterface {

	public function __construct(
		private readonly JsonSerializer $jsonSerializer,
		private readonly ResponseFactoryInterface $responseFactory,
		private readonly StreamFactoryInterface $streamFactory
	) {}

	public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface {
		try {
			return $handler->handle($request);
		} catch (Exception $e) {
			try {
				$errorContent = $this->jsonSerializer->encode(['error' => $e->getMessage() ?: $e::class]);
			} catch (JsonSerializerException) {
				$errorContent = $this->jsonSerializer->encode(['error' => "Internal error: " . $e::class]);
			}
			//return $this->responseBuilder->emptyResponse(500);
			return $this->responseFactory->createResponse(500)->withBody(
				$this->streamFactory->createStream(
					$errorContent
				)
			);
		}
	}
}
