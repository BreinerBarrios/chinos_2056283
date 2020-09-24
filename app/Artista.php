<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Artista extends Model
    {
        protected $table = "artists";
        protected $primaryKey = "ArtistId";
        public $timestamps = false;

        // extender modelo: artisata tiene muchos Albumes
        public function albumes(){
            return $this->hasMany('App\Album' , 'ArtistId');
        }
    }

    

