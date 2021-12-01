@push('scripts')
    <script src="{{ asset('js/lightbox.min.js') }}"></script>
@endpush
@push('styles')
    <link rel="stylesheet" href="{{ asset('css/lightbox.min.css') }}">
@endpush

<div class="field field-files">
    <label>{{ $title }}</label>
    @if($files && !$isHideList)

        @if($isShowAsGallery)
            <div class="field-files-gallery">
                @foreach($files as $file)
                    <a class="image-link"
                       href="{{ $file->getUrl() }}"
                       style="background-image: url({{ $file->getUrl() }})"
                       title="{{ $file->name }}"></a>
                @endforeach
            </div>
            <script>
                $(document).ready(() => $('.field-files-gallery .image-link').simpleLightbox());
            </script>
        @endif

        @if(! $isShowAsGallery)
            @foreach($files as $file)
                <div class="field-file-view">
                    <div class="file-name">
                        <a href="{{ $file->url }}" target="_blank">{{ $file->name }}</a>
                    </div>
                    <div class="file-actions">
                        <a href="{{ route('files.delete', $file->id) }}" class="btn">
                            <i class="mdi mdi-delete"></i> Удалить
                        </a>
                    </div>
                </div>
            @endforeach
        @endif

    @endif
    <div class="field-file">
        <input class="form-input" type="file" multiple name="{{ $name }}[]">
    </div>
    @if($hint)
        <div class="hint">{!! $hint !!}</div>
    @endif
</div>
