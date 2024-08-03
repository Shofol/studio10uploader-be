<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AfterEvent extends Model
{
    use HasFactory;
    public function store($request, $eventId) { 
        
        
        $afterEvent = new AfterEvent();
        $afterEvent->events_id = $eventId;
        if(isset($request['id'])) {
            $afterEvent->files_id = $request['id'];
        }      
        $afterEvent->type = $request['type'];
        if(isset($request['mediaType'])) {
            $afterEvent->mediaType = $request['mediaType'];
        } 

        if(isset($request['media'])) {
            $afterEvent->media = $request['media'];
        }     
        
        $afterEvent->startTime = $request['startTime'];
        $afterEvent->duration = $request['duration'];
        $afterEvent->name = $request['name'];
        $afterEvent->comment = $request['comment'];
        if(isset($request['color']))
        $afterEvent->color = $request['color'];     
        $afterEvent->save();
        $afterEventId = $afterEvent->id;

        if(isset($request['children'])) {
            foreach($request['children'] as $children) {
                $afterGroupEvent = new AfterGroupEvent();
                $afterGroupEvent->store( $children, $afterEventId );
            }
            

        }

        return true;
    }

    public function getAfterEvent($eventId) {
        $query= AfterEvent::query();
        $query->where('events_id', '=', $eventId);
        $afterEventList =  $query->get();
        return $afterEventList;
    }

}
