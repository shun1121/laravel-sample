<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Memo;
use App\Models\Tag;

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
        $user = \Auth::user();
        $memos = Memo::where('user_id', $user['id'])->where('status', 1)->orderBy('updated_at', 'DESC')->get();
        // dd($memos);
        return view('home', compact('user', 'memos'));
    }

    public function create()
    {
        // ログインしているユーザ情報をViewに渡す
        $user = \Auth::user();
        $memos = Memo::where('user_id', $user['id'])->where('status', 1)->orderBy('updated_at', 'DESC')->get();
        return view('create', compact('user', 'memos'));
    }

    public function store(Request $request)
    {
        // フォームに入力されたメモの内容やユーザ情報を受け取ることができる。
        $data = $request->all();
        // dd($data);
        // POSTされたデータをDB（memosテーブル）に挿入
        // MEMOモデルにDBへ保存する命令を出す

        // 同じタグがあるか確認
        $exist_tag = Tag::where('name', $data['tag'])->where('user_id', $data['user_id'])->first();
        // dd($exist_tag['name']);
        if( empty($exist_tag['id']) ){
            //先にタグをインサート
            $tag_id = Tag::insertGetId(['name' => $data['tag'], 'user_id' => $data['user_id']]);
            // dd($tag_id);
        } else {
            $tag_id = $exist_tag['id'];
        }

        //タグのIDが判明する
        // タグIDをmemosテーブルに入れてあげる
        $memo_id = Memo::insertGetId([
            'content' => $data['content'],
            'user_id' => $data['user_id'],
            'tag_id' => $tag_id,
            'status' => 1
        ]);

        // リダイレクト処理
        return redirect()->route('home');
    }

    // ↓web.phpで設定した{id}と対応している
    public function edit($id){
        $user = \Auth::user();
        // 該当するIDのメモをデータベースから取得、first()は条件に該当する行を一つだけ取得するメソッド。
        $memo = Memo::where('status', 1)->where('id', $id)->where('user_id', $user['id'])->first();
        $memos = Memo::where('user_id', $user['id'])->where('status', 1)->orderBy('updated_at', 'DESC')->get();
        $tags = Tag::where('user_id', $user['id'])->get();
        // dd($memo);
        //取得したメモをViewに渡す
        return view('edit', compact('memo', 'user', 'memos', 'tags'));
    }

    // $idで/edit/1の1などのパラメータを取得できる。
    public function update(Request $request, $id)
    {
        $inputs = $request->all();
        // データベースの'id'がurlの$idと同じもの
        Memo::where('id', $id)->update(['content' => $inputs['content'], 'tag_id' => $inputs['tag_id']]);
        return redirect()->route('home');
    }
}
