<?php

namespace yii2lab\geo\widgets;

use common\enums\app\ApiVersionEnum;
use Yii;
use yii\base\Widget;
use yii2lab\domain\data\Query;
use yii\helpers\ArrayHelper;
use yii2lab\widgets\ajaxSelector\AjaxSelector;

class GeoSelector extends Widget
{
	
	public $form;
	public $model;
	public $url = [];
	public $default = [];
	
	public function init() {
		parent::init();
		$version = 'v' . env('api.version.default');
		$this->url['city'] = isset($this->url['city']) ? $this->url['city'] : $version . '/city';
		$this->url['region'] = isset($this->url['region']) ? $this->url['region'] : $version . '/region';
		$this->url['country'] = isset($this->url['country']) ? $this->url['country'] : $version . '/country';
	}
	
	/**
	 * Runs the widget
	 */
	public function run()
	{
		//$this->formId = strtolower(basename(get_class($this->model)));
		$entities = [
			'country' => [
				//'primaryKey' => 'id',
				//'elementName' => 'country_id',
				//'elementId' => $this->formId . '-country_id',
				'uri' => $this->url['country'],
				'prompt' => Yii::t('geo/main', 'select_country'),
				'childName' => 'region',
				'options' => $this->getCollection('country'),
			],
			'region' => [
				//'primaryKey' => 'id',
				//'elementName' => 'region_id',
				//'elementId' => $this->formId . '-region_id',
				'uri' => $this->url['region'],
				'prompt' => Yii::t('geo/main', 'select_region'),
				'childName' => 'city',
				'options' => $this->getCollection('region', 'country_id'),
			],
			'city' => [
				//'primaryKey' => 'id',
				//'elementName' => 'city_id',
				//'elementId' => $this->formId . '-city_id',
				'uri' => $this->url['city'],
				'prompt' => Yii::t('geo/main', 'select_city'),
				'childName' => null,
				'options' => $this->getCollection('city', 'region_id'),
			],
		];
		
		return AjaxSelector::widget([
			'form' => $this->form,
			'model' => $this->model,
			'entities' => $entities,
		]);
	}
	
	private function getCollection($entityName, $fieldName = null) {
		$query = Query::forge();
		if(!empty($fieldName)) {
			$value = $this->model->hasProperty($fieldName) ? $this->model->{$fieldName} : ArrayHelper::getValue($this->default, $fieldName);
			if(empty($value)) {
				return [];
			}
			$query->where($fieldName, $value);
		}
		$collection = Yii::$domain->geo->{$entityName}->all($query);
		$collection = ArrayHelper::map($collection, 'id', 'name');
		return $collection;
	}
}
