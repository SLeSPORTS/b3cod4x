@extends('b3::base')

@section('b3content')
    <link rel="stylesheet" href="{{asset('vendor/stormyy/b3cod4x/css/b3app.css')}}"/>
    <vue-toastr ref="toastr"></vue-toastr>
    <div class="container">
        <h1 style="margin-top:0;margin-bottom:30px;">{{$server->name}}</h1>
        <tabs cache-lifetime="10">
            <tab name="Players">
                <h3 style="margin:0">Current players</h3>
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th>Id</th>
                        <th>Playername</th>
                        <th>Guid</th>
                        <th>Ip</th>
                        <th>Screenshots</th>
                        <th>Latest screenshot</th>
                        @if(Auth::check())
                            <th></th>
                        @endif
                    </tr>
                    </thead>
                    <tbody is="b3players" serverid="{{$server->id}}"></tbody>
                </table>
            </tab>
            <tab name="Search">
                <h3 style="margin:0">Search players</h3>
                <b3playersearch serverid="{{$server->id}}"></b3playersearch>
            </tab>
            <tab name="Chat">
                <h3 style="margin:0">Chatlog</h3>
                <b3chat serverid="{{$server->id}}" :canchat="{{ Auth::check() && Auth::user()->can('chat', $server)}}"></b3chat>
            </tab>
            <tab name="Admins">
                <h3 style="margin:0">Admins</h3>
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th>Id</th>
                        <th>Group</th>
                        <th>Playername</th>
                        <th>Guid</th>
                        <th>Bans</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($admins as $admin)
                        <tr>
                            <td>{{$admin->id}}</td>
                            <td>{{$admin->group->name}}</td>
                            <td>{{$admin->name}}</td>
                            <td><a href="{{url('/b3/'.$server->id.'/player/'.$admin->guid)}}">{{$admin->guid}}</a></td>
                            <td>{{$admin->adminpenalties()->whereIn('type', ['Ban', 'TempBan'])->count()}}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </tab>
            <tab name="Active bans">
                Total bans: {{$activebans->total()}}
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th>Type</th>
                        <th>Player</th>
                        <th>Reason</th>
                        <th>Expires</th>
                        <th>Added</th>
                        <th>Admin</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($activebans as $activeban)
                        <tr>
                            <td>{{$activeban->type}}</td>
                            <td><a href="{{url('/b3/'.$server->id.'/player/'.$activeban->player->guid)}}">{{$activeban->player->name}}</a></td>
                            <td>{{$activeban->reason}}</td>
                            <td>{{($activeban->time_expire != -1) ? \Carbon\Carbon::createFromTimestamp($activeban->time_expire)->toDateTimeString() : 'Never'}}</td>
                            <td>{{\Carbon\Carbon::createFromTimestamp($activeban->time_add)->toDateTimeString()}}</td>
                            <td>{!! ($activeban->admin_id != 0) ? '<a href="'.url('/b3/'.$server->id.'/player/'.$activeban->admin->guid).'">'.$activeban->admin->name.'</a>' : '' !!}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                {{ $activebans->fragment('activeban')->links() }}
            </tab>
        </tabs>
    </div>
@endsection
