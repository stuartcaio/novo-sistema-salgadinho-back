<?php

namespace App\Http\Controllers\Traits;

use Illuminate\Http\Request;
use DB;

trait DatabaseFunctions{
    private string $table;

    protected function __dbConstruct(string $table){
        $this->table = $table;
    }

    public function getAll(){
        return DB::table($this->table)
                 ->select()
                 ->get();
    }

    public function FindByInsertedOneProperty(string $property, $value){
        return DB::table($this->table)
                 ->select()
                 ->where("$this->table.$property", $value)
                 ->first();
    }

    public function FindByInsertedProperties(array $properties, array $inputValues){
        $item = DB::table($this->table)->select();

        foreach($properties as $key => $value){
            if($key === 0){
                $item->where($value, $inputValues[0]);
            }

            if($key === count($inputValues) - 1){
                return $item->orWhere("$this->table.$value", $inputValues[$key])
                            ->first();
            }

            if($key !== 0){
                $item->orWhere($value, $inputValues[$key]);
            }
        }
    }

    public function save(array $properties){
        $convertedPropertiesArrayToSave = [];

        foreach($properties as $key => $value){
            $convertedPropertiesArrayToSave[$key] = $value;
        }

        return DB::table($this->table)
                 ->insert($convertedPropertiesArrayToSave);
    }

    public function updateByID(array $properties, int $id){
        $convertedPropertiesArrayToSave = [];

        foreach($properties as $key => $value){
            $convertedPropertiesArrayToSave[$key] = $value;
        }

        return DB::table($this->table)
                 ->where("$this->table.id", $id)
                 ->update($convertedPropertiesArrayToSave);
    }

    public function delete(int $id){
        return DB::table($this->table)->where("id", $id)->delete();
    }
}