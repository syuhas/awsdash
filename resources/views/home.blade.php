
@extends('layouts.app')

@section('content')
<h1>Welcome to the Bucket Manager</h1>
<div class="card">
    <div class="summaryText">
        <p>This is a sample application that displays S3 buckets metadata and contents.</p>
        <p>The purpose of this application is to demonstrate gathering service metrics with an event driven architecture, and to practice backfilling and managing databases.</p>
    </div>
    <div class="row">
        <div class="col-md-6">
            <table class="table">
                <thead>
                    <tr>
                        <th>Total Number of Buckets</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>{{ count($buckets) }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="col-md-6">
            <table class="table">
                <thead>
                    <tr>
                        <th colspan="3">Most Expensive Bucket</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Account</td>
                        <td>Bucket</td>
                        <td>Cost</td>
                    </tr>
                    <tr>
                        <td>{{ $buckets[0]['account_id'] }}</td>
                        <td>{{ $buckets[0]['bucket'] }}</td>
                        <td>$ {{ $buckets[0]['costPerMonth'] }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <table class="table">
                <thead>
                    <tr>
                        <th>Total Cost of All Buckets</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>$ {{ $bucketsTotalCost }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="col-md-6">
            <table class="table">
                <thead>
                    <tr>
                        <th>Total Size of All Buckets</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>{{ $bucketsTotalSize }} GB</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection