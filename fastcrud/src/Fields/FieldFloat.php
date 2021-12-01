<?php
namespace Veocode\FastCRUD\Fields;


class FieldFloat extends Field {

    public function getRenderedInput(){
        return view('fastcrud::fields.string', [
            'name' => $this->getName(),
            'type' => 'string',
            'class' => 'float',
            'title' => $this->getFormTitle(),
            'hint' => $this->getFormHint(),
            'value' => $this->getValue()
        ]);
    }

    public function prepareValueForSaving($value){
        return !is_null($value) && $value !== '' ? floatval($value) : null;
    }

}
