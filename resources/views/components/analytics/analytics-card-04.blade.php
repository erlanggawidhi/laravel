<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Real Time Suhu Pameungpeuk Garut</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .dark {
            background-color: #2d3748;
            color: #f7fafc;
        }
    </style>
</head>

<body class="dark">

    <div class="flex flex-col col-span-full sm:col-span-6 bg-white dark:bg-gray-800 shadow-sm rounded-xl">
        <header class="px-5 py-4 border-b border-gray-100 dark:border-gray-700/60 flex items-center">
            <h2 class="font-semibold text-gray-800 dark:text-gray-100">Suhu Pameungpeuk Garut</h2>
            <div class="relative ml-2" x-data="{ open: false }" @mouseenter="open = true" @mouseleave="open = false">
                <button class="block" aria-haspopup="true" :aria-expanded="open" @focus="open = true" @focusout="open = false" @click.prevent>
                    <svg class="fill-current text-gray-400 dark:text-gray-500" width="16" height="16" viewBox="0 0 16 16">
                        <path d="M8 0C3.6 0 0 3.6 0 8s3.6 8 8 8 8-3.6 8-8-3.6-8-8-8zm0 12c-.6 0-1-.4-1-1s.4-1 1-1 1 .4 1 1-.4 1-1 1zm1-3H7V4h2v5Z" />
                    </svg>
                </button>
                <div class="z-10 absolute bottom-full left-1/2 -translate-x-1/2">
                    <div class="bg-white dark:bg-gray-800 dark:text-gray-100 border border-gray-200 dark:border-gray-700/60 px-3 py-2 rounded-lg shadow-lg overflow-hidden mb-2" x-show="open" x-transition:enter="transition ease-out duration-200 transform" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-out duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" x-cloak>
                        <div class="text-xs text-center whitespace-nowrap">Built with <a class="underline" @focus="open = true" @focusout="open = false" href="https://www.chartjs.org/" target="_blank">Chart.js</a></div>
                    </div>
                </div>
            </div>
        </header>
        <div class="px-5 py-3">
            <div class="flex items-start">
                <div class="text-3xl font-bold text-gray-800 dark:text-gray-100 mr-2 tabular-nums"><span id="temperature-value">--</span>°C</div>
                <div id="temperature-deviation" class="text-sm font-medium px-1.5 rounded-full"></div>
            </div>
        </div>
        <div class="grow">
            <canvas id="temperature-chart" width="595" height="248"></canvas>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const apiKey = 'dff875fb10cfae1af293b659110fff2e'; // API key Anda
            const apiUrl = `https://api.openweathermap.org/data/2.5/weather?id=1632972&units=metric&appid=${apiKey}`;

            function fetchRealTimeTemperature() {
                fetch(apiUrl)
                    .then(response => response.json())
                    .then(data => {
                        const temperature = data.main.temp;
                        document.getElementById('temperature-value').textContent = temperature.toFixed(1);
                        // Update deviation if needed
                        // document.getElementById('temperature-deviation').textContent = data.deviation;
                    })
                    .catch(error => console.error('Error fetching real-time temperature:', error));
            }

            // Initialize temperature fetch
            fetchRealTimeTemperature();

            // Fetch temperature every 1 seconds
            setInterval(fetchRealTimeTemperature, 2000);

            // Chart.js configuration
            const ctx = document.getElementById('temperature-chart').getContext('2d');
            const chart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: [], // Initialize labels array
                    datasets: [{
                        label: 'Suhu (°C)',
                        data: [], // Initialize data array
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 1,
                        fill: false
                    }]
                },
                options: {
                    scales: {
                        x: {
                            title: {
                                display: true,
                                text: 'Waktu'
                            }
                        },
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Suhu (°C)'
                            },
                            ticks: {
                                callback: function(value) {
                                    return value + '°C';
                                }
                            }
                        }
                    }
                }
            });

            // Function to update chart data
            function updateChartData() {
                fetch(apiUrl)
                    .then(response => response.json())
                    .then(data => {
                        const temperature = data.main.temp;
                        const currentTime = new Date().toLocaleTimeString();

                        // Add new data to chart
                        chart.data.labels.push(currentTime);
                        chart.data.datasets[0].data.push(temperature);

                        // Maintain chart data range of 5
                        if (chart.data.labels.length > 5) {
                            chart.data.labels.shift();
                            chart.data.datasets[0].data.shift();
                        }

                        chart.update("none");
                    })
                    .catch(error => console.error('Error updating chart data:', error));
            }

            // Update chart data every 1 seconds
            setInterval(updateChartData, 1000);
        });
    </script>

</body>

</html>