<?php
namespace Veocode\FastCRUD\Fields;


class FieldDateTime extends FieldDate {

    protected $formatView = 'd.m.Y H:i:s';
    protected $formatValue = 'Y-m-d\TH:i:s';

    public function getRenderedInput(){
        return view('fastcrud::fields.string', [
            'name' => $this->getName(),
            'type' => 'datetime-local',
            'class' => 'date',
            'title' => $this->getFormTitle(),
            'hint' => $this->getFormHint(),
            'value' => $this->getInputValue()
        ]);
    }

    public function getRenderedFilterInput(){
        $dateFrom = request()->get($this->getName() . '__from');
        $dateTo = request()->get($this->getName() . '__to');
        return view('fastcrud::fields.date-time-filter', [
            'name' => $this->getName(),
            'title' => $this->getFormTitle(),
            'valueFrom' => $dateFrom,
            'valueTo' => $dateTo
        ]);
    }

}
