<?php

namespace Modules\Event\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Event\Entities\Event;
use Modules\Event\Entities\Guest;
use Illuminate\Support\Facades\Session;
use Modules\Event\Emails\EventMail;
use Illuminate\Support\Facades\Log;

class EventController extends Controller
{
    public function index()
    {
        $event = Event::all();
        return view('event::index', [
            'title' => 'Event',
            'events' => $event,
        ]);
    }

    public function getGuests($id)
    {   
        $event = Event::find($id);
        $guests = Event::find($id)->guests()->get();
        return view('event::manage', [
            'title' => 'Event',
            'event' => $event,
            'guests' => $guests
        ]);
    }

    
    public function import(Request $request)
    {
        $eventId = $request->get('eventId');
        if (count($request->all()) == 2) {
            Session::flash('error','Select file please!');
            return back();
        } else {
            $guestDatas = [];
            $data = array_map('str_getcsv', file($request->file('file')));
            $header = ['event_id','user_id'];
            $list = Guest::all()->toArray();
            $logs = [];
            unset($data[0]);
            $i = 1;

            foreach ($data as $value) {
                $guestValue[0] = $eventId;
                $guestValue[1] = $value[0];

                $guestData = array_combine($header, $guestValue);
                $check = $this->checkData($guestData, $list, $i);

                if (count($check['log']) < 1) {
                    array_push($guestDatas, $guestData);
                } else {
                    array_push($logs,$check);
                }
            }
            if (count($logs) > 0) {
                Log::channel('daily')->info('Row '.$check['row'].' --- Guest already taken!');
                Session::flash('error','Row '.$check['row'].' --- Guest already taken!');
                return back();
            } else {
                foreach ($guestDatas as $guestData){
                    Guest::create($guestData);
                }
                Session::flash('success','Guests Added!');
                return back();
            }
        }
    }

    public function sendMailInvite(Request $request){
        $eventId = $request->get('eventId');
        $event = Event::find($eventId);
        $details = [
            'title' => 'Invite '.$event->name,
            'event' => $event
        ];
    
        \Mail::to('nquocvii@gmail.com')->send(new EventMail($details));
    
        Session::flash('success','Invited');
        return back();
    }

    function checkData($data, $list, $row)
    {
        $logs = [];
        $result = [];

        foreach ($list as $l) {
            if($l['event_id'] == $data['event_id'] && $l['user_id'] == $data['user_id']) {
                $logs['guest'] = 'guest already taken';
                break;
            }
        }

        $result['row'] = $row;
        $result['log'] = $logs;

        return $result;
    }
}