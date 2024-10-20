document.addEventListener("DOMContentLoaded", function () {
    const machineSelect = document.getElementById('machine-select');
    const startDateInput = document.getElementById('start-date');
    const endDateInput = document.getElementById('end-date');
    const canvas = document.getElementById('vitals-chart');
    const ctx = canvas.getContext('2d');

    // Fetch Machines
    function fetchMachines() {
        const xhr = new XMLHttpRequest();
        xhr.open('GET', 'fetch_machines.php', true);

        xhr.onload = function () {
            if (this.status === 200) {
                const machines = JSON.parse(this.responseText);
                populateMachineDropdown(machines);
            } else {
                console.error('Error fetching machines.');
            }
        };
        xhr.send();
    }

    // Machine Dropdown
    function populateMachineDropdown(machines) {
        machineSelect.innerHTML = ''; 
        machines.forEach(machine => {
            const option = document.createElement('option');
            option.value = machine.machine_id;
            option.textContent = machine.machine_name;
            machineSelect.appendChild(option);
        });

        
        fetchChartData(machineSelect.value, startDateInput.value, endDateInput.value);
    }

    const padding = 50; 

    // High DPI Screen Fix
    function resizeCanvasForHighDPI(canvas, ctx) {
        const pixelRatio = window.devicePixelRatio || 1;
        const width = canvas.offsetWidth;
        const height = canvas.offsetHeight;

        // Set the canvas resolution to match the pixel ratio
        canvas.width = width * pixelRatio;
        canvas.height = height * pixelRatio;

        // Scale the context to ensure sharp rendering
        ctx.scale(pixelRatio, pixelRatio);

        canvas.style.width = `${width}px`;
        canvas.style.height = `${height}px`;
    }

    function drawGrid(ctx, width, height, labelsX, labelsY) {
        ctx.strokeStyle = '#e0e0e0';
        ctx.lineWidth = 1;
        const chartWidth = width - padding * 2;
        const chartHeight = height - padding * 2;

        // Grid
        const labelInterval = 8; 
        for (let i = 0; i < labelsX.length; i++) {
            if (i % labelInterval === 0) {  
                const x = padding + (chartWidth / (labelsX.length - 1)) * i;
                ctx.beginPath();
                ctx.moveTo(x, padding);
                ctx.lineTo(x, height - padding);
                ctx.stroke();

                // X-axis labels
                ctx.fillStyle = '#333';
                ctx.textAlign = 'center';
                ctx.fillText(labelsX[i], x, height - padding + 20);
            }
        }

        // Draw horizontal grid lines
        const maxY = Math.max(...labelsY);
        for (let i = 0; i <= 10; i++) {
            const y = padding + (chartHeight / 10) * i;
            const label = ((maxY / 10) * (10 - i)).toFixed(2);
            ctx.beginPath();
            ctx.moveTo(padding, y);
            ctx.lineTo(width - padding, y);
            ctx.stroke();

            // Y-axis labels
            ctx.fillStyle = '#333';
            ctx.textAlign = 'right';
            ctx.fillText(label, padding - 10, y + 3);
        }
    }

    function drawLineGraph(ctx, data, color, width, height, minVal, maxVal) {
        ctx.strokeStyle = color;
        ctx.lineWidth = 2;
        ctx.beginPath();

        const chartWidth = width - padding * 2;
        const chartHeight = height - padding * 2;

        const scaleX = chartWidth / (data.length - 1);
        const scaleY = chartHeight / (maxVal - minVal);

        for (let i = 0; i < data.length; i++) {
            const x = padding + i * scaleX;
            const y = padding + chartHeight - (data[i] - minVal) * scaleY;

            if (i === 0) {
                ctx.moveTo(x, y);
            } else {
                ctx.lineTo(x, y);
            }
        }

        ctx.stroke();
    }

    function clearCanvas(ctx, width, height) {
        ctx.clearRect(0, 0, width, height);
    }

    function fetchChartData(machineId, startDate, endDate) {
        const xhr = new XMLHttpRequest();
        xhr.open('POST', 'fetch_data.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onload = function () {
            if (this.status === 200) {
                const responseData = JSON.parse(this.responseText);
                updateChart(responseData);
            } else {
                document.getElementById('status-message').textContent = "Error fetching data.";
            }
        };
        xhr.send(`machine_id=${machineId}&start_date=${startDate}&end_date=${endDate}`);
    }

    function updateChart(data) {
        const temperatureData = data.map(row => row.temperature);
        const pressureData = data.map(row => row.pressure);
        const labelsX = data.map(row => row.time);
        const labelsY = [...temperatureData, ...pressureData];

        const minTemperature = Math.min(...temperatureData);
        const maxTemperature = Math.max(...temperatureData);
        const minPressure = Math.min(...pressureData);
        const maxPressure = Math.max(...pressureData);

        const minVal = Math.min(minTemperature, minPressure);
        const maxVal = Math.max(maxTemperature, maxPressure);

        clearCanvas(ctx, canvas.width, canvas.height);
        drawGrid(ctx, canvas.width / window.devicePixelRatio, canvas.height / window.devicePixelRatio, labelsX, labelsY);

        drawLineGraph(ctx, temperatureData, 'rgba(255, 99, 132, 1)', canvas.width / window.devicePixelRatio, canvas.height / window.devicePixelRatio, minVal, maxVal);
        drawLineGraph(ctx, pressureData, 'rgba(54, 162, 235, 1)', canvas.width / window.devicePixelRatio, canvas.height / window.devicePixelRatio, minVal, maxVal);
    }

    
    resizeCanvasForHighDPI(canvas, ctx);

   
    fetchMachines();

    // Update during filter change
    machineSelect.addEventListener('change', () => {
        fetchChartData(machineSelect.value, startDateInput.value, endDateInput.value);
    });

    startDateInput.addEventListener('change', () => {
        fetchChartData(machineSelect.value, startDateInput.value, endDateInput.value);
    });

    endDateInput.addEventListener('change', () => {
        fetchChartData(machineSelect.value, startDateInput.value, endDateInput.value);
    });
});
