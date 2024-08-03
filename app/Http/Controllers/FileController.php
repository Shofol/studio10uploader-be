<?php

namespace App\Http\Controllers;

use App\Models\Files;
use Faker\Core\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Artisan;

class FileController extends Controller
{
    public function index(Request $request)
    {
        
       
        $files = new Files();
        $filesList = $files->all(); 
        $response['data'] = $filesList;
        $response['status'] = 200;
        return response($response);
    }
    
    
    public function lists(Request $request)
    {
        $response['data'] = array();
        $response['status'] = 500;
        $requestData = $request->all();
        $files = new Files();
        $count = $files->getNumberOfData($requestData);
        $start = $requestData['start'];
        $end = $requestData['end'];
        $perPage = $requestData['per_page'];
        $query= Files::query();
        if(!empty($requestData['search']['field']['file_name'])) {
            $query = $query->where('file_name', 'like', '%'.$requestData['search']['field']['file_name'].'%');
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
            $query = $query->orderBy($requestData['orderby'],$requestData['filter']);
        }
        
        $filesList =  $query->skip($start)->take($perPage)->get();
       
        $page= ceil($count/$requestData['per_page']);
       
        $response['totalPage'] = $page;
        $response['data'] =  $filesList;
        $response['status'] = 200;
        return response($response);
    }
    

    public function fileId() {

        $maxId = Files::max('id');
        return response( $maxId, 200);
    }

    public function store(Request $request) {

       
        $files = new Files();
        
        $recentFile = $files->store($request->all());
        $response['data'] = $recentFile;
        $response['status'] = 200;
        return response($response);

    }

    public function updateFile(Request $request, $id) {      
       
        $updateFile =  Files :: find($id);
        if(isset($request['title'])) 
        $updateFile->title= $request['title'];
        if(isset($request['file_name'])) 
        $updateFile->file_name= $request['file_name'];
        if(isset($request['file_type'])) 
        $updateFile->file_type= $request['file_type'];
        if(isset($request['file_size'])) 
        $updateFile->file_size= $request['file_size'];
        if(isset($request['file_duration'])) 
        $updateFile->file_duration= $request['file_duration'];        
        $updateFile->save();
        $getFile = Files :: find($id); 
        $response['data'] = $getFile; 
        $response['status'] = 200;     
        return response($response);
    }

    public function show( $id)
    {
        $fileInfo = Files :: find($id);

        $response['data'] = $fileInfo; 
        $response['status'] = 200;   
        return response($response, 500);
    }

    public function destroy($id)
    {
        $count=0;
        $ids= explode(",",$id);
        foreach($ids as $item) {
            $count+= Files::where('id',$item)->delete();
        }           
        if($count > 0 ){   
            $response['message'] = "deleted Successfully"; 
            $response['status'] = 200; 
            return response($response);
        }
        else{
            $response['message'] = "delete failed"; 
            $response['status'] = 200; 
            return response($response);
        }
           
    }

    public function searchFile(Request $request) {    
        $requestData = $request->all();
        $results = Files::where('title', $requestData['title'])->get();
        return response($results);
   
    }

    public function upload(Request $request) {    
        
        $response['status'] = 200;
        $file = $request->file('file');
        $fileName = time().'.' . $file->getClientOriginalExtension();
        $destinationPath='files';
        $request->file->move(public_path($destinationPath), $fileName);
        $baseUrl = url('/');
        $response['filePath'] =$baseUrl."/files/".$fileName;
        return response($response);
    }
}
