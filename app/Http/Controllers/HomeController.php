<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Kutia\Larafirebase\Facades\Larafirebase;
use App\Notifications\SendPushNotification;
use App\Models\User;
use App\helpers;
use Notification;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }

    public function updateToken(Request $request){
        try{
            $request->user()->update(['fcm_token'=>$request->token]);
            return response()->json([
                'success'=>true
            ]);
        }catch(\Exception $e){
            report($e);
            return response()->json([
                'success'=>false
            ],500);
        }
    }

    public function notification(Request $request){
        $request->validate([
            'title'=>'required',
            'message'=>'required'
        ]);
           
        try{
            $fcmTokens = User::whereNotNull('fcm_token')->pluck('fcm_token')->toArray();
           // Notification::send(null,new SendPushNotification($request->title,$request->message,$fcmTokens));

            /* or */
           auth()->user()->notify(new SendPushNotification($request->title,$request->message,$fcmTokens));
            /* or */

        //   Larafirebase::withTitle($request->title)
        //         ->withBody($request->message)
        //         ->sendMessage($fcmTokens);
    
            return redirect()->back()->with('success','Notification Sent Successfully!!');

        }catch(\Exception $e){
            report($e);
            return redirect()->back()->with('error','Something goes wrong while sending notification.');
        }
    }

    public function notifyUser(){
        $user = User::where('id',auth()->user()->id)->first();
        $notification_id = $user['fcm_token'];
        $title = "Greeting Notification";
        $message = "Have good day!";
        $id = $user['id'];
        $type = "basic";
      
        $res = send_notification_FCM($notification_id, $title, $message, $id,$type);
      
        if($res == 1){
      
           // success code
           return redirect()->back()->with('success','Mobile Notification Sent Successfully!!');
      
        }else{
      
          // fail code
         
        }        
      
     }

     public function sendSmsNotificaition()
     {
         $basic  = new \Nexmo\Client\Credentials\Basic('edc749f6', 'Qzta0tk1cp9VWSAo');
         $client = new \Nexmo\Client($basic);
  
         $message = $client->message()->send([
             'to' => '919426757955',
             'from' => 'John Doe',
             'text' => 'Happy Shopping'
         ]);
  
         dd('SMS message has been delivered.');
     }

     public function otpVerification()
    {
        return view('verifyotp');
    }
}
