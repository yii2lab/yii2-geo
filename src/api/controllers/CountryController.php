<?php

namespace yii2lab\geo\api\controllers;

use common\enums\rbac\PermissionEnum;
use yii2lab\app\domain\helpers\Config;
use yii2lab\domain\rest\ActiveControllerWithQuery as Controller;

class CountryController extends Controller
{
	
	public $serviceName = 'geo.country';

	/**
	 * @inheritdoc
	 */
	public function behaviors() {
		return [
			'authenticator' => [
				'class' => 'yii2woop\account\domain\filters\auth\HttpTokenAuth',
				'only' => ['create', 'update', 'delete'],
			],
			'access' => [
				'class' => 'yii\filters\AccessControl',
				'only' => ['create', 'update', 'delete'],
				'rules' => [
					[
						'allow' => true,
						'roles' => [PermissionEnum::GEO_COUNTRY_MANAGE],
					],
				],
			],
			'corsFilter' => Config::genCors(),
		];
	}
	
}
