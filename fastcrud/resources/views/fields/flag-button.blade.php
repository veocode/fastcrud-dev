@php
    $class = $isChecked
                ? 'flag-icon flag-on mdi mdi-checkbox-marked'
                : 'flag-icon flag-off mdi mdi-checkbox-blank-outline';
    if ($isToggleable) $class .= ' flag-toggler';
@endphp
<i class="{{ $class }}"
    @if($isToggleable)
        title="Переключить"
        data-flag-toggle="{{ $name }}"
        data-flag-id="{{ $id }}"
    @endif
></i>
