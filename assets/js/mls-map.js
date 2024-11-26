function initLeafletMap(title, geoX, geoY, location) {
    var map;

    // Check if we have geo-coordinates
    if (geoX && geoY) {
        // Initialize the map centered on the provided coordinates
        map = L.map('mls-property-map').setView([geoY, geoX], 13);
        L.marker([geoY, geoX]).addTo(map)
            .bindPopup(`<b>${title}</b>`);
    } else if (location) {
        // If no coordinates, initialize with a temporary center
        map = L.map('mls-property-map').setView([36.5, -6.0], 10);

        // Then geocode the location
        geocodeLocation(location, map, title);
    }

    // Add the tile layer to the map
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png?{foo}', {
		foo: 'bar', 
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);
}

function geocodeLocation(location, map, title) {
    // Use the OpenStreetMap Nominatim API to get coordinates from the location name
    fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(location)}`)
        .then(response => response.json())
        .then(data => {
            if (data.length > 0) {
                var lat = data[0].lat;
                var lon = data[0].lon;
				console.log('lat, lon' );
				console.log(lat, lon );
                // Set the map's view to the geocoded location
                map.setView([lat, lon], 13);

                // Place a marker at the geocoded location
                L.marker([lat, lon]).addTo(map)
                    .bindPopup(`<b>${title}</b>`);
            } else {
                console.error("Location not found.");
            }
        });
}
