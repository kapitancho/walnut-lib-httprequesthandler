<?php

namespace Walnut\Lib\HttpRequestHandler;

use Exception;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Log\LoggerInterface;

final class ErrorLoggerHandler implements MiddlewareInterface {

	public function __construct(
		private readonly LoggerInterface $logger
	) {}

	/**
	 * @throws Exception
	 */
	public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface {
		try {
			$result = $handler->handle($request);
			if ($result->getStatusCode() >= 400) {
				$contents = $result->getBody()->getContents();
				$result->getBody()->rewind();
				$this->logger->error($result->getStatusCode() . ':' . $contents);
			}
			return $result;
		} catch (Exception $e) {
			$this->logger->error($e);
			throw $e;
		}
	}

}
