<?php


    namespace App\components;


    class ProductFilter extends QueryFilter
    {

        public function name($value):void
        {
            if($value == null){
                return;
            }
            $value = mb_strtolower($value);
            $sort = $value === 'asc' ? 'asc' : 'desc';
            $this->builder->orderBy('title', $sort);
        }

        public function price($value):void
        {
            if($value == null){
                return;
            }
            $value = mb_strtolower($value);
            $sort = $value === 'asc' ? 'asc' : 'desc';
            $this->builder->orderBy('price', $sort);
        }

    }
