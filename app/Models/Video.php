<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Video extends Model {

    protected $table       = 'videos';
    protected $primaryKey   = 'id';

    protected $fillable     = [
        'lesson_id','vimeo_id','title','hash','status'
    ];

    public  function getUriAttribute()
    {

        return '/videos/'.$this->getAttribute('vimeo_id');
    }
    public function getDescriptionStatusAttribute()
    {
        if($this->status=='processing'){
            return 'Vimeo está optimizando el video. Estará listo en unos minutos';
        }
        if($this->status=='uploading'){
            return 'El video se está subiendo a la nube de vimeo ...';
        }
        if($this->status=='error'){
            return 'Hubo un error al procesar el video. Vuelva a subirlo';
        }
        return 'video listo';
    }
    public function getLinkAttribute()
    {
        return 'https://vimeo.com/'.$this->vimeo_id.'/'.$this->hash;
    }
    public function getEmbedUrlAttribute()
    {
        return 'https://player.vimeo.com/video/'.$this->vimeo_id.'?h='.$this->hash;
    }
   

}