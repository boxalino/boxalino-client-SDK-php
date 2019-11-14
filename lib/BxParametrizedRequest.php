<?php

namespace com\boxalino\bxclient\v1;

class BxParametrizedRequest extends BxRequest
{
	private $bxReturnFields = array('id');
	private $getItemFieldsCB = null;
	
	private $requestParametersPrefix = "";
	private $requestWeightedParametersPrefix = "bxrpw_";
	private $requestFiltersPrefix = "bxfi_";
	private $requestFacetsPrefix = "bxfa_";
	private $requestSortFieldPrefix = "bxsf_";
	
	private $requestReturnFieldsName = "bxrf";
	private $requestContextItemFieldName = "bxcif";
	private $requestContextItemFieldValues = "bxciv";

	protected $requestParameterExclusionPatterns = [];
	
	public function __construct($language, $choiceId, $max=10, $min=0, $bxReturnFields=null, $getItemFieldsCB=null) {
		parent::__construct($language, $choiceId, $max, $min);
		
		if($bxReturnFields != null) {
			$this->bxReturnFields = $bxReturnFields;
		}
		$this->getItemFieldsCB = $getItemFieldsCB;
	}
	
	public function setRequestParametersPrefix($requestParametersPrefix) {
		$this->requestParametersPrefix = $requestParametersPrefix;
	}
	
	public function getRequestParametersPrefix() {
		return $this->requestParametersPrefix;
	}
	
	public function setRequestWeightedParametersPrefix($requestWeightedParametersPrefix) {
		$this->requestWeightedParametersPrefix = $requestWeightedParametersPrefix;
	}
	
	public function getRequestWeightedParametersPrefix() {
		return $this->requestWeightedParametersPrefix;
	}
	
	public function setRequestFiltersPrefix($requestFiltersPrefix) {
		$this->requestFiltersPrefix = $requestFiltersPrefix;
	}
	
	public function getRequestFiltersPrefix() {
		return $this->requestFiltersPrefix;
	}
	
	public function setRequestFacetsPrefix($requestFacetsPrefix) {
		$this->requestFacetsPrefix = $requestFacetsPrefix;
	}
	
	public function getRequestFacetsPrefix() {
		return $this->requestFacetsPrefix;
	}
	
	public function setRequestSortFieldPrefix($requestSortFieldPrefix) {
		$this->requestSortFieldPrefix = $requestSortFieldPrefix;
	}
	
	public function getRequestSortFieldPrefix() {
		return $this->requestSortFieldPrefix;
	}
	
	public function setRequestReturnFieldsName($requestReturnFieldsName) {
		$this->requestReturnFieldsName = $requestReturnFieldsName;
	}
	
	public function getRequestReturnFieldsName() {
		return $this->requestReturnFieldsName;
	}
	
	public function setRequestContextItemFieldName($requestContextItemFieldName) {
		$this->requestContextItemFieldName = $requestContextItemFieldName;
	}
	
	public function getRequestContextItemFieldName() {
		return $this->requestContextItemFieldName;
	}
	
	public function setRequestContextItemFieldValues($requestContextItemFieldValues) {
		$this->requestContextItemFieldValues = $requestContextItemFieldValues;
	}
	
	public function getRequestContextItemFieldValues() {
		return $this->requestContextItemFieldValues;
	}
	
	public function getPrefixes() {
		return array($this->requestParametersPrefix, $this->requestWeightedParametersPrefix, $this->requestFiltersPrefix, $this->requestFacetsPrefix, $this->requestSortFieldPrefix);
	}
	
	public function matchesPrefix($string, $prefix, $checkOtherPrefixes=true) {
		if($checkOtherPrefixes) {
			foreach($this->getPrefixes() as $p) {
				if($p == $prefix) {
					continue;
				}
				if(strlen($prefix) < strlen($p) && strpos($string, $p) === 0) {
					return false;
				}
			}
		}
		return $prefix == null || strpos($string, $prefix) === 0;
	}

	public function getPrefixedParameters($prefix, $checkOtherPrefixes=true) {
		$params = [];
		if(!is_array($this->requestMap)) {
			return [];
		}
		foreach($this->requestMap as $k => $v) {
			if($this->matchesPrefix($k, $prefix, $checkOtherPrefixes)) {
				$params[substr($k, strlen($prefix))] = $v;
			}
		}
		return $params;
	}

