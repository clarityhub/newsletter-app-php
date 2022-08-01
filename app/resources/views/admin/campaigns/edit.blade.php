@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-sm-12">
            <ul class="nav nav-pills mb-3">
                <li class="nav-item">
                    <a class="btn btn-light" href="{{ route('campaigns') }}">
                        {{ __('pages.back') }}
                    </a>
                </li>
                <li class="nav-item">
                    <button
                        class="btn btn-danger"
                        data-toggle="modal"
                        data-target="#deleteCampaignModal"
                    >
                        Delete
                    </button>
                </li>
            </ul>

            <div class="card mb-3">
                <div class="card-header">Edit Campaign</div>

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
                          'route' => array('campaigns.update', $campaign->id),
                        ))
                    }}
                        {{ Form::token() }}

                        <div class="form-group">
                            {{ Form::label('title', 'Campaign Title') }}
                            {{ Form::text('title', session('input.title') ?? $campaign->title, array('class' => 'form-control')) }}
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
                            {{ Form::text('subject_line', session('input.subject_line') ?? $campaign->subject_line, array('class' => 'form-control')) }}
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
                            {{ Form::text('preview_text', session('input.preview_text') ?? $campaign->preview_text, array('class' => 'form-control')) }}
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
                            {{ Form::text('greeting', session('input.greeting') ?? $campaign->greeting, array('class' => 'form-control')) }}
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

                        <button type="submit" class="btn btn-success">Edit Campaign</button>
                    {{ Form::close() }}
                </div>
            </div>

            <div class="card mb-3">
                <div class="card-header">Schedule Campaign</div>
                <div class="card-body">
                    <p class="text-muted">
                        Scheduling a campaign will create the campaign in Mailchimp,
                        schedule it for the given time, and will send you a test email.
                    </p>

                    <p class="text-muted">
                        Once created, you will have to update the campaign in Mailchimp.
                        A link to the campaign will appear below.
                    </p>

                    @if (session('action.status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('action.status') }}
                        </div>
                    @endif

                    @if (session('action.error'))
                        <div class="alert alert-danger" role="alert">
                            {{ session('action.error') }}
                        </div>
                    @endif

                    {{
                        Form::open(array(
                          'method' => 'POST',
                          'route' => array('campaigns.actions.schedule', $campaign->id),
                        ))
                    }}
                        {{ Form::token() }}

                        <div class="form-group">
                            {{ Form::label('emails', 'Test emails') }}
                            {{ Form::text('emails',  session('emails') ?? '', array('class' => 'form-control')) }}
                            <small class="form-text text-muted">Use commas to delimit emails. At least 1 email is required.</small>
                        </div>
                        
                        <div class="form-group">
                            {{ Form::label('time', 'Scheduled Date') }}
                            {{ Form::date('time',  session('date') ?? \Carbon\Carbon::now(), array('class' => 'form-control')) }}
                            <small class="form-text text-muted">The time will always be set to 9:00AM MST.</small>
                        </div>

                        <a href="{{ route('campaigns.preview', ['id' => $campaign->id]) }}" class="btn btn-primary">Preview</a>
                        
                        @if (!empty($campaign->mailchimp_id))
                            <button type="submit" class="btn btn-primary">Re-schedule Campaign</button>
                        @else
                            <button type="submit" class="btn btn-primary">Schedule Campaign</button>
                        @endif
                        

                        @if (!empty($campaign->mailchimp_id))
                            <a href="{{$campaign->mailchimp_url}}" class="btn btn-outline-info" target="blank">
                                Edit Campaign in Mailchimp
                            </a>

                            <small class="form-text text-danger">Editing a campaign will unschedule it from Mailchimp</small>
                        @endif

                    {{ Form::close() }}


                    @if (!empty($campaign->mailchimp_id))
                        {{
                            Form::open(array(
                                'method' => 'POST',
                                'route' => array('campaigns.actions.detach', $campaign->id),
                            ))
                        }}
                            {{ Form::token() }}

                            <button type="submit" class="btn btn-danger">Detach From Mailchimp</button>
                        {{ Form::close() }}
                    @endif
                </div>
            </div>
        <div class="card mb-3">
                <div class="card-header">Campaign Issues</div>
                <div class="card-body">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Repo Title</th>
                                <th scope="col">Title</th>
                                <th scope="col">Short Description</th>
                                <th scope="col">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($issues as $issue)
                            <tr>
                                <th scope="row">{{ $issue->id }}</th>
                                <td>{{ $issue->repository_title }}</td>
                                <td>
                                    <a
                                        href="{{ $issue->html_url }}"
                                        target="_blank"
                                    >
                                        {{ $issue->title }}
                                    </a>
                                </td>
                                <td>{!! $issue->html_short_description !!}</td>
                                <td>
                                    <a
                                        class="btn btn-primary"
                                        href="{{ route('issues.edit', ['id' => $issue->id]) }}?returnTo={{ urlencode( route('campaigns.edit', ['id' => $campaign->id]) ) }}"
                                    >
                                        Edit
                                    </a>

                                    <button
                                        class="btn btn-danger"
                                        data-toggle="modal"
                                        data-target="#deleteIssueModal{{ $issue->id }}"
                                    >
                                        Remove
                                    </button>
                                </td>
                            </tr>
                                
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>


@foreach ($issues as $issue)

<!-- Delete Issue Modal -->
<div class="modal fade" id="deleteIssueModal{{ $issue->id }}" tabindex="-1" role="dialog" aria-labelledby="deleteIssueModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteIssueModalLabel">Remove Issue</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Are you sure you want to remove this issue "<span class="text-primary">{{ $issue->title }}</span>"
                from this campaign "<span class="text-primary">{{ $campaign->title }}</span>"?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn" data-dismiss="modal">Cancel</button>

                {{
                    Form::open(array(
                        'method' => 'DELETE',
                        'route' => array('campaigns.issues.delete', $campaign->id, $issue->id),
                    ))
                }}
                    {{ Form::token() }}
                    <input class="btn btn-danger" type="submit" value="Remove Issue" />
                {{ Form::close() }}
                
            </div>
        </div>
    </div>
</div>
    
@endforeach

<!-- Delete Campaign Modal -->
<div class="modal fade" id="deleteCampaignModal" tabindex="-1" role="dialog" aria-labelledby="deleteCampaignLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteCampaignLabel">Delete Campaign</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete this campaign "<span class="text-primary">{{ $campaign->title }}</span>"?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn" data-dismiss="modal">Cancel</button>

                {{
                    Form::open(array(
                        'method' => 'DELETE',
                        'route' => array('campaigns.delete', $campaign->id),
                    ))
                }}
                    {{ Form::token() }}
                    <input class="btn btn-danger" type="submit" value="Delete">
                {{ Form::close() }}
                
            </div>
        </div>
    </div>
</div>

@endsection
