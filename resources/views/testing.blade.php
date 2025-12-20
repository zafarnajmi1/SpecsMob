<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Testing</title>
</head>

<body>
    <h1>Testing Scraper API</h1>

    <script>
        // JavaScript to call the scraper API using fetch
        fetch('/proxy-scrape', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                url: 'https://www.lieferando.de/en/menu/day-night-pizzaservice-stuttgart'
            }),
        })
            .then(response => response.json())
            .then(data => console.log(data))  // Log the response data
            .catch(error => console.error('Error:', error));  // Handle any errors


    </script>
</body>

</html>