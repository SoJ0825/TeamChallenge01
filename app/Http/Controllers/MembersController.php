<?php

namespace App\Http\Controllers;

use App\User;
use Log;
use Dirape\Token\Token;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Member;

class MembersController extends Controller
{

    public $token_invalid_time = 600;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        $member = Member::all()->find($id);

        if (time() - $member->token_lifetime < $this->token_invalid_time)
        {
            return $member;
        } else {
            return null;
        }
//        return 'Hi <';
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        Log::Info($request);
        if(empty($request->account)){
            return 'account can\'t be empty';
        }

        if(empty($request->password)){
            return 'password can\'t be empty';
        }

        $members = Member::all();

        foreach ($members as $member) {
            if($request->account == $member->account) {
                return 'account is exist!';
            }
        }

        Member::create([
            'account'=>$request->account,
            'password'=>Hash::make($request->password),
            'username'=>$request->username,
            'email'=>$request->email,
            'phone'=>$request->phone,
            'api_token' => (new Token())->Unique('members', 'api_token', 32),
            'token_lifetime' => time()

        ]);
        session()->put('account', $request->account);

        Log::Info($request);
        return 'create success!';
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

        $member = Member::all()->find($id);

            if (time() - $member->token_lifetime < $this->token_invalid_time)
            {
                return $member;
            } else {
                return null;
            }


    }

//    public function showFirstData($id)
//    {
//        $member = Member::all()->find($id);
//        return $member;
//    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function login(Request $request)
    {
        if( $member = Member::all()->firstwhere('account' , $request->account))
        {
            if (Hash::check($request->password, $member->password))
            {
//                session()->put('account', $member->account);
                $member->token_lifetime = time();
                $member->save();
                $this->user = $member;
                return $member;
            }
        }

        return 'something wrong!';
//        return $request;
    }

    public function logout($id)
    {
        $member = Member::all()->find($id);

        if (time() - $member->token_lifetime < $this->token_invalid_time)
        {
            $member->token_lifetime = 0;
            $member->save();
            return 'logout';
        } else {
            return 'please login!';
        }
    }
}


