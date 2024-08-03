<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Events extends Model
{
    use HasFactory;

  
    public function store($request) { 
            
        $event = new Events();
        $event->title = $request['title'];    
        $event->round = $request['round'];
        $event->opponent =  $request['opponent'];
        $event->status =  $request['status'];
        $event->startTime =  $request['startTime'];      
        $event->save();
        return $eventId= $event->id;       
    }

    public function getSingleEvent($eventId) {
        $recentEvent = Events :: find($eventId);
        return $recentEvent;
    }

}
