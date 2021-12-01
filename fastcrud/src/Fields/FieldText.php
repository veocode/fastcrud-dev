<?php
namespace Veocode\FastCRUD\Fields;


class FieldText extends Field {

    public function getRenderedInput(){
        return view('fastcrud::fields.text', [
            'name' => $this->getName(),
            'title' => $this->getFormTitle(),
            'hint' => $this->getFormHint(),
            'value' => $this->getValue()
        ]);
    }

    public function getRenderedFilterInput(){
        $valueFromRequest = request()->get($this->getName(), '');
        $this->setValue($valueFromRequest == '*' ? '' : $valueFromRequest);
        return view('fastcrud::fields.string', [
            'name' => $this->getName(),
            'type' => 'text',
            'class' => 'string',
            'buttons' => [],
            'title' => $this->getFormTitle(),
            'hint' => $this->getFormHint(),
            'value' => $this->getValue()
        ]);
    }


}
