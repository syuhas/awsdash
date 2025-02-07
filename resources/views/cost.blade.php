
@extends('layouts.app')

@section('content')
<h1>Cost Explorer</h1>
<div class="card">
    <select class="form-select" id="bucketSelect">
        <option selected disabled>Select Bucket:</option>
        @foreach($buckets as $bucket)
            <option value="{{ $bucket['bucket'] }}">{{ $bucket['bucket'] }}</option>
        @endforeach
    </select>
    <div class="spinners">
        <div style="display: none;" class="spinner-grow text-primary" role="status">
        </div>
        <div style="display: none;" class="spinner-grow text-secondary" role="status">
        </div>
        <div style="display: none;" class="spinner-grow text-success" role="status">
        </div>
        <div style="display: none;" class="spinner-grow text-danger" role="status">
        </div>
        <div style="display: none;" class="spinner-grow text-warning" role="status">
        </div>
    </div>

    <div id="bucketDetails" class="mt-4" style="display: none;">
        <h4>Bucket Details</h4>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Bucket</th>
                    <th>Total Size</th>
                    <th>Total Objects</th>
                    <th>Cost</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td id="bucketName"></td>
                    <td id="bucketSize"></td>
                    <td id="bucketObjects"></td>
                    <td id="bucketCost"></td>
                </tr>
            </tbody>
        </table>
        <br><br>
        <h4>Objects Preview:</h4>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Key</th>
                    <th>Size</th>
                    <th>Cost</th>
                </tr>
            </thead>
            <tbody id="objectTable">
            </tbody>
        </table>
    
</div>
@endsection