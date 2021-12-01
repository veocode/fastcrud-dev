<?php
namespace Veocode\FastCRUD\Fields;

use Illuminate\Support\Collection;


class FieldList extends Field {

    protected $items = [];

    public function getRenderedInput(){
        return view('fastcrud::fields.list', [
            'name' => $this->getName(),
            'title' => $this->getFormTitle(),
            'hint' => $this->getFormHint(),
            'items' => $this->items,
            'value' => $this->getValue()
        ]);
    }

    public function getRenderedValue(){
        $value = $this->getValue();
        if (isset($this->items[$value])){
            $cssClass = "{$this->getName()}-{$value}";
            return '<span class="' . $cssClass . '">' . $this->items[$value] . '</span>';
        }
        return '&mdash;';
    }

    public function getRenderedFilterInput(){
        $items = $this->items;

        $firstItem = reset( $items );
        unset( $items[ key($items) ]);

        if (!empty($firstItem)){
            $this->items = ['*' => '---'] + $this->items;
        } else {
            $this->items = ['*' => '---'] + $items;
        }
        return parent::getRenderedFilterInput();
    }

    public function setItems($items = []){
        $this->items = $items instanceof Collection ? $items->toArray() : $items;
        return $this;
    }

}
