<?php

namespace App\Traits\Base;

use App\Enums\LanguageEnum;
use App\Services\LanguageService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

trait Translate
{
    public function scopeByLocale($query)
    {

        $defualte_language = LanguageEnum::ENGLISH;

        if(request()->language_id == $defualte_language){


            return $query->join($this->translations, function ($join) {
                $join->on($this->getTable() . '.id', '=', $this->translations . '.' . $this->getForeignKey())
                     ->where($this->translations . '.locale', '=', request()->language_id);


            })
            ->select($this->getTable() . '.*', $this->translations . '.*' , $this->getTable() . ".id");


        }
        //     $defaultLanguage = LanguageEnum::ENGLISH; // Define default language
        $defaultLanguage = LanguageEnum::ENGLISH; // Define default language

       return  $query = $query->leftJoin($this->translations, function($join) use ($defaultLanguage) {
            $join->on($this->getTable() . '.id', '=', $this->translations . '.' . $this->getForeignKey())
                ->where(function($query)  use($defaultLanguage){
                    $query->where($this->translations.'.locale', '=', request()->language_id)
                          ->orWhere(function($query)use($defaultLanguage) {
                              $query->where($this->translations.'.locale', '=', $defaultLanguage)
                                    ->whereNotExists(function($subQuery) {
                                        $subQuery->select(DB::raw(1))
                                                 ->from($this->translations)
                                                 ->whereColumn($this->getTable() . '.id', '=', $this->translations . '.' . $this->getForeignKey())
                                                 ->where($this->translations.'.locale', '=', request()->language_id);
                                    });
                          });
                });
        });

    }


    public  function scopeCreateTranslations($query ,array $data)
    {
        $languages = LanguageService::getAllLanguage();

        foreach ($languages as $language) {
            $translationData = [
                'locale' => $language,
            ];

            foreach ($this->fillable_translation as $field) {
                $translationData[$field] = $data[$field . "_" . $language];
            }

            $this->translations()->create($translationData);
        }
    }

    public  function scopeUpdateTranslations($query ,array $data)
    {

        $languages = LanguageService::getAllLanguage();
        foreach ($languages as $language) {
            $translationData = [
                'locale' => $language,
            ];

            foreach ($this->fillable_translation as $field) {
                $translationData[$field] = $data[$field . "_" . $language];
            }

            if($this->translations()->where('locale',$language)->exists()){

                $this->translations()->where('locale', $language)->update($translationData);
            }else{
                $this->createTranslations($data);

            }
        }
    }
}
