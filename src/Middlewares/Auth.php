<?php
use MiladRahimi\PhpRouter\Middleware;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class Auth implements Middleware
{
    public function handle(ServerRequestInterface $request, Closure $next)
    {
    	if (!session_get('logged-in')) {    		
        	return new JsonResponse([ 'status' => false, 'message' => 'You\'re not logged in!' ]);
    	}
    	return $next($request);
    }
}