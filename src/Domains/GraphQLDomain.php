<?php
namespace AlphaAPI\Domains;

use RedBeanPHP\R as DB;

class GraphQLDomain
{
	static function Resolvers()
	{
		return[
			'Query' => self::Query(),
			'Mutation' => self::Mutation()
		];
	}

	static function Types()
	{
		return
		'
			type User{
				id: Int
				name: String
			}
			type Query {
				user(userId: Int!): User
				all_users: [User]
			}
			type Mutation {
				new_user(name: String!): User
				delete_user(userId: Int!): Boolean
			}
		';
	}

	private static function Query()
	{
		return [
			'user' => function ($root, $args){
				return DB::loadForUpdate('user',$args['id']);
			},
			'all_users' => function ($root, $args){
				return DB::findAll('user');
			}
		];
	}

	private static function Mutation()
	{
		return [
			'new_user' => function ($root, $args) {
				$user =  DB::dispense('user');
				$user->name = $args['name'];
				return DB::load('user',DB::store($user));
			},
			'delete_user' => function ($root, $args) {
				$user = DB::loadForUpdate('user',$args['id']);
				DB::trash($user);
				return true;
			}
		];
	}

}