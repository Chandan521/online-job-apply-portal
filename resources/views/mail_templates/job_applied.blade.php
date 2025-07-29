<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Job Application Confirmation</title>
</head>

<body style="font-family: Arial, sans-serif; background-color: #f4f4f4; padding: 20px;">
    <div style="max-width: 600px; margin: auto; background: #fff; padding: 30px; border-radius: 10px;">
        <h2>Hello {{ $user->name }},</h2>

        <p>Thank you for applying for the position of <strong>{{ $job->title }}</strong> at
            <strong>{{ $job->company }}</strong>.</p>

        <p>Your application has been received and will be reviewed by the recruiter.</p>

        <hr>
        <h4>Job Details:</h4>
        <ul>
            <li><strong>Location:</strong> {{ $job->location ?? 'N/A' }}</li>
            <li>
                <strong>Type:</strong>
                @if (is_array($job->type))
                    @foreach ($job->type as $type)
                        <span style="background:#e0f0ff; padding:4px 8px; border-radius:4px; margin-right:4px;">
                            {{ $type }}
                        </span>
                    @endforeach
                @else
                    {{ $job->type }}
                @endif
            </li>


            <li><strong>Deadline:</strong> {{ \Carbon\Carbon::parse($job->deadline)->format('d M Y') }}</li>
        </ul>

        <p>You can check the status of your application in your dashboard.</p>

        <br>
        <p>Regards,<br>
            <strong>{{ setting('site_name', config('app.name')) }}</strong> Team
        </p>
    </div>
</body>

</html>
