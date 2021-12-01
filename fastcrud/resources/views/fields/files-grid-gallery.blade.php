@push('scripts')
    <script src="{{ asset('js/lightbox.min.js') }}"></script>
@endpush
@push('styles')
    <link rel="stylesheet" href="{{ asset('css/lightbox.min.css') }}">
@endpush

<div class="{{ $class }} grid-gallery-preview">
    @foreach($files as $index => $file)
        @if($index == 0)
            <a class="image-link btn" href="{{ $file->getUrl() }}" title="{{ $model->getTitle() }}: {{ $index+1 }}/{{ $files->count() }}">
                <i class="mdi mdi-image-multiple"></i> <span>{{ $files->count() }}</span>
            </a>
        @else
            <a class="image-link" href="{{ $file->getUrl() }}" title="{{ $model->getTitle() }}:  {{ $index+1 }}/{{ $files->count() }}"></a>
        @endif
    @endforeach
</div>
<script>
    $(document).ready(() => $('.{{ $class }} .image-link').simpleLightbox());
</script>
