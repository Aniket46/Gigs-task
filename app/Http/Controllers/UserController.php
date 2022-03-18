<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;

use Illuminate\Validation\Rules\Enum;

use Illuminate\Support\Facades\Validator;

use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::all();

        return response()->json($users);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'firstname' => 'required|max:255|alpha',
            'lastname' => 'required|max:255|alpha',
            'mobile' => 'unique:users,mobile|required|integer|max:9999999999',
            'email' => 'unique:users,email|required|max:255',
            'age' => 'required|integer|max:100',
            'gender' => 'required|in:m,f,o',
            'city' => 'required|max:255',
            'password' => ['required', 'min:6', 'regex:/^.*(?=.{3,})(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[!$#%@]).*$/']
        ]);

        $newUser = new User([
            'firstname' => $request->get('firstname'),
            'lastname' => $request->get('lastname'),
            'mobile' => $request->get('mobile'),
            'email' => $request->get('email'),
            'age' => $request->get('age'),
            'gender' => $request->get('gender'),
            'city' => $request->get('city'),
            'password' => $request->get('password'),
        ]);

        $newUser->save();

        return response()->json($newUser);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::findOrFail($id);
        return response()->json($user);
    }

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
        $user = User::findOrFail($id);
        
        $request->validate([
            'firstname' => 'required|max:255|alpha',
            'lastname' => 'required|max:255|alpha',
            'city' => 'required|max:255'
        ]);

        $user->firstname = $request->get('firstname');
        
        $user->lastname = $request->get('lastname');
        
        $user->city = $request->get('city');
        
        $user->save();

        return response()->json($user);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);

        $user->delete();

        return response()->json(User::all());
    }

    public function login(Request $request)
    {   
        $users = User::select("*")
        ->where([
            ["email", "=", $request->get('email')],
            ["password", "=", $request->get('password')]
        ])
        ->get();

        return response()->json($users);
    }

    public function rent_book(Request $request)
    {   
        $request->validate([
            'book_id' => 'required|integer',
            'user_id' => 'required|integer'
        ]);

        $book = DB::table('book_logs')->select('id')->where([
            ['book_id' ,'=', $request->get('book_id')],
            ['user_id' ,'=', $request->get('user_id')],
            ['returned_at' ,'=', NULL]
        ])->get()->count();

        if($book > 0)
        {
            return response()->json(['msg' => 'You have already rented this book!'],403);
        }

        DB::table('book_logs')->insert([
            'book_id' => $request->get('book_id'),
            'user_id' => $request->get('user_id'),
            'rented_at' => date('Y-m-d H:i:s')
        ]);

        return response()->json(['msg' => 'Book rented successfully!']);
    }

    public function return_book(Request $request)
    {   
        $request->validate([
            'book_id' => 'required|integer',
            'user_id' => 'required|integer'
        ]);

        $affected = DB::table('book_logs')->where([
            'book_id' => $request->get('book_id'),
            'user_id' => $request->get('user_id')
        ])->update(['returned_at' => date('Y-m-d H:i:s')]);
        
        if($affected)
        {
            return response()->json(['msg' => 'Book returned successfully!']);
        }
        else
        {
            return response()->json(['msg' => 'Something went wrong!'],400);
        }
    }

    public function user_rented_books($id)
    {   
        $user = User::findOrFail($id);

        $user_books = DB::table('book_logs')
        ->join('books','books.id','=','book_logs.book_id')
        ->select('book_logs.book_id','books.book_name','books.author','books.cover_image','book_logs.rented_at','book_logs.returned_at')
        ->where([
            ["user_id", "=", $id]
        ])
        ->get();

        return response()->json($user_books);
    }

}
