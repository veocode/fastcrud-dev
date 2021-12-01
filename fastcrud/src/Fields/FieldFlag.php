<?php
namespace Veocode\FastCRUD\Fields;


class FieldFlag extends Field {

    protected $toggleableInGrid = false;

    public function isChecked(){
        $value = $this->getValue();
        $isChecked = !is_null($value) && $value;
        return $isChecked;
    }

    public function toggleableInGrid() {
        $this->toggleableInGrid = true;
        return $this;
    }

    public function prepareValueForSaving($value){
        return request()->has($this->getName());
    }

    public function getRenderedValue(){
        return view('fastcrud::fields.flag-button', [
            'isChecked' => $this->isChecked(),
            'isToggleable' => $this->toggleableInGrid,
            'name' => $this->getName(),
            'id' => $this->model->id ?? 0
        ]);
    }

    public function getRenderedInput(){
        return view('fastcrud::fields.flag', [
            'name' => $this->getName(),
            'title' => $this->getFormTitle(),
            'isChecked' => $this->isChecked()
        ]);
    }

}
