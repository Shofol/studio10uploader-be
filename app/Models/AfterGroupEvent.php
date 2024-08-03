<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AfterGroupEvent extends Model
{
    use HasFactory;

    public function store($request,$afterEventId) { 
               
        $afterGroupEvent = new AfterGroupEvent();
        $afterGroupEvent->after_events_id   = $afterEventId;

        if(isset($request['id'])) {
            $afterGroupEvent->files_id = $request['id'];
        }       
        $afterGroupEvent->type = $request['type'];
        $afterGroupEvent->mediaType = $request['mediaType'];
        $afterGroupEvent->media = $request['media'];
        $afterGroupEvent->duration = $request['duration'];
        $afterGroupEvent->name = $request['name'];
        $afterGroupEvent->comment = $request['comment'];
        $afterGroupEvent->color = $request['color'];
        $afterGroupEvent->startTime = $request['startTime']; 
        $afterGroupEvent->audio = $request['audio'];
        $afterGroupEvent->save();
        return true;
    }

    public function getAfterGroupEvent($afterEventId) {
        $query= AfterGroupEvent::query();
        $query->where('after_events_id', '=', $afterEventId);
        $afterGroupEventList =  $query->get();
        return $afterGroupEventList;
    }
}
