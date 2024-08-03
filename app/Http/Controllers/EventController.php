<?php

namespace App\Http\Controllers;

use App\Models\AfterEvent;
use App\Models\AfterGroupEvent;
use App\Models\Events;
use App\Models\BeforeEvent;
use App\Models\BreakEvent;
use App\Models\BreakGroupEvent;
use App\Models\FirstHalfEvent;
use App\Models\FirstHalfGroupEvent;
use App\Models\GroupEvent;
use App\Models\SecondHalfEvent;
use App\Models\SecondHalfGroupEvent;
use Illuminate\Http\Request;

class EventController extends Controller
{
    
    
    public function __construct()
    {
  
    }
    
    
    public function index(Request $request)
    {
        $data = Events::all();
        for($i=0; $i<count($data); $i++) {
           $eventId = $data[$i]->id;
           $allEvents[] = $this->eventDetails($eventId);
        }

        $response['data'] = $allEvents;
        $response['status'] = 200;
        return response($response);
    }

    public function list(Request $request) {
        
        $response['status'] = 200;
        $responseData =[];
        $requestData = $request->all();
        //$files = new Events();
        $allEvents = Events::all();
        $count = $allEvents->count();
        $start = $requestData['start'];
        $perPage = $requestData['per_page'];
        $page= ceil($count/$requestData['per_page']);
        $response['totalPage'] = $page;
        $query= Events::query();
        $filesList =  $query->skip($start)->take($perPage)->get();
        $numberOfData = $filesList->count();
        $response['data'] = [];
        for($i=0; $i<$numberOfData; $i++) {
            $eventId = $filesList[$i]->id;
            $eventDetails = $this->eventDetails($eventId);
            $responseData[] = $eventDetails;
        }

        $response['data'] =  $responseData;
        return response($response);

    }

    public function store(Request $request, Events $event, BeforeEvent $beforeEvent, GroupEvent $groupEvent,
     FirstHalfEvent $firstHalfEvent, FirstHalfGroupEvent $firstHalfGroupEvent, BreakEvent $breakEvent,
     BreakGroupEvent $breakGroupEvent, SecondHalfEvent  $secondHalfEvent, SecondHalfGroupEvent $secondHalfGroupEvent,
     AfterEvent  $afterEvent, AfterGroupEvent $afterGroupEvent)
    {     
        $requestData = $request->all();
        $eventId = $event->store($requestData);
        foreach($requestData['schedule']['beforeGame'] as $schedule) {
            $beforeEvent->store($schedule,$eventId);
        }
        
        foreach($requestData['schedule']['firstHalf'] as $firstHalf) {
            $firstHalfEvent->store($firstHalf,$eventId);
        }

        
        foreach($requestData['schedule']['break'] as $breakEvents) {
            $breakEvent->store($breakEvents,$eventId);
        }


        foreach($requestData['schedule']['secondHalf'] as $secondHalf) {
            $secondHalfEvent->store($secondHalf,$eventId);
        }

        
        foreach($requestData['schedule']['afterGame'] as $afterGame) {
            $afterEvent->store($afterGame,$eventId); 
        }


        $latestEvent = $event->getSingleEvent($eventId);
        $beforeEventList = $beforeEvent->getBeforeEvent($eventId);
        for($i=0; $i<count($beforeEventList); $i++) {
            $children= $groupEvent->getGroupEvent($beforeEventList[$i]->id);
            $beforeEventList[$i]['children'] = $children;
        }

        $recentEvent['beforeGame'] = $beforeEventList;

        $firstHalfEventList = $firstHalfEvent->getFirstHalfEvent($eventId);
        for($i=0; $i<count($firstHalfEventList); $i++) {
            $children= $firstHalfGroupEvent->getFirstHalfGroupEvent($firstHalfEventList[$i]->id);
            $firstHalfEventList[$i]['children'] = $children;
        }
        $recentEvent['firstHalf'] = $firstHalfEventList;

        $breakEventList = $breakEvent->getBreakEvent($eventId);
        for($i=0; $i<count($breakEventList); $i++) {
            $children= $breakGroupEvent->getBreakGroupEvent($breakEventList[$i]->id);
            if(isset($children)) {
                $breakEventList[$i]['children'] = $children;
            }
            
        }
        $recentEvent['break'] = $breakEventList;


        $secondHalfList = $secondHalfEvent->getSecondHalfEvent($eventId);
        for($i=0; $i<count($secondHalfList); $i++) {
            $children= $secondHalfGroupEvent->getSecondHalfGroupEvent($secondHalfList[$i]->id);
            if(isset($children)) {
                $secondHalfList[$i]['children'] = $children;
            }
            
        }
        $recentEvent['secondHalf'] = $secondHalfList;


        $afterEventList = $afterEvent->getAfterEvent($eventId);
        for($i=0; $i<count($afterEventList); $i++) {
            $children= $afterGroupEvent->getAfterGroupEvent($afterEventList[$i]->id);
            if(isset($children)) {
                $afterEventList[$i]['children'] = $children;
            }
            
        }
        $recentEvent['afterGame'] = $afterEventList;   
        $latestEvent['schedule'] = $recentEvent;  
        $response['data'] =  $latestEvent;
        $response['status'] = 200;
        return response($response); 
    }


