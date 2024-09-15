<?php

namespace Walnut\Lib\HttpRequestHandler;

use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class CorsHandler implements MiddlewareInterface {

	/**
	 * @param string[] $origins
	 * @param string[] $methods
	 * @param string[] $allowedHeaders
	 * @param string[] $exposedHeaders
	 */
	public function __construct(
		private readonly ResponseFactoryInterface $responseFactory,
		private readonly array $origins = ['*'],
		private readonly array $methods = ['GET', 'POST', 'PUT', 'PATCH', 'DELETE'],
		private readonly array $allowedHeaders = [],
		private readonly array $exposedHeaders = [],
		private readonly bool $allowCredentials = false,
		private readonly int $maxAge = 0
	) {}

	public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface {
		return $this->addHeaders(
			$request->getMethod() === 'OPTIONS' ?
			$this->responseFactory->createResponse() :
			$handler->handle($request)
		);
	}

	/** @noinspection CallableParameterUseCaseInTypeContextInspection */
	private function addHeaders(ResponseInterface $response): ResponseInterface {
		if ($this->allowCredentials) {
			$response = $response->withAddedHeader('Access-Control-Allow-Credentials', 'true');
		}
		if ($this->exposedHeaders) {
			$response = $response->withAddedHeader('Access-Control-Expose-Headers', implode(', ', $this->exposedHeaders));
		}
		if ($this->allowedHeaders) {
			$response = $response->withAddedHeader('Access-Control-Allow-Headers', implode(', ', $this->allowedHeaders));
		}
		if ($this->origins) {
			$response = $response->withAddedHeader('Access-Control-Allow-Origin', implode(', ', $this->origins));
		}
		if ($this->methods) {
			$response = $response->withAddedHeader('Access-Control-Allow-Methods', implode(', ', $this->methods));
		}
		if ($this->maxAge) {
			$response = $response->withAddedHeader('Access-Control-Max-Age', (string)$this->maxAge);
		}
		return $response;
	}

}
