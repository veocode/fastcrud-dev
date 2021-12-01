<div class="field field-text">
    <label>{{ $title }}</label>
    <textarea class="form-input" name="{{ $name }}">{{ $value }}</textarea>
    @if($hint)
        <div class="hint">{!! $hint !!}</div>
    @endif
</div>
