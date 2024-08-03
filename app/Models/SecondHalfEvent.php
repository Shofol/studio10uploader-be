<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SecondHalfEvent extends Model
{
    use HasFactory;
    public function store($request, $eventId) { 
        
        
        $secondHalfEvent = new SecondHalfEvent();
        $secondHalfEvent->events_id = $eventId;
        if(isset($request['id'])) {        
            $secondHalfEvent->files_id = $request['id'];
        }
        $secondHalfEvent->type = $request['type'];
        if(isset($request['mediaType'])) {
            $secondHalfEvent->mediaType = $request['mediaType'];
        } 

        if(isset($request['media'])) {
            $secondHalfEvent->media = $request['media'];
        }     
        
        $secondHalfEvent->startTime = $request['startTime'];
        $secondHalfEvent->duration = $request['duration'];
        $secondHalfEvent->name = $request['name'];
        $secondHalfEvent->comment = $request['comment'];
        if(isset($request['color']))
        $secondHalfEvent->color = $request['color'];     
        $secondHalfEvent->save();
        $secondHalfEventId = $secondHalfEvent->id;

        if(isset($request['children'])) {
            foreach($request['children'] as $children) {
                $groupEvent = new SecondHalfGroupEvent();
                $groupEvent->store( $children, $secondHalfEventId );
            }
            

        }

        return true;
    }

    public function getSecondHalfEvent($eventId) {
        $query= SecondHalfEvent::query();
        $query->where('events_id', '=', $eventId);
        $secondHalfEventList =  $query->get();
        return $secondHalfEventList;
    }

}
