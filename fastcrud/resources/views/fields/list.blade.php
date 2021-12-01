<div class="field field-list">
    <label>{{ $title }}</label>
    <select class="form-input form-select" name="{{ $name }}">
        @foreach($items as $id => $title)
            <option value="{{ $id }}" @if(($value === '*' && $id === '*') || ($value !== '*' && $value == $id)) selected @endif>{{ $title }}</option>
        @endforeach
    </select>
    @if($hint)
        <div class="hint">{!! $hint !!}</div>
    @endif
</div>
