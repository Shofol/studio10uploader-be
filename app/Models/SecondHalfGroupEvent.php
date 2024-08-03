<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SecondHalfGroupEvent extends Model
{
    use HasFactory;

    public function store($request,$secondHalfId) { 
               
        $secondHalfGroupEvent = new SecondHalfGroupEvent();
        $secondHalfGroupEvent->second_half_events_id= $secondHalfId;
        if(isset($request['id'])) {
            $secondHalfGroupEvent->files_id = $request['id'];
        }       
        $secondHalfGroupEvent->type = $request['type'];
        $secondHalfGroupEvent->mediaType = $request['mediaType'];
        $secondHalfGroupEvent->media = $request['media'];
        $secondHalfGroupEvent->duration = $request['duration'];
        $secondHalfGroupEvent->name = $request['name'];
        $secondHalfGroupEvent->comment = $request['comment'];
        $secondHalfGroupEvent->color = $request['color'];
        $secondHalfGroupEvent->startTime = $request['startTime']; 
        $secondHalfGroupEvent->audio = $request['audio'];
        $secondHalfGroupEvent->save();
        return true;
    }

    public function getSecondHalfGroupEvent($secondHalfEventId) {
        $query= SecondHalfGroupEvent::query();
        $query->where('second_half_events_id', '=', $secondHalfEventId);
        $secondHalfGroupList =  $query->get();
        return $secondHalfGroupList;
    }
}
