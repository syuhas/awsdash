
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
        <div id="loadingSpinner" style="display: none;" class="spinner-grow text-primary" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
        <div id="loadingSpinner2" style="display: none;" class="spinner-grow text-secondary" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
        <div id="loadingSpinner3" style="display: none;" class="spinner-grow text-success" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
        <div id="loadingSpinner4" style="display: none;" class="spinner-grow text-danger" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
        <div id="loadingSpinner5" style="display: none;" class="spinner-grow text-warning" role="status">
            <span class="visually-hidden">Loading...</span>
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

    <script>
        document.getElementById('bucketSelect').addEventListener('change', function () {
            const bucketName = this.value;
            const detailsDiv = document.getElementById('bucketDetails');
            const objectTable = document.getElementById('objectTable');
            const spinners = document.getElementsByClassName('spinners');
            const spinner = document.getElementById('loadingSpinner');
            const spinner2 = document.getElementById('loadingSpinner2');
            const spinner3 = document.getElementById('loadingSpinner3');
            const spinner4 = document.getElementById('loadingSpinner4');
            const spinner5 = document.getElementById('loadingSpinner5');

            detailsDiv.style.display = 'none';

            Array.from(spinners).forEach(spinner => {
                spinner.style.height = '50vh';
            });

            spinner.style.display = 'block';
            spinner2.style.display = 'block';
            spinner3.style.display = 'block';
            spinner4.style.display = 'block';
            spinner5.style.display = 'block';



            
            fetch(`/api/bucket-details?bucket=${bucketName}`)
                .then(response => response.json())
                .then(data => {
                    document.getElementById('bucketName').innerText = `${data.bucket}`;
                    document.getElementById('bucketCost').innerText = `$ ${data.cost.toFixed(8)}`;
                    document.getElementById('bucketSize').innerText = `${data.size} GB`;
                    document.getElementById('bucketObjects').innerText = `${data.objectNumber}`;
                    objectTable.innerHTML = ''; // Clear existing objects

                    data.objects.forEach(object => {
                        const tr = document.createElement('tr');
                        const key = document.createElement('td');
                        const size = document.createElement('td');
                        const cost = document.createElement('td');
                        key.innerText = object.key;
                        size.innerText = object.sizeBytes;
                        cost.innerText = object.costPerMonth.toFixed(8);
                        objectTable.appendChild(tr);
                        tr.appendChild(key);
                        tr.appendChild(size);
                        tr.appendChild(cost);
                    });
                    const tr = document.createElement('tr');
                    const td1 = document.createElement('td');
                    const td2 = document.createElement('td');
                    const td3 = document.createElement('td');
                    td1.innerText = '...';
                    td2.innerText = '...';
                    td3.innerText = '...';
                    objectTable.appendChild(tr);
                    tr.appendChild(td1);
                    tr.appendChild(td2);
                    tr.appendChild(td3);
                    spinner.style.display = 'none';
                    spinner2.style.display = 'none';
                    spinner3.style.display = 'none';
                    spinner4.style.display = 'none';
                    spinner5.style.display = 'none';
                    Array.from(spinners).forEach(spinner => {
                        spinner.style.height = '0';
                    });

                    detailsDiv.style.display = 'block'; // Show details
                })
                .catch(error => {
                    console.error('Error fetching bucket details:', error);
                });
        });
    </script>
</div>
@endsection