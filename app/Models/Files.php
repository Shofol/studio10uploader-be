<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Files extends Model
{
    use HasFactory;

    public function allFiles() {
        return Files::all();
    }

    public function store($request) {       
     
        $file = new Files();
        $file->title = $request['title'];
        $file->file_name = $request['file_name'];
        $file->file_type = $request['file_type'];
        $file->file_size = $request['file_size'];
        $file->file_duration = $request['file_duration'];
        $file->save();
        $fileId= $file->id;

        $recentFile = Files :: find($fileId);
        return $recentFile;
  
      }

      public function getNumberOfData($requestData) {       
        
        
        if(empty($requestData['search'])) {

            $results = Files::orderBy($requestData['orderby'], $requestData['filter'])->get();
            return $results->count();
        } else {
            $query= Files::query();
            
            if(!empty($requestData['search']['field']['file_name'])) {
                $query->where('file_name', 'like', '%'.$requestData['search']['field']['file_name'].'%');
            }
    
            if(!empty($requestData['search']['field']['file_type'])) {
                $query = $query->where('file_type', 'like', '%'.$requestData['search']['field']['file_type'].'%');
            }
    
            if(!empty($requestData['search']['field']['file_size'])) {
                $query = $query->where('file_size', '=', $requestData['search']['field']['file_size']);
            }
    
            if(!empty($requestData['search']['field']['file_duration'])) {
                $query = $query->where('file_duration', '=', $requestData['search']['field']['file_duration']);
            }
    
            if(!empty($requestData['search']['field']['title'])) {
                $query = $query->where('title', 'like', '%'.$requestData['search']['field']['title'].'%');
            }
    
    
            if(!empty($requestData['orderby']) && !empty($requestData['filter']) ) 
            {
                $field = $requestData['orderby'];
                $filter = $requestData['filter'];
                $query = $query->orderBy($field,$filter);
            }
             
           

            $filesList =  $query->get();
            $count =  $filesList->count();
            return $count;
    
        }

  }
}
