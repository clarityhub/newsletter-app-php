@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <ul class="nav nav-pills mb-3">
                <li class="nav-item">
                    <a class="btn btn-light" href="{{ route('campaigns') }}">
                        {{ __('pages.cancel') }}
                    </a>
                </li>
            </ul>


            <div class="card">
                <div class="card-header">Create Campaign</div>

                <div class="card-body">
                    <p class="text-muted">
                        Create a campaign by providing links to Github issues.
                        This will pull the issue information and will take 
                        create a preview of the campaign before you accept
                        it and send it to Mailchimp.
                    </p>
                </div>
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

                    {{ Form::open(array('route' => 'campaigns.store')) }}
                        {{ Form::token() }}

                        <div class="form-group">
                            {{ Form::label('title', 'Campaign Title') }}
                            <?php $date = date('n/j/y') ?>
                            {{ Form::text('title', session('input.title') ?? "Open Source Email $date", array('class' => 'form-control')) }}
                            @if ($errors->has('title'))
                                @foreach ($errors->get('title') as $error)
                                    <p class="form-text text-danger" role="alert">
                                        {{ $error }}
                                    </p>
                                @endforeach
                            @endif
                        </div>

                        <div class="form-group">
                            {{ Form::label('subject_line', 'Subject Line') }}
                            {{ Form::text('subject_line', session('input.subject_line') ?? 'Clarity Hub Weekly', array('class' => 'form-control')) }}
                            <small class="form-text text-muted">
                                The subject line that will appear in the user's inbox
                            </small>
                            @if ($errors->has('subject_line'))
                                @foreach ($errors->get('subject_line') as $error)
                                    <p class="form-text text-danger" role="alert">
                                        {{ $error }}
                                    </p>
                                @endforeach
                            @endif
                        </div>

                        <div class="form-group">
                            {{ Form::label('preview_text', 'Preview Text') }}
                            {{ Form::text('preview_text', session('input.preview_text') ?? 'Clarity Hub Weekly | Weekly Open Source Issues straight to your inbox', array('class' => 'form-control')) }}
                            <small class="form-text text-muted">
                                The first line of text that will appear in the user's inbox
                            </small>
                            @if ($errors->has('preview_text'))
                                @foreach ($errors->get('preview_text') as $error)
                                    <p class="form-text text-danger" role="alert">
                                        {{ $error }}
                                    </p>
                                @endforeach
                            @endif
                        </div>
        
                        <div class="form-group">
                            {{ Form::label('greeting', 'Greeting') }}
                            {{ Form::text('greeting', session('input.greeting') ?? '', array('class' => 'form-control')) }}
                            <small class="form-text text-muted">
                                This appears at the top of the email. Say something nice!
                            </small>
                            @if ($errors->has('greeting'))
                                @foreach ($errors->get('greeting') as $error)
                                    <p class="form-text text-danger" role="alert">
                                        {{ $error }}
                                    </p>
                                @endforeach
                            @endif
                        </div>

                        <div class="form-group">
                            {{ Form::label('github_issues', 'Github Issue URLs') }}
                            {{ Form::textarea('github_issues', session('input.github_issues') ?? '', array('class' => 'form-control')) }}
                            <small class="form-text text-muted">
                                One URL per line
                            </small>
                            @if ($errors->has('github_issues'))
                                @foreach ($errors->get('github_issues') as $error)
                                    <p class="form-text text-danger" role="alert">
                                        {{ $error }}
                                    </p>
                                @endforeach
                            @endif
                        </div>

                        <button type="submit" class="btn btn-success">Create Campaign</button>
                    {{ Form::close() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
