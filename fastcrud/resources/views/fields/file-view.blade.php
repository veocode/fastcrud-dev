<div class="field field-file-view">
    <div class="file-name">
        {{ $viewTitle }}: <a href="{{ $file->url }}" target="_blank">{{ $file->name }}</a>
    </div>
    <div class="file-actions">
        <a href="{{ route('files.delete', $file->id) }}" class="btn">
            <i class="mdi mdi-delete"></i> Удалить файл
        </a>
    </div>
</div>