	public function getContextItems() {
		$contextItemFieldName = null;
		$contextItemFieldValues = [];
		$params = $this->getPrefixedParameters($this->requestParametersPrefix, false);
		foreach($params as $name => $values) {
			if($name == $this->requestContextItemFieldName) {
				$value = $values;
				if(is_array($value) && sizeof($value) > 0) {
					$value = $values[0];
				}
				$contextItemFieldName = $value;
				continue;
			}if($name == $this->requestContextItemFieldValues) {
				$value = $values;
				if(!is_array($value)) {
					$value = explode(',', $values);
				}
				$contextItemFieldValues = $value;
				continue;
			}
			$params[$name] = $values;
		}
		if($contextItemFieldName) {
			foreach($contextItemFieldValues as $contextItemFieldValue) {
				$this->setProductContext($contextItemFieldName, $contextItemFieldValue);
			}
		}
		return parent::getContextItems();
	}

    public function getRequestParameterExclusionPatterns() {
        return $this->requestParameterExclusionPatterns;
    }

    public function addRequestParameterExclusionPatterns($pattern) {
        $this->requestParameterExclusionPatterns[] = $pattern;
    }

    public function getRequestContextParameters() {
		$params = [];
		foreach($this->getPrefixedParameters($this->requestWeightedParametersPrefix) as $name => $values) {
			$params[$name] = $values;
		}
		foreach($this->getPrefixedParameters($this->requestParametersPrefix, false) as $name => $values) {
			if(strpos($name, $this->requestWeightedParametersPrefix) !== false) {
				continue;
			}
			if(strpos($name, $this->requestFiltersPrefix) !== false) {
				continue;
			}
			if(strpos($name, $this->requestFacetsPrefix) !== false) {
				continue;
			}
			if(strpos($name, $this->requestSortFieldPrefix) !== false) {
				continue;
			}
			if($name == $this->requestReturnFieldsName) {
				continue;
			}
			$params[$name] = $values;
		}
		unset($params['bxi_data_owner_expert']);
		return $params;
	}
	
	public function getWeightedParameters() {
		$params = [];
		foreach($this->getPrefixedParameters($this->requestWeightedParametersPrefix) as $name => $values) {
			$pieces = explode('_', $name);
			$fieldValue = "";
			if(sizeof($pieces) > 0) {
				$fieldValue = $pieces[sizeof($pieces)-1];
				unset($pieces[sizeof($pieces)-1]);
			}
			$fieldName = implode('_', $pieces);
			if(!isset($params[$fieldName])) {
				$params[$fieldName] = [];
			}
			$params[$fieldName][$fieldValue] = $values;
			
		}
		return $params;
	}
	
	public function getFilters() {
		$filters = parent::getFilters();
		foreach($this->getPrefixedParameters($this->requestFiltersPrefix) as $fieldName => $value) {
			$negative = false;
			if(strpos($value, '!')===0) {
				$negative = true;
				$value = substr($value, 1);
			}
			$filters[] = new BxFilter($fieldName, array($value), $negative);
		}
		return $filters;
	}
	
	public function getFacets() {
		$facets = parent::getFacets();
		if($facets == null) {
			$facets = new BxFacets();
		}
		foreach($this->getPrefixedParameters($this->requestFacetsPrefix) as $fieldName => $selectedValue) {
			$facets->addFacet($fieldName, $selectedValue);
		}
		return $facets;
	}
	
	public function getSortFields() {
		$sortFields = parent::getSortFields();
		if($sortFields == null) {
			$sortFields = new BxSortFields();
		}
		foreach($this->getPrefixedParameters($this->requestSortFieldPrefix) as $name => $value) {
			$sortFields->push($name, $value);
		}
		return $sortFields;
	}
	
	public function getReturnFields() {
		return array_unique(array_merge(parent::getReturnFields(), $this->bxReturnFields));
	}
	
	public function getAllReturnFields() {
		$returnFields = $this->getReturnFields();
		if(isset($this->requestMap[$this->requestReturnFieldsName])) {
			$returnFields = array_unique(array_merge($returnFields, explode(',', $this->requestMap[$this->requestReturnFieldsName])));
		}
		return $returnFields;
	}
	
	private $callBackCache = null;
	public function retrieveFromCallBack($items, $fields) {
		if($this->callBackCache === null) {
			$this->callBackCache = [];
			$ids = [];
			foreach($items as $item) {
				$ids[] = $item->values['id'][0];
			}
			$itemFields = call_user_func($this->getItemFieldsCB, $ids, $fields);
			if(is_array($itemFields)) {
				$this->callBackCache = $itemFields;
			}
		}
		return $this->callBackCache;
	}
	
	public function retrieveHitFieldValues($item, $field, $items, $fields) {
		$itemFields = $this->retrieveFromCallBack($items, $fields);
		if(isset($itemFields[$item->values['id'][0]][$field])) {
			return $itemFields[$item->values['id'][0]][$field];
		}
		return parent::retrieveHitFieldValues($item, $field, $items, $fields);
	}
}
