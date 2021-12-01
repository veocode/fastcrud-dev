<?php
namespace Veocode\FastCRUD\Fields;

use Illuminate\Http\Request;


abstract class Field {

    protected $name;
    protected $title;
    protected $formTitle;
    protected $formHint;
    protected $isShowInGrid = true;
    protected $isShowInForm = true;
    protected $isVirtual = false;
    protected $isSortable = true;
    protected $isFilterable = false;
    protected $isReadOnly = false;
    protected $isEditableInGrid = false;
    protected $gridInputRenderer;
    protected $nullPlaceholder = '&mdash;';
    protected $showWhen = [];
    protected $hideWhen = [];
    protected $valueFormatter;
    protected $validationRule;
    protected $validationUpdateRule;
    protected $theme;
    protected $default = null;

    protected $value;
    protected $model;

    public static function make($name, $title){
        return new static($name, $title);
    }

    public static function makeWithType($type, $name, $title = '') {
        $pascalCaseType = ucfirst($type);
        $className = "App\\Fields\\Field{$pascalCaseType}";
        return $className::make($name, $title);
    }

    public function __construct($name, $title = ''){
        $this->name = $name;
        $this->title = $title;
        $this->formTitle = $title;
    }

    public function getName(){
        return $this->name;
    }

    public function getTitle(){
        return $this->title;
    }

    public function getFormTitle(){
        return $this->formTitle;
    }
    public function getFormHint(){
        return $this->formHint;
    }

    public function setValue($value){
        $this->value = $value;
        return $this;
    }

    public function getDefault(){
        return $this->default;
    }

    public function setDefault($value){
        $this->default = $value;
        return $this;
    }

    public function setValueFromModel($model){
        $this->model = $model;
        return $this->setValue($model->{$this->name} ?? null);
    }

    public function setValueFromRequestOrModel($model){
        $this->model = $model;
        $valueInRequest = request()->old($this->name);
        return $this->setValue($valueInRequest ?? ($model->{$this->name} ?? null));
    }

    public function getValue(){
        if ($this->valueFormatter && is_callable($this->valueFormatter)){
            return call_user_func($this->valueFormatter, $this->value);
        }
        return $this->value;
    }

    public function setNullPlaceholder($placeholder) {
        $this->nullPlaceholder = $placeholder;
        return $this;
    }

    public function getNullPlaceholder(){
        return $this->nullPlaceholder;
    }

    public function setFormTitle($formTitle){
        $this->formTitle = $formTitle;
        return $this;
    }

    public function setFormHint($formHint){
        $this->formHint = $formHint;
        return $this;
    }

    public function validate($rule) {
        $this->validationRule = $rule;
        $this->validationUpdateRule = $rule;
        return $this;
    }

    public function validateOnCreate($rule) {
        $this->validationRule = $rule;
        return $this;
    }

    public function validateOnUpdate($rule) {
        $this->validationUpdateRule = $rule;
        return $this;
    }

    public function getValidationRule($isUpdating = false) {
        return $isUpdating ? $this->validationUpdateRule : $this->validationRule;
    }

    public function format(callable $formatter){
        $this->valueFormatter = $formatter;
        return $this;
    }

    public function showOnlyInTheme($theme) {
        $this->theme = $theme;
        return $this;
    }

    public function isHiddenInTheme($theme){
        return !empty($this->theme) && $this->theme != $theme;
    }

    public function filterable($isFilterable = true) {
        $this->isFilterable = $isFilterable;
        return $this;
    }

    public function sortable($isSortable = true) {
        $this->isSortable = $isSortable;
        return $this;
    }

    public function hideInGrid(){
        $this->isShowInGrid = false;
        return $this;
    }

    public function hideInForm(){
        $this->isShowInForm = false;
        return $this;
    }

    public function isHiddenInGrid(){
        return !$this->isShowInGrid;
    }

    public function isHiddenInForm(){
        return !$this->isShowInForm;
    }

    public function readOnly(){
        $this->isReadOnly = true;
        return $this;
    }

    public function isReadOnly(){
        return $this->isReadOnly;
    }

    public function editableInGrid(){
        $this->isEditableInGrid = true;
        return $this;
    }

    public function isEditableInGrid(){
        return $this->isEditableInGrid;
    }

    public function virtual(){
        $this->isVirtual = true;
        return $this;
    }

    public function isVirtual(){
        return $this->isVirtual;
    }

    public function isFilterable(){
        return $this->isFilterable;
    }

    public function isNotFilterable(){
        return !$this->isFilterable;
    }

    public function isSortable(){
        return $this->isSortable;
    }

    public function isNotSortable(){
        return !$this->isSortable;
    }

    public function gridInputRenderer($callback) {
        $this->gridInputRenderer = $callback;
        return $this;
    }

    public function showWhen($name, $value = null){
        if (is_array($name)) {
            $this->showWhen += $name;
            return $this;
        }
        $this->showWhen[$name] = $value;
        return $this;
    }

    public function hideWhen($name, $value = null){
        if (is_array($name)) {
            $this->hideWhen += $name;
            return $this;
        }
        $this->hideWhen[$name] = $value;
        return $this;
    }

    public function hasCondition(){
        return !empty($this->showWhen) || !empty($this->hideWhen);
    }

    public function getConditionAttributes() {
        if (!$this->hasCondition()) { return ''; }
        return 'data-show-when="'.http_build_query($this->showWhen).'" data-hide-when="'.http_build_query($this->hideWhen).'"';
    }

    public function prepareValueForSaving($value){
        return $value;
    }

    public function getRenderedValue(){
        $value = $this->getValue();
        if (!$value) { return $this->getNullPlaceholder(); }
        return $this->getValue();
    }

    public function getRenderedInput(){
        return view('fastcrud::fields.string', [
            'name' => $this->getName(),
            'type' => 'text',
            'class' => 'string',
            'title' => $this->getFormTitle(),
            'hint' => $this->getFormHint(),
            'value' => $this->getValue()
        ]);
    }

    public function getRenderedGridInput() {
        if (is_callable($this->gridInputRenderer)) {
            return call_user_func($this->gridInputRenderer, $this, $this->model);
        }

        return $this->getRenderedInput();
    }

    public function getRenderedFilterInput(){
        $valueFromRequest = request()->get($this->getName(), '*');
        $this->setValue($valueFromRequest);
        return $this->getRenderedInput();
    }

    public function addFilterToQuery($query, Request $request) {
        $filterValue = $request->get($this->getName(), '*');
        if (!is_null($filterValue) && $filterValue !== '*') {
            $query->where($this->getName(), $filterValue);
        }
        return $query;
    }

}
