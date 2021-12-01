<?php
namespace Veocode\FastCRUD\Fields;


class FieldPassword extends FieldString {

    protected $isShowInGrid = false;

    public function getRenderedInput(){
        return view('fastcrud::fields.string', [
            'name' => $this->getName(),
            'type' => 'password',
            'class' => 'password',
            'title' => $this->getFormTitle(),
            'hint' => $this->getFormHint(),
            'value' => $this->getValue()
        ]);
    }

}
