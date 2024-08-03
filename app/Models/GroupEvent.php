<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class GroupEvent extends Model
{
    use HasFactory;

    public function store($request,$groupId) { 
               
        $groupEvent = new GroupEvent();
        $groupEvent->before_events_id  = $groupId;
        if(isset($request['id'])) {
            $groupEvent->files_id = $request['id'];
        }
        $groupEvent->type = $request['type'];
        $groupEvent->mediaType = $request['mediaType'];
        $groupEvent->media = $request['media'];
        $groupEvent->duration = $request['duration'];
        $groupEvent->name = $request['name'];
        $groupEvent->comment = $request['comment'];
        $groupEvent->color = $request['color'];
        $groupEvent->startTime = $request['startTime']; 
        $groupEvent->audio = $request['audio'];
        $groupEvent->save();
        return true;
    }

    public function getGroupEvent($groupId) {
        $query= GroupEvent::query();
        $query->where('before_events_id', '=', $groupId);
        $groupEventList =  $query->get();
        return $groupEventList;
    }

}