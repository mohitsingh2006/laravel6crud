<?php

namespace App\Http\Controllers;

use App\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use DB;

class ArticleController extends Controller
{
    function show()
    {
        //$articles = DB::table('articles')->orderBy('id','DESC')->get();
        $articles = Article::orderBy('id','DESC')->get();
        return view('list')->with(compact('articles'));
    }

    function addArticle() {
        return view('add');
    }

    function saveArticle(Request $request){
        $validator = Validator::make($request->all(),[
            'title' => 'required|max:255',
            'description' => 'required',
            'author' => 'required|max:100'
        ]);

        if($validator->passes()){
            // Insert record in DB

            $article = new Article;
            $article->title = $request->title;
            $article->description = $request->description;
            $article->author = $request->author;
            $article->save();

            $request->session()->flash('msg','Article saved successfully');
            return redirect('articles');

        } else {
            return redirect('articles/add')->withErrors($validator)->withInput();
        }
    }

    function editArticle($id, Request $request) {
        // Fetch a record from database
        $article = Article::where('id', $id)->first();
        if(!$article) {
            $request->session()->flash('errorMsg','Record not found.');
            return redirect('articles');
        }


        return view('edit')->with(compact('article'));
    }

    function updateArticle($id, Request $request){
        $validator = Validator::make($request->all(),[
            'title' => 'required|max:255',
            'description' => 'required',
            'author' => 'required|max:100'
        ]);

        if($validator->passes()){
            // Insert record in DB

            $article = Article::find($id);
            $article->title = $request->title;
            $article->description = $request->description;
            $article->author = $request->author;
            $article->save();

            $request->session()->flash('msg','Article updated successfully');
            return redirect('articles');

        } else {
            return redirect('articles/edit/'.$id)->withErrors($validator)->withInput();
        }
    }

    function  deleteArticle($id, Request $request) {
        $article = Article::where('id', $id)->first();
        if(!$article) {
            $request->session()->flash('errorMsg','Record not found.');
            return redirect('articles');
        }

        Article::where('id',$id)->delete();
        $request->session()->flash('msg','Record has been deleted successfully.');
        return redirect('articles');
    }
}
