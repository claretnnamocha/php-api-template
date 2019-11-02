<?php
namespace AlphaAPI\Controllers;

use Zend\Diactoros\Response\JsonResponse;
use Siler\GraphQL;
use AlphaAPI\Domains\GraphQLDomain;

class Controller
{
	function index()
	{
		return new JsonResponse([ 'status' => true, 'message' => 'Hello World!' ]);
	}

	function graphql()
	{
		$schema = GraphQL\schema(GraphQLDomain::Types(), GraphQLDomain::Resolvers());
		// print_r(trim(GraphQLDomain::Types()));
		GraphQL\init($schema);
	}
}