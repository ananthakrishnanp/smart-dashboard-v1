document.addEventListener('DOMContentLoaded', function() {
    const canvas = document.getElementById('metricsChart');
    const ctx = canvas.getContext('2d');

    //Canvas size
    canvas.width = 900; 
    canvas.height = 400; 

    //Fetching data
    const fetchData = async () => {
        try {
            const response = await fetch('php/fetch_metrics.php'); 
            const data = await response.json(); 

            if (data.online && data.error && data.offline) {
                drawChart(data);
            } else {
                console.error('Invalid data received from server:', data);
            }
        } catch (error) {
            console.error('Error fetching data:', error);
        }
    };

    //Chart Bar
    const drawRoundedRect = (x, y, width, height, radius) => {
        const maxRadius = Math.min(width / 2, height / 2); 
        const cornerRadius = Math.min(radius, maxRadius); 

        ctx.beginPath();
        ctx.moveTo(x + cornerRadius, y);
        ctx.lineTo(x + width - cornerRadius, y);
        ctx.quadraticCurveTo(x + width, y, x + width, y + cornerRadius);
        ctx.lineTo(x + width, y + height - cornerRadius);
        ctx.quadraticCurveTo(x + width, y + height, x + width - cornerRadius, y + height);
        ctx.lineTo(x + cornerRadius, y + height);
        ctx.quadraticCurveTo(x, y + height, x, y + height - cornerRadius);
        ctx.lineTo(x, y + cornerRadius);
        ctx.quadraticCurveTo(x, y, x + cornerRadius, y);
        ctx.closePath();
        ctx.fill();
    };

    // Function to draw the chart
    const drawChart = (data) => {
        // Clearing canvas
        ctx.clearRect(0, 0, canvas.width, canvas.height);

        // Labels
        const barIntervals = ['00:00', '00:30', '01:00', '01:30', '02:00', '02:30', '03:00', '03:30', '04:00', '04:30',
                              '05:00', '05:30', '06:00', '06:30', '07:00', '07:30', '08:00', '08:30', '09:00', '09:30',
                              '10:00', '10:30', '11:00', '11:30', '12:00', '12:30', '13:00', '13:30', '14:00', '14:30',
                              '15:00', '15:30', '16:00', '16:30', '17:00', '17:30', '18:00', '18:30', '19:00', '19:30',
                              '20:00', '20:30', '21:00', '21:30', '22:00', '22:30'];

        
        const timeLabels = ['00:00', '02:00', '04:00', '06:00', '08:00', '10:00', '12:00', '14:00', '16:00', '18:00', '20:00', '22:00'];

        // Filter data
        const onlineData = barIntervals.map(label => data.online[label] || 0);
        const errorData = barIntervals.map(label => data.error[label] || 0);
        const offlineData = barIntervals.map(label => data.offline[label] || 0);

        // Scale factor
        const onlineScalingFactor = 0.7; 
        const offlineScalingFactor = 1; 

        // Max Value
        const maxVal = Math.max(...onlineData, ...errorData, ...offlineData) * 1.2; // Scaling for visibility

      
        const separation = 10; 
        const barWidth = (canvas.width / barIntervals.length) - separation; 

        const scale = (canvas.height - 70) / maxVal; // Reduce the scale for more compact bars

        // Calculate width of bar
        const totalGraphWidth = (barWidth + separation) * barIntervals.length;

        
        const offsetX = (canvas.width - totalGraphWidth) / 2;

        barIntervals.forEach((label, index) => {
            const x = offsetX + (barWidth + separation) * index; // Add offsetX to shift the bars to the right

            // Online Bar
            ctx.fillStyle = 'rgba(75, 192, 192, 0.6)'; 
            const onlineHeight = onlineData[index] * scale * onlineScalingFactor; 
            drawRoundedRect(x, canvas.height - onlineHeight - 40, barWidth, onlineHeight, 10); 

            // Offline Bar
            ctx.fillStyle = 'rgba(255, 99, 132, 0.6)'; 
            const offlineHeight = offlineData[index] * scale * offlineScalingFactor; 
            drawRoundedRect(x, canvas.height - (onlineHeight + offlineHeight) - 50, barWidth, offlineHeight, 10);

            // Error bar
            ctx.fillStyle = '#CC79A7'; 
            const errorHeight = errorData[index] * scale; 
            drawRoundedRect(x, canvas.height - (onlineHeight + offlineHeight + errorHeight) - 40, barWidth, errorHeight, 10);

            
            if (timeLabels.includes(label)) {
               
                ctx.fillStyle = '#000';
                ctx.font = '13px Inter, sans-serif'; 
                ctx.fillText(label, x + barWidth / 3 - 3, canvas.height - 10); 
            }
        });
    };

    fetchData();
});
