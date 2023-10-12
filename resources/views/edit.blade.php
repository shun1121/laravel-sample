@extends('layouts.app')

@section('content')
<div class="row justify-content-center ml-0 mr-0 h-100">
    <div class="card w-100">
        <div class="card-header">メモ編集</div>
        <div class="card-body">
            <!-- route()関数 -->
            <!-- https://camo.qiitausercontent.com/fc18787c61c484afd259e81c92d841ba5bc15871/68747470733a2f2f71696974612d696d6167652d73746f72652e73332e61702d6e6f727468656173742d312e616d617a6f6e6177732e636f6d2f302f3639383433392f35336635386662392d313836392d333034322d643133662d6532666537643333616131322e6a706567 -->
            <form method='POST' action="{{ route('update', ['id' => $memo['id']]) }}">
                <!-- ↓ユーザ乗っ取り対策 -->
                @csrf
                <input type='hidden' name='user_id' value="{{ $user['id'] }}">
                <div class="form-group">
                    <textarea name='content' class="form-control" rows="10">
                        {{ $memo['content'] }}
                    </textarea>
                </div>
                <div class="form-group">
                    <select class='form-control' name='tag_id'>
                        @foreach($tags as $tag)
                            <option value="{{ $tag['id'] }}" {{ $tag['id'] == $memo['tag_id'] ? "selected" : "" }}>{{$tag['name']}}</option>
                        @endforeach
                    </select>
                </div>
                <button type='submit' class="btn btn-primary btn-lg">更新</button>
            </form>
        </div>
    </div>
</div>
@endsection
