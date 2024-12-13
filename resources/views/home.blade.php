
@extends('layouts.app')

@section('content')
    <h1>Home</h1>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Bucket Name</th>
                <th>Total Cost</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($buckets as $bucket)
                <tr>
                    <td>{{ $bucket['bucket'] }}</td>
                    <td>${{ $bucket['costPerMonth'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection