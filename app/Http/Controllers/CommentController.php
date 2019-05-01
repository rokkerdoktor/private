<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Common\Core\Controller;
use App\Comment;
class CommentController extends Controller
{
    public function index($id)
    {
        $comments = Comment::where('title_id', $id)->get();
        return $comments;
    }

    public function show($id)
    {
        return Comment::FindOrFail($id);
    }

    public function update(Request $request,$id)
    {
        $comment = Comment::findOrFail($id);
        $comment->comment = $request->comment;
        $comment->save();

    }
    
    public function store(Request $request){
        $comment = new Comment;
        $comment->title_id = $request->title_id;
        $comment->user_id = $request->user_id;
        $comment->comment = $request->comment;
        $comment->season = $request->season;
        $comment->episode = $request->episode;
        $comment->save();
    }

    public function destroy($id){
        $comment = Comment::findOrFail($id);
        $comment->delete();
    }
}
