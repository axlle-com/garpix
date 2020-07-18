<?php


    namespace App\components;


    abstract class QueryFilter
    {
        protected $request;
        protected $builder;

        public function __construct($builder,$request)
        {
            $this->builder = $builder;
            $this->request = $request;
        }

        public function apply()
        {
            foreach ($this->request as $filter => $value){
                if(method_exists($this,$filter)){
                    $this->$filter($value);
                }
            }
            return $this->builder;
        }


    }
