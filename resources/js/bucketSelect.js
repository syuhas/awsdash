document.getElementById('bucketSelect').addEventListener('change', async function () {
    const bucketName = this.value;
    const detailsDiv = document.getElementById('bucketDetails');
    const objectTable = document.getElementById('objectTable');
    const spinnersContainer = document.querySelector('.spinners');
    const spinners = document.querySelectorAll('.spinners > div');

    spinnersContainer.style.height = '50vh'; // Show spinners
    detailsDiv.style.display = 'none'; // Hide details
    // Now show each spinner element
    spinners.forEach(spinner => {
        console.log(spinner);
        spinner.style.display = 'block';
    });
    
    try {
        const response = await fetch(`/api/bucket-details?bucket=${bucketName}`);
        const data = await response.json();
        
        document.getElementById('bucketName').innerText = `${data.bucket}`;
        document.getElementById('bucketCost').innerText = `$ ${data.cost.toFixed(8)}`;
        document.getElementById('bucketSize').innerText = `${data.size} GB`;
        document.getElementById('bucketObjects').innerText = `${data.objectNumber}`;
        
        objectTable.innerHTML = ''; // Clears the existing objects
        
        // Helper function for dynamically creating rows
        const createRow = (values) => {
            const tr = document.createElement('tr');
            values.forEach(value => {
                const td = document.createElement('td');
                td.innerText = value;
                tr.appendChild(td);
            })
            objectTable.appendChild(tr);
        };
        
        data.objects.forEach(({ key, sizeBytes, costPerMonth }) => {
            createRow([key, sizeBytes, costPerMonth.toFixed(8)]);
        })
        
        //  Seperator row
        createRow(['...', '...', '...']);
        
    } catch (error) {
        console.error('Error fetching bucket details:', error);
    } finally {
        // Now hide each spinner element
        spinners.forEach(spinner => {
            spinner.style.display = 'none';
        });
        spinnersContainer.style.height = '0'; // Hide spinners
        detailsDiv.style.display = 'block'; // Show details
    }
});