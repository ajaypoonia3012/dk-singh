<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Progress Report</title>

    <style>

        body{
            font-family: DejaVu Sans, sans-serif;
            font-size:12px;
            color:#222;
        }

        h1{
            text-align:center;
            color:#d4a017;
        }

        h2{
            margin-top:25px;
            border-bottom:1px solid #ddd;
            padding-bottom:5px;
        }

        table{
            width:100%;
            border-collapse:collapse;
            margin-top:10px;
        }

        table td,
        table th{
            border:1px solid #ddd;
            padding:8px;
        }

        .summary{
            margin-top:20px;
        }

        .note{
            background:#f7f7f7;
            padding:12px;
            border-radius:6px;
        }

    </style>
</head>
<body>

<h1>{{ $setting->site_name }}</h1>

<p style="text-align:center;">
    Member Progress Report
</p>

<hr>

<h2>Member Information</h2>

<table>
    <tr>
        <td><strong>Name</strong></td>
        <td>{{ $user->name }}</td>
    </tr>

    <tr>
        <td><strong>Email</strong></td>
        <td>{{ $user->email }}</td>
    </tr>

    <tr>
        <td><strong>Generated</strong></td>
        <td>{{ now()->format('d M Y') }}</td>
    </tr>
</table>

<h2>Transformation Summary</h2>

<table>
    <tr>
        <td>Starting Weight</td>
        <td>
            {{ $firstCheckIn?->weight ?? '-' }} kg
        </td>
    </tr>

    <tr>
        <td>Current Weight</td>
        <td>
            {{ $latestCheckIn?->weight ?? '-' }} kg
        </td>
    </tr>

    <tr>
        <td>Weight Lost</td>
        <td>
            {{ $weightLost }} kg
        </td>
    </tr>

    <tr>
        <td>Journey Duration</td>
        <td>
            {{ $journeyDays }} Days
        </td>
    </tr>
</table>

<h2>Compliance</h2>

<table>
    <tr>
        <td>Workout Compliance</td>
        <td>{{ $workoutCompliance }}%</td>
    </tr>

    <tr>
        <td>Diet Compliance</td>
        <td>{{ $dietCompliance }}%</td>
    </tr>
</table>

<h2>Coach Feedback</h2>

<div class="note">
    {{ $latestCoachNote?->note ?? 'No coach feedback available.' }}
</div>

<h2>Check-In Timeline</h2>

<table>

    <thead>
        <tr>
            <th>Date</th>
            <th>Weight</th>
            <th>Waist</th>
            <th>Energy</th>
            <th>Mood</th>
        </tr>
    </thead>

    <tbody>

    @foreach($checkIns as $checkIn)

        <tr>

            <td>
                {{ $checkIn->created_at->format('d M Y') }}
            </td>

            <td>
                {{ $checkIn->weight }} kg
            </td>

            <td>
                {{ $checkIn->waist }} cm
            </td>

            <td>
                {{ $checkIn->energy_level }}
            </td>

            <td>
                {{ $checkIn->mood }}
            </td>

        </tr>

    @endforeach

    </tbody>

</table>

</body>
</html>