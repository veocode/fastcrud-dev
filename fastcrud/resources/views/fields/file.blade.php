<div class="field field-file">
    <label>{{ $title }}</label>
    <input class="form-input" type="file" name="{{ $name }}">
    @if($hint)
        <div class="hint">{!! $hint !!}</div>
    @endif
</div>
