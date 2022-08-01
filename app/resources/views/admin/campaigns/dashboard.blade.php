@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">

            <h2>Campaigns</h2>

            @if (session('status'))
                <div class="alert alert-success" role="alert">
                    {{ session('status') }}
                </div>
            @endif

            <ul class="nav nav-pills mb-3">
                <li class="nav-item">
                    <a class="btn btn-success" href="{{ route('campaigns.create') }}">
                        {{ __('pages.campaigns_create') }}
                    </a>
                </li>
            </ul>

            <table class="table table-striped">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Title</th>
                        <th scope="col">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($campaigns as $campaign)
                    <tr>
                        <th scope="row">{{ $campaign->id }}</th>
                        <td>{{ $campaign->title }}</td>
                        <td>
                            <a
                                class="btn btn-primary"
                                href="{{ route('campaigns.edit', ['id' => $campaign->id]) }}"
                            >
                                Edit
                            </a>
                        </td>
                    </tr>
                        
                    @endforeach
                </tbody>
            </table>

            {{ $campaigns->links() }}
        </div>
    </div>
</div>
@endsection
