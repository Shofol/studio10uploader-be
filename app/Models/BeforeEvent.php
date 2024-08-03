<?php

namespace App\Models;

use App\Models\GroupEvent;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class BeforeEvent extends Model
{
    use HasFactory; 

    public function store($request, $eventId) { 
              
        $beforeEvent = new BeforeEvent();
        $beforeEvent->events_id = $eventId;
        if(isset($request['id'])) {
            $beforeEvent->files_id = $request['id'];
        }       
        $beforeEvent->type = $request['type'];
        $beforeEvent->mediaType = $request['mediaType'];
        $beforeEvent->media = $request['media'];
        $beforeEvent->duration = $request['duration'];
        $beforeEvent->name = $request['name'];
        $beforeEvent->comment = $request['comment'];
        if(isset($request['color']))
        $beforeEvent->color = $request['color'];
        $beforeEvent->startTime = $request['startTime']; 
        $beforeEvent->save();
        $groupId = $beforeEvent->id;

        if(isset($request['children'])) {
            foreach($request['children'] as $children) {               
                $groupId = $beforeEvent->id;
                $groupEvent = new GroupEvent();
                $groupEvent->store( $children, $groupId );
            }
            

        }

        return true;
    }

    public function getBeforeEvent($eventId) {
        $query= BeforeEvent::query();
        $query->where('events_id', '=', $eventId);
        $beforeEventList =  $query->get();
        return $beforeEventList;
    }

}
