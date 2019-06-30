<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Common\Core\Controller;
use App\Link;

class LinkController extends Controller
{

    public function index($id)
    {
        $links = Link::where('title_id', $id)->get();
        return $links;
    }
    public function episode($id,$evad,$epizod)
    {
        $links = Link::where('title_id', $id)->where('season', $evad) ->where('episode', $epizod) ->get();
        return $links;
    }

    public function show($id)
    {
        return Link::FindOrFail($id);
    }


    public function update(Request $request,$id)
    {
        $links = Link::findOrFail($id);
        $links->url = $request->url;
        $links->approved=$request->approved;
        $links->season=$request->season;
        $links->episode=$request->episode;
        $links->quality=$request->quality;
        $links->user_name=$request->user_name;
        $links->label=$request->label;
        $links->save();

    }
    
    public function store(Request $request){
        $links = new Link;
        $links->url = $request->url;
        $links->type="external";
        $links->approved=$request->approved;
        $links->season=$request->season;
        $links->episode=$request->episode;
        $links->quality=$request->quality;
        $links->user_name=$request->user_name;
        $links->label=$request->label;
        $links->title_id=$request->title_id;
        $links->save();
    }

    public function destroy($id){
        $links = Link::findOrFail($id);
        $links->delete();
    }

    public function approved($id){
        $links = Link::findOrFail($id);
        $links->approved=1;
        $links->save();
    }

    public function listnotapproved(){
        $links = Link::where("approved",0)->get();
        return $links;

    }
}
