@extends('admin.admin_master')
@section('admin')
@foreach(auth()->user()->notifications as $notification)
  <div class="bg-blue-300 p-3 m-2">
    <b>{{ $notification->data['name']}}</b> test notification !!
    <a href="{{ route('testmarkasread',$notification->id)}}" class="p-2 bg-red-400 text-blue rounded-lg">Mark As Read</a>
  </div> 
@endforeach
@endsection