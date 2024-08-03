<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\BreakGroupEvent;

class BreakEvent extends Model
{
    use HasFactory;

    public function store($request, $eventId) { 
        
        
        $breakEvent = new BreakEvent();
        $breakEvent->events_id = $eventId;
        if(isset( $request['id'])) {
            $breakEvent->files_id = $request['id'];
        }  
        $breakEvent->type = $request['type'];
        if(isset($request['mediaType'])) {
            $breakEvent->mediaType = $request['mediaType'];
        } 

        if(isset($request['media'])) {
            $breakEvent->media = $request['media'];
        }     
        
        $breakEvent->startTime = $request['startTime'];
        $breakEvent->duration = $request['duration'];
        $breakEvent->name = $request['name'];
        $breakEvent->comment = $request['comment'];
        if(isset($request['color']))
        $breakEvent->color = $request['color'];     
        $breakEvent->save();
        $breakEventId = $breakEvent->id;

        if(isset($request['children'])) {
            foreach($request['children'] as $children) {
                $groupEvent = new BreakGroupEvent();
                $groupEvent->store( $children, $breakEventId );
            }
            

        }

        return true;
    }

    public function getBreakEvent($eventId) {
        $query= BreakEvent::query();
        $query->where('events_id', '=', $eventId);
        $breakEventList =  $query->get();
        return $breakEventList;
    }
}
