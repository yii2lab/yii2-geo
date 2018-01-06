<?php

namespace yii2lab\geo\widgets;

use Yii;
use yii\base\Widget;
use yii2lab\domain\data\Query;
use yii\helpers\ArrayHelper;
use yii2lab\widgets\ajaxSelector\AjaxSelector;

class GeoSelector extends Widget
{
	
	public $form;
	public $model;
	public $default = [];
	
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
				'uri' => 'v4/country',
				'prompt' => Yii::t('geo/main', 'select_country'),
				'childName' => 'region',
				'options' => $this->getCollection('country'),
			],
			'region' => [
				//'primaryKey' => 'id',
				//'elementName' => 'region_id',
				//'elementId' => $this->formId . '-region_id',
				'uri' => 'v4/region',
				'prompt' => Yii::t('geo/main', 'select_region'),
				'childName' => 'city',
				'options' => $this->getCollection('region', 'country_id'),
			],
			'city' => [
				//'primaryKey' => 'id',
				//'elementName' => 'city_id',
				//'elementId' => $this->formId . '-city_id',
				'uri' => 'v4/city',
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
		$collection = Yii::$app->geo->{$entityName}->all($query);
		$collection = ArrayHelper::map($collection, 'id', 'name');
		return $collection;
	}
}