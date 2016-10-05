<?php

namespace App\Http\Controllers;

use App\History;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;


class UserController extends Controller
{

    public function index(){
        $users = DB::table('users')
            ->join('histories', 'users.id', '=', 'histories.user_id')
            ->select('users.email', 'histories.created_at')
            ->where('histories.ip',$this->getIp())
            ->orderBy('histories.created_at', 'desc')
            ->get();
        echo json_encode($users);
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $browser = $this->getBrowser();
        $ip =$this -> getIp();

        $rules = array(
            'password' => 'required|min:8',
            'email' => 'required|email',
        );

        $validator = Validator::make(Input::all(), $rules);

        Input::flash('only', array('email'));

        if ($validator->fails()) {
            return redirect('/')->withErrors($validator);
        } else {
            $hash=Hash::make($request->password);
            $user = User::where('email', $request->email)->first();
            if (!empty($user)) {
                if(Hash::check($request->password, $user->password)) {
                } else {
                    Session::flash('message','Sorry. You put the wrong password (');
                    return view('login');
                }
            } else {
                $user=new User();
                DB::transaction(function() use ($user,$request,$browser,$ip) {
                    $user->email=$request->email;
                    $user->password=Hash::make($request->password);
                    $user->save();
                    return $user;
                });
            }
            Session::flash('message','Hello! Welcome to our page )');
            History::create(array('ip' => $ip, 'browser' => $browser, 'user_id' => $user->id));
            return redirect('/users/'.$user->id);
        }
    }

    public function getBrowser()
    {
        $user_agent = $_SERVER["HTTP_USER_AGENT"];
        if (strpos($user_agent, "Firefox") !== false) $browser = "Firefox";
        elseif (strpos($user_agent, "Opera") !== false) $browser = "Opera";
        elseif (strpos($user_agent, "Chrome") !== false) $browser = "Chrome";
        elseif (strpos($user_agent, "MSIE") !== false) $browser = "Internet Explorer";
        elseif (strpos($user_agent, "Safari") !== false) $browser = "Safari";
        else $browser = "Неизвестный";
        return $browser;
    }

    public function getIp(){
        $ip_address = $_SERVER["REMOTE_ADDR"];
        return $ip_address;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $histories = User::find($id)->histories()->orderBy('created_at')->get();
        return view('histories',array('histories' => $histories, 'user' => User::find($id)));
    }
}
