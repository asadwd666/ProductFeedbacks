<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index(){
        return view('admin.index.blade.php');
    }
    public function listUsers()
    {
        $users = User::all()->where('role_id',1);
       return $users;
    }
    public function postingPermission(Request $request){
        // Assigning permissions to a user
        $user=User::where('id',$request->user_id)->first();
        if($request->enablePosting){
            $user->givePermissionTo('posting');
        }else{
            $user->revokePermissionTo('posting');
        }
        return response()->json([
            'success'=>true
        ]);

    }
    public function commentsPermission(Request $request){
        $user=User::where('id',$request->user_id)->first();
        if($request->enableCommenting){
            $user->givePermissionTo('commenting');
        }else{
            $user->revokePermissionTo('commenting');
        }
        return response()->json([
            'success'=>true
        ]);
    }
    public function deleteUser(Request $request)
    {
        // Delete user logic
        $user =User::where('id',$request->user_id)->first();
        $user->delete();
        return response()->json([
            'success'=>true
        ]);
    }

    public function suspendUser(User $user)
    {
        // Suspend user logic
        $user->update(['suspended' => true]);
        return redirect()->route('admin.users')->with('success', 'User suspended successfully.');
    }

    public function disableComments(User $user)
    {
        // Disable comments logic
        $user->update(['comments_disabled' => true]);
        return redirect()->route('admin.users')->with('success', 'Comments disabled for the user.');
    }

}
