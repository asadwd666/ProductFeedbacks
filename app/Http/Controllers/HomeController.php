<?php

namespace App\Http\Controllers;

use Purifier;
use App\Models\Comment;
use App\Models\Feedback;
use App\Models\User;
use App\Models\Voting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Validator;

class HomeController extends Controller
{
    public function index()
    {
    }
    public function dashboard()
    {
        if(auth()->user() && auth()->user()->role_id == 1){
            $users=User::where('role_id',2)->get();
            return view('admin.index',compact('users'));

         }
        $feedbacks = Feedback::with('comments.user', 'user', 'vote')
            ->get()->map(function ($feedback) {
                $feedback->encrypted_id = Crypt::encrypt($feedback->id);
                if ($feedback->comments->isNotEmpty()) {
                    $feedback->comments->each(function ($comment) {
                        $comment->user_encrypted_id = md5($comment->user->id);
                    });
                }

                return $feedback;
            });
        $feedbacks = new \Illuminate\Pagination\LengthAwarePaginator(
            $feedbacks->forPage(\Illuminate\Pagination\Paginator::resolveCurrentPage(), 10),
            $feedbacks->count(),
            10,
            \Illuminate\Pagination\Paginator::resolveCurrentPage()
        );
        return view('welcome', compact('feedbacks'));
    }
    public function saveFeedbacks(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => ['required', 'max:255', 'string'],
            'description' => ['required', 'max:1024', 'string'],
            'category' => ['required', 'max:10', 'string'],
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => 'Validation failed', 'errors' => $validator->errors()], 422);
        }
        DB::beginTransaction();
        try {
            $feedback = new Feedback();
            $feedback->title = $request->title;
            $feedback->description = $request->description;
            $feedback->category = $request->category;
            $feedback->user_id = Auth::user()->id;
            $feedback->save();
            DB::commit();

            return response()->json(['success' => true, 'message' => 'Feedback saved successfully']);
        } catch (\Exception $e) {
            DB::rollback();
            // Handle any database or other exceptions
            dd($e->getMessage());
            return response()->json(['error' => 'Error occurred while saving feedback'], 500);
        }
    }
    
    public function mentionUser(Request $request)
    {
        $mentions = $request->get('mentions');
        if (is_array($mentions)) {
            // Convert the array to a string, using a separator (e.g., comma)
            $mentionsString = implode(',', $mentions);

            // Fetch user suggestions based on entered characters
            $users = User::where('name', 'like', $mentionsString . '%')->get();
            return response()->json(['users' => $users]);
        }
    }
    public function saveComments(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'comments_input' => ['required'],
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => 'Validation failed', 'errors' => $validator->errors()], 422);
        }
        $purifiedContent = Purifier::clean($request->comments_input);

        DB::beginTransaction();
        try {
            $decryptedPostId = Crypt::decrypt($request->input('post_id'));
            $comments = new Comment();
            $comments->feedback_id = $decryptedPostId;
            $comments->comment_text = $purifiedContent;
            $comments->user_id = Auth::user()->id;
            $comments->save();
            $commentsForPost = Comment::where('feedback_id', $decryptedPostId)->with('user', 'feedback.user')->get();

            $commentsForPost->each(function ($comment) {
                $comment->user_encrypted_id = md5($comment->user->id);
            });

            DB::commit();

            return response()->json(['success' => true, 'message' => 'Feedback saved successfully', 'comments' => $commentsForPost]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' => 'Error occurred while saving feedback'], 500);
        }
    }
    public function addVote(Request $request)
    {
        $vote = $request->voted === 'true' ? 1 : 0;
        $voting = Voting::where('user_id', Auth::user()->id)
            ->where('feedback_id', $request->feedbackId)
            ->first();

        if ($voting) {
            $voting->update(['vote_count' => $vote]);
            $voting->save();
        } else {
            // Create a new record if no record exists
            Voting::create([
                'user_id' => Auth::user()->id,
                'vote_count' => $vote,
                'feedback_id' => $request->feedbackId,
            ]);
        }
        return response()->json([
            'success' => true
        ]);
    }
}
