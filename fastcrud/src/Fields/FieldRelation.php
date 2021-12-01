<?php
namespace Veocode\FastCRUD\Fields;


class FieldRelation extends FieldList {

    protected $modelClass;
    protected $relatedFieldName;
    protected $relationTitleAttributeName;

    protected $linkRoute;

    public function to($parentModelClass, $relatedFieldName, $relationTitleAttributeName = 'title'){
        $this->modelClass = $parentModelClass;
        $this->relatedFieldName = $relatedFieldName;
        $this->relationTitleAttributeName = $relationTitleAttributeName;

        $items = $parentModelClass::getFlatList(true, $relationTitleAttributeName);
        $this->setItems($items);
        return $this;
    }

    public function linkRoute($routeName) {
        $this->linkRoute = $routeName;
        return $this;
    }

    public function getRenderedValue(){
        if (empty($this->model)){
            return parent::getRenderedValue();
        }
        $relatedModel = $this->model->{$this->relatedFieldName};
        if (!$relatedModel) { return $this->getNullPlaceholder(); }

        $value = $relatedModel->{$this->relationTitleAttributeName} ?? null;
        if (!$value) { return $this->getNullPlaceholder(); }

        if ($this->linkRoute){
            $linkUrl = route($this->linkRoute, ['id' => $relatedModel->id]);
            return sprintf('<a href="%s" class="relation-link">%s</a>', $linkUrl, $value);
        }
        return $value;
    }

}
