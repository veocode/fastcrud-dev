<?php
namespace Veocode\FastCRUD\Fields;


class FieldInt extends Field {

    public function getRenderedInput(){
        return view('fastcrud::fields.string', [
            'name' => $this->getName(),
            'type' => 'number',
            'class' => 'int',
            'title' => $this->getFormTitle(),
            'hint' => $this->getFormHint(),
            'value' => $this->getValue()
        ]);
    }

    public function prepareValueForSaving($value){
        return !is_null($value) && $value !== '' ? intval($value) : null;
    }

}
