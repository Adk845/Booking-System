<?php

namespace App\Http\Controllers;

use App\Mail\kirimEmail;
use App\Models\event;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
class fullCalenderController extends Controller
{
    //
    public function first() {
        return redirect()->route('index');
    }

    public function dashboard(){
        $current_year = Carbon::now()->year;
        $current_month = Carbon::now()->month;

    
        $events = event::select(
            DB::raw('DATE(start) as date_start'),
            DB::raw('TIME(start) as time_start'),
            DB::raw('DATE(end) as date_end'),
            DB::raw('TIME(end) as time_end'),
            'title',
            'name'
        )->whereYear('start', $current_year)->whereMonth('start', $current_month)->get();
        return view('dashboard', compact('events'));

    }

    public function dashboard_api(){
        $current_year = Carbon::now()->year;
        $current_month = Carbon::now()->month;
        $startOfWeek = Carbon::now()->startOfWeek(); // Default hari Senin
        $endOfWeek = Carbon::now()->endOfWeek();
        $datas = [];

        $events = event::select(
            DB::raw('DATE(start) as date_start'),
            DB::raw('TIME(start) as time_start'),
            DB::raw('DATE(end) as date_end'),
            DB::raw('TIME(end) as time_end'),
            'title',
            'name'
        )->whereBetween('start', [$startOfWeek, $endOfWeek])->orderBy('start', 'asc')->get();

        $datas = $events->map(function ($event) {
            return [
                'day'      => Carbon::parse($event->date_start)->format('l'), // Nama hari
                'title'         => $event->title, // Opsional: menambahkan data lain
                'name'          => $event->name,  // Opsional
                'date_start'    => $event->date_start,
                'date_end'      => $event->date_end,
                'time_start'    => $event->time_start,
                'time_end'      => $event->time_end,

            ];
        });
        // foreach($events as $event)
        // {
        //     $day_name = Carbon::parse($event->start)->format('l');
        //     array_push($day, $day_name);
        // }
        return response()->json($datas);
    
        // $events = event::select(
        //     DB::raw('DATE(start) as date_start'),
        //     DB::raw('TIME(start) as time_start'),
        //     DB::raw('DATE(end) as date_end'),
        //     DB::raw('TIME(end) as time_end'),
        //     'title',
        //     'name'
        // )->whereBetween('start', $current_year)->whereMonth('start', $current_month)->get();
        // return response()->json($events);
    }
    public function index(Request $request)
    {
        if ($request->ajax()) {
           
            $data = Event::where('start', '>=', $request->start)
                        ->where('end', '<=', $request->end)
                        ->get(['id','name', 'title', 'start', 'end']);
    
            return response()->json($data);
        }
        return view('dashboard');
    }
    
    public function ajax(Request $request)
    {
        switch ($request->type) {
            case 'add':
                $event = Event::create([
                    'name' => $request->name,
                    'desc' => $request->desc,
                    'subject' => $request->subject,
                    'title' => $request->title,
                    'start' => $request->start,
                    'end' => $request->end       
                ]);
            
                //  BAGIAN KIRIM EMAIL 
            

                $data_email = [
                    'subject' => "Booking Meeting room notifications",
                    'sender_email' => 'Booking.Room@resindori.com',
                    'sender_name' => 'Booking Room System',
                    'title' => $request->title,
                    'name' => $request->name,
                    'desc' => $request->desc,
                    'user_email' => $request->user_email,
                    'start' => $request->start,
                    'end' => $request->end,
                ];
            
                // Membuat file 
                $ics_content = "BEGIN:VCALENDAR\r\n";
                $ics_content .= "VERSION:2.0\r\n";
                $ics_content .= "PRODID:-//Resindo//NONSGML v1.0//EN\r\n";
                $ics_content .= "BEGIN:VEVENT\r\n";
                $ics_content .= "SUMMARY:" . $request->subject . "\r\n";
                $ics_content .= "DESCRIPTION:" . $request->desc . "\r\n";
                $ics_content .= "DTSTART:" . \Carbon\Carbon::parse($request->start)->format('Ymd\THis') . "\r\n";
                $ics_content .= "DTEND:" . \Carbon\Carbon::parse($request->end)->format('Ymd\THis') . "\r\n";
                $ics_content .= "LOCATION:" . $request->title . "\r\n";
                $ics_content .= "STATUS:CONFIRMED\r\n";
                $ics_content .= "BEGIN:VALARM\r\n";
                $ics_content .= "TRIGGER:-PT10M\r\n";  // Mengatur pengingat waktu 
                $ics_content .= "DESCRIPTION:Reminder\r\n";
                $ics_content .= "ACTION:DISPLAY\r\n";
                $ics_content .= "END:VALARM\r\n";
                $ics_content .= "END:VEVENT\r\n";
                $ics_content .= "END:VCALENDAR\r\n";
            
                // Simpan file ICS sementara di server
                $ics_filename = storage_path('app/meeting_event.ics');
                file_put_contents($ics_filename, $ics_content);
            
                // Kirim email dengan lampiran ICS
                Mail::to($request->user_email)->send(new kirimEmail($data_email, $ics_filename));
            
                // Menghapus file ICS setelah dikirim
                unlink($ics_filename);
            
                return response()->json($event);
                break;
            
    
            case 'update':
                $event = Event::find($request->id);
                if ($event) {
                    $event->update([
                        'name' => $request->name,
                        'desc' => $request->desc,
                        'subject' => $request->subject,
                        'title' => $request->title,
                        'start' => $request->start,  
                        'end' => $request->end       
                    ]);
                    return response()->json($event);
                }
                break;
    
            case 'delete':
                $event = Event::find($request->id);
                if ($event) {
                    $event->delete();
                    return response()->json(['success' => true]);
                }
                break;
    
            default:
                return response()->json(['error' => 'Invalid request type'], 400);
        }
    }
    
}
