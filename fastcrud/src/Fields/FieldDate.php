<?php
namespace Veocode\FastCRUD\Fields;

use Carbon\Carbon;
use Illuminate\Http\Request;


class FieldDate extends FieldString {

    protected $formatView = 'd.m.Y';
    protected $formatValue = 'Y-m-d';
    protected $formatter;

    public function setFormatter($formatter) {
        $this->formatter = $formatter;
        return $this;
    }

    public function formatDate($value){
        if (is_callable($this->formatter)){
            return call_user_func($this->formatter, $value);
        }
        return Carbon::make($value)->format($this->formatView);
    }

    public function getRenderedValue() {
        $value = $this->getValue();
        if (!$value) {
            return parent::getRenderedValue();
        }
        return $this->formatDate($value);
    }

    public function getRenderedInput(){
        return view('fastcrud::fields.string', [
            'name' => $this->getName(),
            'type' => 'date',
            'class' => 'date',
            'title' => $this->getFormTitle(),
            'hint' => $this->getFormHint(),
            'value' => $this->getInputValue()
        ]);
    }

    public function getInputValue(){
        $value = $this->getValue();
        if (!$value) { return ''; }
        if ($value instanceof Carbon){
            return $value->format($this->formatValue);
        }
        return $value;
    }

    public function getRenderedFilterInput(){
        $dateFrom = request()->get($this->getName() . '__from');
        $dateTo = request()->get($this->getName() . '__to');
        return view('fastcrud::fields.date-filter', [
            'name' => $this->getName(),
            'title' => $this->getFormTitle(),
            'valueFrom' => $dateFrom,
            'valueTo' => $dateTo
        ]);
    }

    public function addFilterToQuery($query, Request $request) {
        $filterDateFrom = $request->get($this->getName() . '__from');
        $filterDateTo = $request->get($this->getName() . '__to');
        if ($filterDateFrom) {
            $query->where($this->getName(), '>=', Carbon::make($filterDateFrom));
        }
        if ($filterDateTo) {
            $query->where($this->getName(), '<=', Carbon::make($filterDateTo));
        }
        return $query;
    }

}
