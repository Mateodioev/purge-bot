<?php

namespace App;

use Amp\Http\Server\{
    Request,
    RequestHandler as RequestHandlerInterface,
    Response,
};

use Closure;

class RequestHandler implements RequestHandlerInterface
{
    public Closure $handlerRequest;

    public function handleRequest(Request $request): Response
    {
        $fn = $this->handlerRequest;
        return $fn($request);
    }

    public function setHandlerRequest(Closure $handler): static
    {
        $this->handlerRequest = $handler;

        if ($this->getHanlerReturnType() !== Response::class) {
            throw new \Exception(sprintf('Handler must return a %s object', Response::class));
        }

        return $this;
    }

    public static function new(Closure $handler): static
    {
        $sv = new static();
        $sv->setHandlerRequest($handler);

        return $sv;
    }

    private function getHanlerReturnType(): string
    {
        $reflection = new \ReflectionFunction($this->handlerRequest);
        return $reflection->getReturnType()?->getName();
    }
}
