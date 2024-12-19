<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User;

class UsersController extends Controller
{
    private $user;

    public function __construct(User $user_model){
        $this->user = $user_model;
    }

    public function index(){
        $all_users = $this->user->withTrashed()->latest()->paginate(4);

        return view('admin.users.index')
                ->with('all_users', $all_users);
    }

    public function deactivate($id){
        $this->user->destroy($id);
        return redirect()->back();
    }

    public function activate($id){
        $this->user->onlyTrashed()->findOrFail($id)->restore();
        return redirect()->back();
    }

    public function search(Request $request){
        $all_users = $this->user->withTrashed()->where('name', 'like', '%' . $request->search_user . '%')->latest()->paginate(4);
        $all_users->appends(['search_user' => $request->search_user]);
        return view('admin.users.index')
                ->with('all_users', $all_users)
                ->with('searched', $request->search_user);
    }
}
