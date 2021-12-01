<?php
namespace Veocode\FastCRUD\Fields;

use Veocode\FastCRUD\Models\File;
use Illuminate\Support\Facades\Storage;


class FieldFiles extends FieldFile {

    protected $formHint = 'Можно перетащить файлы из Проводника на это поле<br>Можно выбрать несколько файлов за раз';
    protected $isHideList = false;
    protected $isShowAsGallery = false;

    public function showAsGallery() {
        $this->isShowAsGallery = true;
        return $this;
    }

    public function hideFilesList() {
        $this->isHideList = true;
        return $this;
    }

    public function getRenderedValue(){
        $files = $this->getValue();
        if (empty($files) || !$files->count()) { return $this->getNullPlaceholder(); }

        if (!$this->isShowAsGallery || !$this->model) { return $files->count(); }

        $links = [];
        foreach($files as $file) {
            $content = "";
            if (empty($links)) $content = '<i class="mdi mdi-image-multiple"></i>';
            $links[] = sprintf('<a href="%s" title="%s" class="image-link">%s</a>', $file->getUrl(), $file->name, $content);
        }

        $class = "gallery-{$this->name}-{$this->model->id}";
        $script = sprintf("$(document).ready(() => $('.{$class} .image-link').simpleLightbox())");

        return view("fastcrud::fields.files-grid-gallery", [
            'class' => $class,
            'files' => $files,
            'model' => $this->model
        ]);
    }

    public function getRenderedInput(){
        $files = $this->getValue();
        return view("fastcrud::fields.files", [
            'name' => $this->getName(),
            'files' => $files,
            'title' => $this->getFormTitle(),
            'viewTitle' => $this->getViewTitle(),
            'hint' => $this->getFormHint(),
            'isHideList' => $this->isHideList,
            'isShowAsGallery' => $this->isShowAsGallery
        ]);
    }

    public function prepareValueForSaving($value){
        return $this->saveUploadedFiles();
    }

    protected function saveUploadedFiles(){
        if(!request()->hasFile($this->getName())) return null;

        $uploadedFiles = request()->file($this->getName());
        if (!$uploadedFiles) { return null; }

        $files = [];
        foreach($uploadedFiles as $file) {
            $name = $file->getClientOriginalName();
            $path = $file->store($this->storagePath, $this->storageDisk);
            $url = Storage::url($path);

            $file = new File();
            $file->fill([
                'name' => $name,
                'path' => $path,
                'url' => $url
            ]);
            $file->save();

            $files[] = $file;
        }

        return $files;
    }

}
