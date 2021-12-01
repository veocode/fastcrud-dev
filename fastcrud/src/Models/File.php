<?php

namespace Veocode\FastCRUD\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;


class File extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected static function booted() {
        static::deleting(function(File $file) {
            DB::table('files_relations')->where('file_id', $file->id)->delete();
        });
    }

    public function getUrl(){
        return asset($this->url);
    }

    public function unlink() {
        Storage::delete($this->path);
    }
}
