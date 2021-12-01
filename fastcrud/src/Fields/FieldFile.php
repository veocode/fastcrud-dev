<?php
namespace Veocode\FastCRUD\Fields;

use Veocode\FastCRUD\Models\File;
use Illuminate\Support\Facades\Storage;


class FieldFile extends Field {

    protected $viewTitle = 'Файл';
    protected $storageDisk = 'public';
    protected $storagePath = '';
    protected $resultAttribute = 'id';

    public function setViewTitle($viewTitle){
        $this->viewTitle = $viewTitle;
        return $this;
    }

    public function getViewTitle(){
        return $this->viewTitle;
    }

    public function setResultAttribute($attributeName) {
        $this->resultAttribute = $attributeName;
        return $this;
    }

    public function setStorageDisk($storageDisk) {
        $this->storagePath = $storageDisk;
        return $this;
    }

    public function getStorageDisk(){
        return $this->storageDisk;
    }

    public function setStoragePath($storagePath) {
        $this->storagePath = $storagePath;
        return $this;
    }

    public function getStoragePath(){
        return $this->storagePath;
    }

    public function getRenderedValue(){
        $file = $this->getValue();
        if (!$file) { return $this->getNullPlaceholder(); }
        return $file->name;
    }

    public function getRenderedInput(){
        $fileId = $this->getValue();
        $file = $fileId ? File::find($fileId) : null;

        $view = $file ? 'file-view' : 'file';

        return view("fastcrud::fields.{$view}", [
            'name' => $this->getName(),
            'file' => $file,
            'title' => $this->getFormTitle(),
            'viewTitle' => $this->getViewTitle(),
            'hint' => $this->getFormHint(),
        ]);
    }

    public function prepareValueForSaving($value){
        return $this->saveUploadedFile();
    }

    protected function saveUploadedFile(){
        $file = request()->file($this->getName());
        if (!$file) { return null; }

        $name = $file->getClientOriginalName();
        $path = request()->file($this->getName())->store($this->storagePath, $this->storageDisk);
        $url = Storage::url($path);

        $file = new File();
        $file->fill([
            'name' => $name,
            'path' => $path,
            'url' => $url
        ]);
        $file->save();

        return $file->{$this->resultAttribute};
    }

}
