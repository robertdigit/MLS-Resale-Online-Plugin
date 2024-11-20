<?php
// mls-map.php

function mls_plugin_display_map($title, $geoX, $geoY, $location) {
    // Static values (replace with dynamic values as needed)
//     $title = 'Property 2';
//     $geoX = ''; // Empty, meaning we will use location
//     $geoY = '';
//     $location = 'Coín';

    // Pass the individual values to JavaScript
    ?>
    <div id="mls-property-map" style="height: 350px;"></div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Passing PHP values to JS
            var propertyTitle = "<?php echo esc_js($title); ?>";
            var propertyGeoX = "<?php echo esc_js($geoX); ?>";
            var propertyGeoY = "<?php echo esc_js($geoY); ?>";
            var propertyLocation = "<?php echo esc_js($location); ?>";
//             console.log(propertyTitle, propertyGeoX, propertyGeoY, propertyLocation);
            // Call the JS function with these values
            initLeafletMap(propertyTitle, propertyGeoX, propertyGeoY, propertyLocation);
        });
    </script>
    <?php
}
?>
