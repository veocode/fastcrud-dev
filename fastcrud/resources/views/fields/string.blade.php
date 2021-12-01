<div class="field field-{{ $class }} field-string-{{ $type }}">
    <label>{{ $title }}</label>
    <div class="form-input-wrapper">
        <input class="form-input" type="{{ $type }}" name="{{ $name }}" value="{{ $value }}">
        @if(!empty($buttons))
            <div class="input-buttons">
                @foreach($buttons as $btn)
                    <button id="{{ $btn->get('id') }}" class="btn btn-input"><i class="mdi mdi-{{ $btn->get('icon') }}"></i> {{ $btn->get('title') }}</button>
                @endforeach
            </div>
        @endif
    </div>
    @if($hint)
        <div class="hint">{!! $hint !!}</div>
    @endif
</div>
