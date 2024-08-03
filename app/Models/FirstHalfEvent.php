<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\FirstHalfGroupEvent;

class FirstHalfEvent extends Model
{
    use HasFactory;

    public function store($request, $eventId) { 
        
        
        $firstHalfEvent = new FirstHalfEvent();
        $firstHalfEvent->events_id= $eventId;
        if(isset($request['id'])) {
            $firstHalfEvent->files_id= $request['id'];
        }     
        $firstHalfEvent->type = $request['type'];
        if(isset($request['mediaType'])) {
            $firstHalfEvent->mediaType = $request['mediaType'];
        } 

        if(isset($request['media'])) {
            $firstHalfEvent->media = $request['media'];
        }     
        
        $firstHalfEvent->startTime = $request['startTime'];
        $firstHalfEvent->duration = $request['duration'];
        $firstHalfEvent->name = $request['name'];
        $firstHalfEvent->comment = $request['comment'];
        if(isset($request['color']))
        $firstHalfEvent->color = $request['color'];     
        $firstHalfEvent->save();
        $groupId = $firstHalfEvent->id;

        if(isset($request['children'])) {
            foreach($request['children'] as $children) {
                $groupEvent = new FirstHalfGroupEvent();
                $groupEvent->store( $children, $groupId );
            }
            

        }

        return true;
    }

    public function getFirstHalfEvent($eventId) {
        $query= FirstHalfEvent::query();
        $query->where('events_id', '=', $eventId);
        $firstHalfList =  $query->get();
        return $firstHalfList;
    }
}