    public function show(Request $request) {
        
        $requestData = $request->all();
        $eventId = $requestData['id'];  
        $recentEvent = $this->eventDetails($eventId);  
        $response['data'] = $recentEvent;
        $response['status'] = 200;
        return response($response);      
    }

    public function eventDetails($eventId) {

        $event = new Events();
        $latestEvent = $event->getSingleEvent($eventId);

        $beforeEvent = new BeforeEvent();
        $groupEvent = new GroupEvent();
        $beforeEventList = $beforeEvent->getBeforeEvent($eventId);
       
        for($i=0; $i<count($beforeEventList); $i++) {
            $children= $groupEvent->getGroupEvent($beforeEventList[$i]->id);
            $beforeEventList[$i]['children'] = $children;
        }
        $recentEvent['beforeGame'] = $beforeEventList;
        

        $firstHalfEvent = new FirstHalfEvent();
        $firstHalfGroupEvent = new FirstHalfGroupEvent();
        $firstHalfEventList = $firstHalfEvent->getFirstHalfEvent($eventId);
        for($i=0; $i<count($firstHalfEventList); $i++) {
            $children= $firstHalfGroupEvent->getFirstHalfGroupEvent($firstHalfEventList[$i]->id);
            $firstHalfEventList[$i]['children'] = $children;
        }
        $recentEvent['firstHalf'] = $firstHalfEventList;
        
        $breakEvent= new BreakEvent();
        $breakGroupEvent= new BreakGroupEvent();
        $breakEventList = $breakEvent->getBreakEvent($eventId);
        for($i=0; $i<count($breakEventList); $i++) {
            $children= $breakGroupEvent->getBreakGroupEvent($breakEventList[$i]->id);
            if(isset($children)) {
                $breakEventList[$i]['children'] = $children;
            }
            
        }
        $recentEvent['break'] = $breakEventList;

        $secondHalfEvent= new SecondHalfEvent();
        $secondHalfGroupEvent= new SecondHalfGroupEvent();
        $secondHalfList = $secondHalfEvent->getSecondHalfEvent($eventId);
        for($i=0; $i<count($secondHalfList); $i++) {
            $children= $secondHalfGroupEvent->getSecondHalfGroupEvent($secondHalfList[$i]->id);
            if(isset($children)) {
                $secondHalfList[$i]['children'] = $children;
            }
            
        }
        $recentEvent['secondHalf'] = $secondHalfList;

        $afterEvent = new AfterEvent();
        $afterGroupEvent = new AfterGroupEvent();
        $afterEventList = $afterEvent->getAfterEvent($eventId);
        for($i=0; $i<count($afterEventList); $i++) {
            $children= $afterGroupEvent->getAfterGroupEvent($afterEventList[$i]->id);
            if(isset($children)) {
                $afterEventList[$i]['children'] = $children;
            }
            
        }
        $recentEvent['afterGame'] = $afterEventList;
        $latestEvent['schedule'] = $recentEvent;      
       return $latestEvent;
    }

