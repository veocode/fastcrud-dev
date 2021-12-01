<?php
namespace Veocode\FastCRUD\Fields;


class FieldEmail extends FieldString {

    public function getRenderedValue(){
        $email = $this->getValue();
        if (!$email) { return $this->getNullPlaceholder(); }
        return '<a href="mailto:'.$email.'">'.$email.'</a>';
    }

    public function getRenderedInput(){
        return view('fastcrud::fields.string', [
            'name' => $this->getName(),
            'type' => 'email',
            'class' => 'email',
            'title' => $this->getFormTitle(),
            'hint' => $this->getFormHint(),
            'value' => $this->getValue()
        ]);
    }

}
