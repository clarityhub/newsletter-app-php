@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">

            <h2>Issues</h2>

            @if (session('status'))
                <div class="alert alert-success" role="alert">
                    {{ session('status') }}
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger" role="alert">
                    {{ session('error') }}
                </div>
            @endif

            <table class="table table-striped">
                <thead>
                    <tr>
                        <th scope="col">Votes</th>
                        <th scope="col">#</th>
                        <th scope="col">Title</th>
                        <th scope="col">Campaigns</th>
                        <th scope="col">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($issues as $issue)
                    <tr>
                        <td>
                            @if ($issue->current_user_has_voted)
                                {{
                                    Form::open(array(
                                        'method' => 'POST',
                                        'route' => array('issues.unvote', $issue->id),
                                    ))
                                }}
                                    {{ Form::token() }}
                                    <button class="btn btn-lg btn-outline-light text-success" type="submit">
                                        <i class="fa fa-arrow-up"></i>
                                    </button>
                                {{Form::close()}}
                            @else
                                {{
                                    Form::open(array(
                                        'method' => 'POST',
                                        'route' => array('issues.vote', $issue->id),
                                    ))
                                }}
                                    {{ Form::token() }}
                                    <button class="btn btn-lg btn-outline-light text-secondary" type="submit">
                                        <i class="fa fa-arrow-up"></i>
                                    </button>
                                {{Form::close()}}
                            @endif

                            <div>{{ $issue->vote_count }}</div>
                        </td>
                        <th scope="row">{{ $issue->id }}</th>
                        <td>{{ $issue->title }}</td>
                        <td>
                            <ul>
                                @foreach ($issue->campaigns as $campaign)
                                    <li>
                                        <a href="{{ route('campaigns.edit', array( 'id' => $campaign->id )) }}">
                                            {{ $campaign->title }}
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </td>
                        <td>
                            <a href="{{ route('issues.edit', array( 'id' => $issue->id ))}}" class="btn btn-primary">Edit</a>
                        </td>
                    </tr>
                        
                    @endforeach
                </tbody>
            </table>

            {{ $paged_issues->links() }}
        </div>
    </div>
</div>
@endsection
