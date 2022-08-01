@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-sm-6">
            <div class="card-deck">
                <div class="card text-center">
                    <div class="card-body">
                        <h5 class="card-title">Campaigns</h5>
                        <p class="card-text">
                            Create and manage open source issue email campaigns    
                        </p>
                        <a href="{{ route('campaigns') }}" class="btn btn-primary">View Campaigns</a>
                        <a href="{{ route('campaigns.create') }}" class="btn btn-success">Create Campaign</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-6">
            <div class="card-deck">
                <div class="card text-center">
                    <div class="card-body">
                        <h5 class="card-title">Issues</h5>
                        <p class="card-text">
                            Manage open source issues    
                        </p>
                        <a href="{{ route('issues') }}" class="btn btn-primary">View Issues</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
