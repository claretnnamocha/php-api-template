<?php
use MiladRahimi\PhpRouter\Middleware;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class VerifyCSRF implements Middleware
{
    public function handle(ServerRequestInterface $request, Closure $next)
    {
    	if (method('post') and !validate_csrf('_csrf_token_')) {    		
        	return new JsonResponse([ 'status' => false, 'message' => 'Unauthorized!' ]);
    	}
    	return $next($request);
    }
}