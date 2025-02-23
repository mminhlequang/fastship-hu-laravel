<?php

namespace Modules\Theme\Entities;

use Illuminate\Database\Eloquent\Model;

class DefineKeyword extends Model
{
    protected $table = 'define_keywords';
    protected $primaryKey = 'id';
    protected $fillable = ['key','value'];

    public static function defineKeywordDefault(){
        return config('theme.define_keywords');
    }

    public static function defineKeyword(){
        $defineKeywordConfigs = self::defineKeywordDefault();
        $data = [];
        $defineKeyword = DefineKeyword::get();
        foreach ($defineKeywordConfigs as $sc){
            if($defineKeyword->count()>0) {
                foreach ( $defineKeyword as $s ) {
                    if ( $s->key === $sc['key'] ) {
                        $sc['value'] = $s->value;
                        break;
                    }
                }
            }
            $data[$sc["keyword"]][] = $sc;
        }
        return $data;
    }
    public static function defineKeywordKeyValue(){
        $defineKeywordConfigs = self::defineKeywordDefault();

        $data = [];
        $defineKeyword = DefineKeyword::get();
        foreach ($defineKeywordConfigs as $sc){
            if($defineKeyword->count()>0) {
                foreach ( $defineKeyword as $s ) {
                    if ( $s->key === $sc['key'] ) {
                        $sc['value'] = $s->value;
                        break;
                    }
                }
            }
            $data[$sc['key']] = $sc['value'];
        }
        return $data;
    }
}
