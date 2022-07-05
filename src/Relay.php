<?php

declare(strict_types = 1);

/**
 * Weave Relay Middleware Adaptor.
 */
namespace Weave\Middleware\Relay;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

/**
 * Weave Middleware Adaptor to use relay with Weave.
 */
class Relay implements \Weave\Middleware\MiddlewareAdaptorInterface
{
	/**
	 * Callable capable of resolving a valid string into a callable.
	 *
	 * @var callable
	 */
	protected $resolver;

	/**
	 * Set a callable on the Adaptor that can be used to resolve strings to class instances.
	 *
	 * @param callable $resolver The resolver.
	 *
	 * @return void
	 */
	public function setResolver(callable $resolver): void
	{
		$this->resolver = $resolver;
	}

	/**
	 * Whether the Middleware the Adaptor wraps is a single- or double-pass style Middleware stack.
	 *
	 * @return boolean
	 */
	public function isDoublePass(): bool
	{
		return false;
	}

	/**
	 * Trigger execution of the supplied pipeline through Relay.
	 *
	 * @param mixed     $pipeline The stack of middleware definitions.
	 * @param Request   $request  The PSR7 request.
	 * @param ?Response $response The PSR7 response (for double-pass stacks).
	 *
	 * @return Response
	 */
	public function executePipeline($pipeline, Request $request, ?Response $response = null): Response
	{
		$relayBuilder = new \Relay\RelayBuilder($this->resolver);
		$relay = $relayBuilder->newInstance($pipeline);
		return $relay->handle($request);
	}
}
