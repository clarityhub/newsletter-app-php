@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-sm-12">
            <ul class="nav nav-pills mb-3">
                <li class="nav-item">
                    <a class="btn btn-light" href="{{ empty($returnTo) ? route('issues') : urldecode( $returnTo ) }}">
                        {{ __('pages.back') }}
                    </a>
                </li>
            </ul>

            <div class="card mb-3">
                <div class="card-header">Edit Issue</div>

                <div class="card-body">
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

                    {{
                        Form::open(array(
                          'method' => 'PUT',
                          'route' => array('issues.update', $issue->id),
                        ))
                    }}
                        {{ Form::token() }}

                        <input type="hidden" name="return_to" value="{{ urldecode($returnTo) }}" />

                        <div class="form-group">
                            {{ Form::label('title', 'Title') }}
                            {{ Form::text('title', $issue->title, array('class' => 'form-control')) }}
                            @if ($errors->has('title'))
                                @foreach ($errors->get('title') as $error)
                                    <p class="form-text text-danger" role="alert">
                                        {{ $error }}
                                    </p>
                                @endforeach
                            @endif
                        </div>

                        <div class="form-group">
                            {{ Form::label('html_description', 'HTML Description') }}
                            {{ Form::textarea('html_description', $issue->html_description, array('class' => 'form-control')) }}
                            @if ($errors->has('html_description'))
                                @foreach ($errors->get('html_description') as $error)
                                    <p class="form-text text-danger" role="alert">
                                        {{ $error }}
                                    </p>
                                @endforeach
                            @endif
                        </div>
            
                        <div class="form-group">
                            {{ Form::label('html_short_description', 'Short HTML Description') }}
                            {{ Form::textarea('html_short_description', $issue->html_short_description, array('class' => 'form-control')) }}
                            @if ($errors->has('html_short_description'))
                                @foreach ($errors->get('html_short_description') as $error)
                                    <p class="form-text text-danger" role="alert">
                                        {{ $error }}
                                    </p>
                                @endforeach
                            @endif
                        </div>

                        <button type="submit" class="btn btn-success">Edit Issue</button>
                    {{ Form::close() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
