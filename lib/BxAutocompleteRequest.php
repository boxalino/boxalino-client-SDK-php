<?php

namespace com\boxalino\bxclient\v1;

class BxAutocompleteRequest
{
	protected $language;
	protected $queryText;
	protected $choiceId;
	protected $textualSuggestionsHitCount;
	protected $bxSearchRequest;
	protected $highlight;
	protected $highlightPre;
	protected $highlightPost;
	
	protected $indexId = null;
	
	public function __construct($language, $queryText, $textualSuggestionsHitCount, $productSuggestionHitCount = 5, $autocompleteChoiceId = 'autocomplete', $searchChoiceId = 'search', $highlight = true, $highlightPre = '<em>', $highlightPost = '</em>') {
		$this->language = $language;
		$this->queryText = $queryText;
		$this->textualSuggestionsHitCount = $textualSuggestionsHitCount;
		$this->highlight = $highlight;
		$this->highlightPre = $highlightPre;
		$this->highlightPost = $highlightPost;
		if($autocompleteChoiceId == null){
			$autocompleteChoiceId = 'autocomplete';
		}
		$this->choiceId = $autocompleteChoiceId;
		$this->bxSearchRequest = new BxSearchRequest($language, $queryText, $productSuggestionHitCount, $searchChoiceId);
	}

	public function getBxSearchRequest() {
		return $this->bxSearchRequest;
	}
	
	public function setBxSearchRequest($bxSearchRequest) {
		$this->bxSearchRequest = $bxSearchRequest;
	}

	public function getLanguage() {
		return $this->language;
	}
	
	public function setLanguage($language) {
		$this->language = $language;
	}
	
	public function getQuerytext() {
		return $this->queryText;
	}
	
	public function setQuerytext($queryText) {
		$this->queryText = $queryText;
	}
	
	public function getChoiceId() {
		return $this->choiceId;
	}
	
	public function setChoiceId($choiceId) {
		$this->choiceId = $choiceId;
	}
	
	public function getTextualSuggestionHitCount() {
		return $this->textualSuggestionsHitCount;
	}
	
	public function setTextualSuggestionHitCount($textualSuggestionsHitCount) {
		$this->textualSuggestionsHitCount = $textualSuggestionsHitCount;
	}

	public function getIndexId() {
		return $this->indexId;
	}
	
	public function setIndexId($indexId) {
		$this->indexId = $indexId;
	}
	
	public function setDefaultIndexId($indexId) {
		if($this->indexId == null) {
			$this->setIndexId($indexId);
		}
		$this->bxSearchRequest->setDefaultIndexId($indexId);
	}
	
	public function getHighlight() {
		return $this->highlight;
	}
	
	public function getHighlightPre() {
		return $this->highlightPre;
	}
	
	public function getHighlightPost() {
		return $this->highlightPost;
	}
	
	private function getAutocompleteQuery() {
		$autocompleteQuery = new \com\boxalino\p13n\api\thrift\AutocompleteQuery();
		$autocompleteQuery->indexId = $this->getIndexId();
		$autocompleteQuery->language = $this->language;
		$autocompleteQuery->queryText = $this->queryText;
		$autocompleteQuery->suggestionsHitCount = $this->textualSuggestionsHitCount;
		$autocompleteQuery->highlight = $this->highlight;
		$autocompleteQuery->highlightPre = $this->highlightPre;
		$autocompleteQuery->highlightPost = $this->highlightPost;
		return $autocompleteQuery;
	}
	
	private $propertyQueries = [];
	public function addPropertyQuery($field, $hitCount, $evaluateTotal=false) {
		$propertyQuery = new \com\boxalino\p13n\api\thrift\PropertyQuery();
		$propertyQuery->name = $field;
		$propertyQuery->hitCount = $hitCount;
		$propertyQuery->evaluateTotal = $evaluateTotal;
		$this->propertyQueries[] = $propertyQuery;
	}
	
	public function resetPropertyQueries() {
		$this->propertyQueries = [];
	}
	
	public function getAutocompleteThriftRequest($profileid, $thriftUserRecord) {
		$autocompleteRequest = new \com\boxalino\p13n\api\thrift\AutocompleteRequest();
		$autocompleteRequest->userRecord = $thriftUserRecord;
		$autocompleteRequest->profileId = $profileid;
		$autocompleteRequest->choiceId = $this->choiceId;
		$autocompleteRequest->searchQuery = $this->bxSearchRequest->getSimpleSearchQuery();
        $autocompleteRequest->searchChoiceId = $this->bxSearchRequest->getChoiceId();
		$autocompleteRequest->autocompleteQuery = $this->getAutocompleteQuery();
		
		if(sizeof($this->propertyQueries)>0) {
			$autocompleteRequest->propertyQueries = $this->propertyQueries;
		}
		
		return $autocompleteRequest;
	}

}
