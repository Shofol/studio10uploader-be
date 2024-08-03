<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FirstHalfGroupEvent extends Model
{
    use HasFactory;

    public function store($request,$firstHalfId) { 
               
        $firstHalfGroupEvent = new FirstHalfGroupEvent();
        $firstHalfGroupEvent->first_half_events_id= $firstHalfId;
        if(isset($request['id'])) {
            $firstHalfGroupEvent->files_id = $request['id'];
        }
        $firstHalfGroupEvent->type = $request['type'];
        $firstHalfGroupEvent->mediaType = $request['mediaType'];
        $firstHalfGroupEvent->media = $request['media'];
        $firstHalfGroupEvent->duration = $request['duration'];
        $firstHalfGroupEvent->name = $request['name'];
        $firstHalfGroupEvent->comment = $request['comment'];
        $firstHalfGroupEvent->color = $request['color'];
        $firstHalfGroupEvent->startTime = $request['startTime']; 
        $firstHalfGroupEvent->audio = $request['audio'];
        $firstHalfGroupEvent->save();
        return true;
    }

    public function getFirstHalfGroupEvent($firstHalfId) {
        $query= FirstHalfGroupEvent::query();
        $query->where('first_half_events_id', '=', $firstHalfId);
        $firstHalfGroupList =  $query->get();
        return $firstHalfGroupList;
    }
}
