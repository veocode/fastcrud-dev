<?php
namespace Veocode\FastCRUD\Fields;

use Illuminate\Http\Request;


class FieldString extends Field {

    private $buttons = [];

    public function addInputButton($button) {
        $this->buttons[] = collect($button);
        return $this;
    }

    public function getRenderedInput(){
        return view('fastcrud::fields.string', [
            'name' => $this->getName(),
            'type' => 'text',
            'class' => 'string',
            'buttons' => $this->buttons,
            'title' => $this->getFormTitle(),
            'hint' => $this->getFormHint(),
            'value' => $this->getValue()
        ]);
    }

    public function getRenderedFilterInput(){
        $valueFromRequest = request()->get($this->getName(), '');
        $this->setValue($valueFromRequest == '*' ? '' : $valueFromRequest);
        return $this->getRenderedInput();
    }

    public function addFilterToQuery($query, Request $request) {
        $filterValue = $request->get($this->getName(), '');
        if (!is_null($filterValue) && $filterValue !== '') {
            $query->where($this->getName(), 'ILIKE', "%{$filterValue}%");
        }
        return $query;
    }

}