    public function destroy($id)
    {
        $count=0;
        $ids= explode(",",$id);
        foreach($ids as $item) {
            $count+= Events::where('id',$item)->delete();
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

    public function action(Request $request) {
        $postData = $request->all();
        $response['status'] = 200; 

       // echo "<pre>";
       // print_r($postData);
       // echo "</pre>";

        if($postData['section']=="event" && $postData['action']=="edit" ) {

            $updateEvent =  Events :: find($postData['data']['id']);
            if(isset($postData['data']['title'])) 
            $updateEvent->title= $postData['data']['title'];
            if(isset($postData['data']['round'])) 
            $updateEvent->round= $postData['data']['round'];
            if(isset($postData['data']['opponent'])) 
            $updateEvent->opponent= $postData['data']['opponent'];
            if(isset($postData['data']['status'])) 
            $updateEvent->status= $postData['data']['status'];
            if(isset($postData['data']['startTime'])) 
            $updateEvent->startTime= $postData['data']['startTime'];        
            $updateEvent->save();
            $response['message'] = 'Event updated Successfully'; 
            return response($response);
        }


        if($postData['section']=="beforeGame" && $postData['action']=="edit") {
           
            if($postData['category']=="parent") {
                $updateBeforeEvent =  BeforeEvent :: find($postData['data']['id']);
            }

            if($postData['category']=="children") {
                $updateBeforeEvent =  GroupEvent :: find($postData['data']['id']);
            }

           
            if(isset($postData['data']['type'])) 
            $updateBeforeEvent->type= $postData['data']['type'];

            if(isset($postData['data']['mediaType'])) 
            $updateBeforeEvent->mediaType= $postData['data']['mediaType'];

            if(isset($postData['data']['media'])) 
            $updateBeforeEvent->media= $postData['data']['media'];

            if(isset($postData['data']['startTime'])) 
            $updateBeforeEvent->startTime= $postData['data']['startTime'];
           
            if(isset($postData['data']['duration'])) 
            $updateBeforeEvent->duration= $postData['data']['duration'];
            if(isset($postData['data']['name'])) 
            $updateBeforeEvent->name= $postData['data']['name'];

            if(isset($postData['data']['name'])) 
            $updateBeforeEvent->name= $postData['data']['name'];
          
            if(isset($postData['data']['comment'])) 
            $updateBeforeEvent->comment= $postData['data']['comment'];
        
            if(isset($postData['data']['color'])) 
            $updateBeforeEvent->color= $postData['data']['color'];

            if(isset($postData['data']['audio'])) 
            $updateBeforeEvent->audio= $postData['data']['audio'];
    
            $updateBeforeEvent->save();
            $response['message'] = 'BeforeGame updated Successfully'; 
            return response($response);
        }

        if($postData['section']=="beforeGame" && $postData['action']=="delete") {
            if($postData['category']=="parent") {
                BeforeEvent::where('id',$postData['id'])->delete();
                $response['message'] = 'BeforeGame deleted Successfully'; 
                return response($response);
            }

            if($postData['category']=="children") {
                GroupEvent::where('id',$postData['id'])->delete();
                $response['message'] = 'BeforeGame children deleted Successfully'; 
                return response($response);
            }
           
           
        }

        if($postData['section']=="afterGame" && $postData['action']=="edit") {
           
            if($postData['category']=="parent") {
                $updateBeforeEvent =  AfterEvent :: find($postData['data']['id']);
            }

            if($postData['category']=="children") {
                $updateBeforeEvent =  AfterGroupEvent :: find($postData['data']['id']);
            }

           
            if(isset($postData['data']['type'])) 
            $updateBeforeEvent->type= $postData['data']['type'];

            if(isset($postData['data']['mediaType'])) 
            $updateBeforeEvent->mediaType= $postData['data']['mediaType'];

            if(isset($postData['data']['media'])) 
            $updateBeforeEvent->media= $postData['data']['media'];

            if(isset($postData['data']['startTime'])) 
            $updateBeforeEvent->startTime= $postData['data']['startTime'];
           
            if(isset($postData['data']['duration'])) 
            $updateBeforeEvent->duration= $postData['data']['duration'];
            if(isset($postData['data']['name'])) 
            $updateBeforeEvent->name= $postData['data']['name'];

            if(isset($postData['data']['name'])) 
            $updateBeforeEvent->name= $postData['data']['name'];
          
            if(isset($postData['data']['comment'])) 
            $updateBeforeEvent->comment= $postData['data']['comment'];
        
            if(isset($postData['data']['color'])) 
            $updateBeforeEvent->color= $postData['data']['color'];

            if(isset($postData['data']['audio'])) 
            $updateBeforeEvent->audio= $postData['data']['audio'];
    
            $updateBeforeEvent->save();
            $response['message'] = 'BeforeGame updated Successfully'; 
            return response($response);
        }

        if($postData['section']=="afterGame" && $postData['action']=="delete") {
            if($postData['category']=="parent") {
                AfterEvent::where('id',$postData['id'])->delete();
                $response['message'] = 'After Game deleted Successfully'; 
                return response($response);
            }
            if($postData['category']=="children") {
                AfterGroupEvent::where('id',$postData['id'])->delete();
                $response['message'] = "After Game children deleted Successfully"; 
                return response($response);
            }
       
        }

        // Break section

        if($postData['section']=="break" && $postData['action']=="edit") {
           
            if($postData['category']=="parent") {
                $updateBeforeEvent =  BreakEvent :: find($postData['data']['id']);
            }

            if($postData['category']=="children") {
                $updateBeforeEvent =  BreakGroupEvent :: find($postData['data']['id']);
            }

           
            if(isset($postData['data']['type'])) 
            $updateBeforeEvent->type= $postData['data']['type'];

            if(isset($postData['data']['mediaType'])) 
            $updateBeforeEvent->mediaType= $postData['data']['mediaType'];

            if(isset($postData['data']['media'])) 
            $updateBeforeEvent->media= $postData['data']['media'];

            if(isset($postData['data']['startTime'])) 
            $updateBeforeEvent->startTime= $postData['data']['startTime'];
           
            if(isset($postData['data']['duration'])) 
            $updateBeforeEvent->duration= $postData['data']['duration'];
            if(isset($postData['data']['name'])) 
            $updateBeforeEvent->name= $postData['data']['name'];

            if(isset($postData['data']['name'])) 
            $updateBeforeEvent->name= $postData['data']['name'];
          
            if(isset($postData['data']['comment'])) 
            $updateBeforeEvent->comment= $postData['data']['comment'];
        
            if(isset($postData['data']['color'])) 
            $updateBeforeEvent->color= $postData['data']['color'];

            if(isset($postData['data']['audio'])) 
            $updateBeforeEvent->audio= $postData['data']['audio'];
    
            $updateBeforeEvent->save();
            $response['message'] = 'Break updated Successfully'; 
            return response($response);
        }

        if($postData['section']=="break" && $postData['action']=="delete") {
            if($postData['category']=="parent") {
                BreakEvent::where('id',$postData['id'])->delete();
                $response['message'] = 'Break Game deleted Successfully'; 
                return response($response);
            }
            if($postData['category']=="children") {
                BreakGroupEvent::where('id',$postData['id'])->delete();
                $response['message'] = "Break Game children deleted Successfully"; 
                return response($response);
            }
       
        }

    // First Half

     if($postData['section']=="firstHalf" && $postData['action']=="edit") {
           
        if($postData['category']=="parent") {
            $updateBeforeEvent =  FirstHalfEvent :: find($postData['data']['id']);
        }

        if($postData['category']=="children") {
            $updateBeforeEvent =  FirstHalfGroupEvent :: find($postData['data']['id']);
        }

       
        if(isset($postData['data']['type'])) 
        $updateBeforeEvent->type= $postData['data']['type'];

        if(isset($postData['data']['mediaType'])) 
        $updateBeforeEvent->mediaType= $postData['data']['mediaType'];

        if(isset($postData['data']['media'])) 
        $updateBeforeEvent->media= $postData['data']['media'];

        if(isset($postData['data']['startTime'])) 
        $updateBeforeEvent->startTime= $postData['data']['startTime'];
       
        if(isset($postData['data']['duration'])) 
        $updateBeforeEvent->duration= $postData['data']['duration'];
        if(isset($postData['data']['name'])) 
        $updateBeforeEvent->name= $postData['data']['name'];

        if(isset($postData['data']['name'])) 
        $updateBeforeEvent->name= $postData['data']['name'];
      
        if(isset($postData['data']['comment'])) 
        $updateBeforeEvent->comment= $postData['data']['comment'];
    
        if(isset($postData['data']['color'])) 
        $updateBeforeEvent->color= $postData['data']['color'];

        if(isset($postData['data']['audio'])) 
        $updateBeforeEvent->audio= $postData['data']['audio'];

        $updateBeforeEvent->save();
        $response['message'] = 'First Half updated Successfully'; 
        return response($response);
    }

    if($postData['section']=="firstHalf" && $postData['action']=="delete") {
        if($postData['category']=="parent") {
            FirstHalfEvent::where('id',$postData['id'])->delete();
            $response['message'] = 'First Half deleted Successfully'; 
            return response($response);
        }
        if($postData['category']=="children") {
            FirstHalfGroupEvent::where('id',$postData['id'])->delete();
            $response['message'] = "First Half children deleted Successfully"; 
            return response($response);
        }
   
    }

    /// second half
    if($postData['section']=="secondHalf" && $postData['action']=="edit") {
           
        if($postData['category']=="parent") {
            $updateBeforeEvent =  SecondHalfEvent :: find($postData['data']['id']);
        }

        if($postData['category']=="children") {
            $updateBeforeEvent =  SecondHalfGroupEvent :: find($postData['data']['id']);
        }

       
        if(isset($postData['data']['type'])) 
        $updateBeforeEvent->type= $postData['data']['type'];

        if(isset($postData['data']['mediaType'])) 
        $updateBeforeEvent->mediaType= $postData['data']['mediaType'];

        if(isset($postData['data']['media'])) 
        $updateBeforeEvent->media= $postData['data']['media'];

        if(isset($postData['data']['startTime'])) 
        $updateBeforeEvent->startTime= $postData['data']['startTime'];
       
        if(isset($postData['data']['duration'])) 
        $updateBeforeEvent->duration= $postData['data']['duration'];
        if(isset($postData['data']['name'])) 
        $updateBeforeEvent->name= $postData['data']['name'];

        if(isset($postData['data']['name'])) 
        $updateBeforeEvent->name= $postData['data']['name'];
      
        if(isset($postData['data']['comment'])) 
        $updateBeforeEvent->comment= $postData['data']['comment'];
    
        if(isset($postData['data']['color'])) 
        $updateBeforeEvent->color= $postData['data']['color'];

        if(isset($postData['data']['audio'])) 
        $updateBeforeEvent->audio= $postData['data']['audio'];

        $updateBeforeEvent->save();
        $response['message'] = 'First Half updated Successfully'; 
        return response($response);
    }

    if($postData['section']=="secondHalf" && $postData['action']=="delete") {
        if($postData['category']=="parent") {
            SecondHalfEvent::where('id',$postData['id'])->delete();
            $response['message'] = 'Second Half deleted Successfully'; 
            return response($response);
        }
        if($postData['category']=="children") {
            SecondHalfGroupEvent::where('id',$postData['id'])->delete();
            $response['message'] = "Second Half deleted Successfully"; 
            return response($response);
        }
   
    }
        
      
    }


}
