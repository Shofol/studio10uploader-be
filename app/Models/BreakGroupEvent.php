<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BreakGroupEvent extends Model
{
    use HasFactory;

    public function store($request,$firstHalfId) { 
               
        $breakGroupEvent = new BreakGroupEvent();
        $breakGroupEvent->break_events_id= $firstHalfId;
        if(isset($request['id'])) {
            $breakGroupEvent->files_id = $request['id'];
        }
        $breakGroupEvent->type = $request['type'];
        $breakGroupEvent->mediaType = $request['mediaType'];
        $breakGroupEvent->media = $request['media'];
        $breakGroupEvent->duration = $request['duration'];
        $breakGroupEvent->name = $request['name'];
        $breakGroupEvent->comment = $request['comment'];
        $breakGroupEvent->color = $request['color'];
        $breakGroupEvent->startTime = $request['startTime']; 
        $breakGroupEvent->audio = $request['audio'];
        $breakGroupEvent->save();
        return true;
    }

    public function getBreakGroupEvent($breakId) {
        $query= BreakGroupEvent::query();
        $query->where('break_events_id', '=', $breakId);
        $breakGroupList =  $query->get();
        return $breakGroupList;
    }
}
