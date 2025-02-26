<?php

function mls_plugin_get_current_language() {
    if (!get_option('mls_plugin_style_proplanghide')) {
        $language_code = get_option('mls_plugin_prop_language', '1'); // Default to English
    }else {
        // Fetch from temporary option
        $language_code = get_option('mls_temp_language_code', '1');
    }
    $languages_map = [
        '1' => 'en', '2' => 'es', '3' => 'de', '4' => 'fr', '5' => 'nl', 
        '6' => 'da', '7' => 'ru', '8' => 'sv', '9' => 'pl', '10' => 'no', 
        '11' => 'tr', '13' => 'fi', '14' => 'hu'
    ];
    return $languages_map[$language_code] ?? 'en'; // Fallback to English if not found
}



function mls_plugin_get_translations() {
    return [
        'en' => [
        
        'labels' => [
            'searchtitle' => 'Discover your ideal home today!',
            'sales' => 'Sales',
			'long_rentals' => 'Rentals',
			'short_rentals' => 'Holiday',
			'new_development' => 'New development',
			'area' => 'Area:',
			'no_area' => 'No locations available',
            'property_type' => 'Property Type:',
			'no_property_type' => 'No property types available',
            'reference_id' => 'Reference ID:',
            'beds' => 'Beds:',
            'baths' => 'Baths:',
            'min_price' => 'Min Price:',
            'max_price' => 'Max Price:',
			'price' => 'Price:',
			'price_selector' => 'Price selector',
			'price_range' => ' Price Range:',
			'sort_by' => 'Sort by:',
			'for_rent' => 'For Rent',
			'for_sale' => 'For Sale',
			'featured' => 'Featured',
			'tour_type' => 'Tour Type:',
			'tour_type_desc' => '"If you are unable to visit the property in person, we can visit for you and connect via whatsapp video to give you a tour."',
        ],
        'placeholders' => [
            'search_reference_id' => 'Search by Reference ID',
			'search_area' => 'Select location',
			'search_property_type' => 'Select Property types',
			'your_phone' => 'Your Phone',
			'your_email' => 'Your Email',
			'your_name' => 'Your Name',
			'buyer' => 'Buyer',
			'agent' => 'Agent',
			'person' => 'In Person',
			'video' => 'Video',
			'comment' => "I'm interested in ",
        ],
        'buttons' => [
            'search_properties' => 'Search Properties',
			'submit_request' => 'Submit Request',
			'view_more' => 'View More',
			'reset' => 'Reset',
			'back_previous' => 'Back to previous page',
			'back_home' => 'Back to Home',
			'virtual_tour' => 'Virtual Tour',
			'video_tour' => 'Video Tour',
			'book_viewing' => 'Book a Viewing',
        ],
        'options' => [
                'any' => 'Any',
                'sort_lowest_price' => 'Price (Lowest First)',
                'sort_highest_price' => 'Price (Highest First)',
                'sort_location' => 'Location',
                'sort_most_recent' => 'Most Recent First',
                'sort_oldest' => 'Oldest First',
        ],
		'error' => [
        'prpreferr' => 'Add Reference attributte & Property ID to show the Properties',
        'mls_nosearchresult' => 'No search results found. Please try searching again.',
        'mls_sessionexpired' => 'Session expired. Please try searching again.',
		'mls_no_properties_found' => 'No properties found.',
		'mls_propertdetail_invalid_found' => 'Invalid property information.',
		'mls_propertdetail_not_found' => 'Property not found.',
		'mls_propertdetail_no_features' => 'No Features Available.',
		'mls_propertdetail_no_amenities' => 'No amenities available',
		'mls_propertdetail_form_name' => 'Please enter your name.',
		'mls_propertdetail_form_email' => 'Please enter your email.',
			'mls_propertdetail_form_valid_email' => 'Please enter a valid email.',
			'mls_propertdetail_form_phone_number' => 'Please enter your phone number.',
			'mls_propertdetail_form_valid_phone_number' => 'Please enter a valid phone number.',
			'mls_propertdetail_form_scheduledate' => 'Please select a schedule date.',
			'mls_propertdetail_form_submitting' => 'Submitting...',
			'mls_propertdetail_form_submitrequest' => 'Submit Request',
			'mls_propertdetail_form_submiterror' => 'There was an issue submitting the form. Please try again.',
			'mls_propertdetail_form_submitsuccess' => 'Thank you! Your request has been submitted, and an email has been sent.',
			'mls_propertdetail_form_submitrequiredmissing' => 'Required fields are missing.',
    	],
		'general' => [
        'in' => 'in',
		'month' => 'Month',
		'share' => 'Share this:',
		'features' => 'Features',
		'description' => 'Description',
    	],
		'specification' => [
        'bedrooms' => 'Bedrooms',
		'bathrooms' => 'Bathrooms',
		'built' => 'Built Area',
		'garden' => 'Garden Plot',
		'terrace' => 'Terrace',
    	],
		'prp_highlights' => [
        'property_highlights' => 'Property Highlights',
		'referenceid' => 'Reference ID',
		'price' => 'Price',
		'location' => 'Location',
		'area' => 'Area',
		'country' => 'Country',
		'property_type' => 'Property type',
		'garbage_fees' => 'Garbage fees/year',
		'ibi_fees' => 'IBI Fees',
    	],
		'prp_map' => [
        'property_location' => 'Property Location (Approximate)',
		'property_location_descp' => 'For privacy reasons, the map displays an approximate location of the property. Please contact us for precise details or to arrange a viewing.',
		
    	],
			
			
    ],
	'es' => [
    'labels' => [
        'searchtitle' => '¡Descubre tu casa ideal hoy!',
        'area' => 'Área:',
        'no_area' => 'No hay ubicaciones disponibles',
        'property_type' => 'Tipo de Propiedad:',
        'no_property_type' => 'No hay tipos de propiedad disponibles',
        'reference_id' => 'ID de Referencia:',
        'beds' => 'Habitaciones:',
        'baths' => 'Baños:',
        'min_price' => 'Precio Mínimo:',
        'max_price' => 'Precio Máximo:',
        'sort_by' => 'Ordenar por:',
        'for_rent' => 'En Alquiler',
        'for_sale' => 'En Venta',
        'featured' => 'Destacado',
        'tour_type' => 'Tipo de Tour:',
        'tour_type_desc' => '"Si no puedes visitar la propiedad en persona, podemos visitarla por ti y conectarnos por video de WhatsApp para mostrarte un tour."',
    ],
    'placeholders' => [
        'search_reference_id' => 'Buscar por ID de Referencia',
        'search_area' => 'Seleccionar ubicación',
        'search_property_type' => 'Seleccionar tipos de propiedad',
        'your_phone' => 'Tu Teléfono',
        'your_email' => 'Tu Correo Electrónico',
        'your_name' => 'Tu Nombre',
    ],
    'buttons' => [
        'search_properties' => 'Buscar Propiedades',
        'submit_request' => 'Enviar Solicitud',
        'view_more' => 'Ver Más',
        'back_previous' => 'Volver a la página anterior',
        'back_home' => 'Volver a Inicio',
        'virtual_tour' => 'Tour Virtual',
        'video_tour' => 'Tour en Video',
        'book_viewing' => 'Reservar Visita',
    ],
    'options' => [
        'any' => 'Cualquiera',
        'sort_lowest_price' => 'Precio (Menor Primero)',
        'sort_highest_price' => 'Precio (Mayor Primero)',
        'sort_location' => 'Ubicación',
        'sort_most_recent' => 'Más Reciente Primero',
        'sort_oldest' => 'Más Antiguo Primero',
    ],
    'error' => [
        'prpreferr' => 'Agrega el atributo de referencia y el ID de propiedad para mostrar las propiedades',
        'mls_nosearchresult' => 'No se encontraron resultados. Por favor, intenta buscar de nuevo.',
        'mls_sessionexpired' => 'La sesión ha expirado. Por favor, intenta buscar de nuevo.',
        'mls_no_properties_found' => 'No se encontraron propiedades.',
        'mls_propertdetail_invalid_found' => 'Información de propiedad no válida.',
        'mls_propertdetail_not_found' => 'Propiedad no encontrada.',
        'mls_propertdetail_no_features' => 'No hay características disponibles.',
        'mls_propertdetail_no_amenities' => 'No hay servicios disponibles.',
    ],
    'general' => [
        'in' => 'en',
        'month' => 'Mes',
        'share' => 'Compartir:',
        'features' => 'Características',
        'description' => 'Descripción',
    ],
    'specification' => [
        'bedrooms' => 'Habitaciones',
        'bathrooms' => 'Baños',
        'built' => 'Área Construida',
        'garden' => 'Parcela de Jardín',
        'terrace' => 'Terraza',
    ],
    'prp_highlights' => [
        'property_highlights' => 'Destacados de la Propiedad',
        'referenceid' => 'ID de Referencia',
        'price' => 'Precio',
        'location' => 'Ubicación',
        'area' => 'Área',
        'country' => 'País',
        'property_type' => 'Tipo de Propiedad',
        'garbage_fees' => 'Tasas de Basura/Año',
        'ibi_fees' => 'IBI',
    ],
    'prp_map' => [
        'property_location' => 'Ubicación Aproximada de la Propiedad',
        'property_location_descp' => 'Por razones de privacidad, el mapa muestra una ubicación aproximada de la propiedad. Contáctanos para detalles precisos o para organizar una visita.',
        'price' => 'Precio',
        'location' => 'Ubicación',
        'area' => 'Área',
        'country' => 'País',
        'property_type' => 'Tipo de Propiedad',
        'garbage_fees' => 'Tasas de Basura/Año',
        'ibi_fees' => 'IBI',
    ],
]


];
}

/*function mls_plugin_translate($key) {
    $current_language = mls_plugin_get_current_language();
    $translations = mls_plugin_get_translations();

    if (isset($translations[$current_language][$key])) {
        return $translations[$current_language][$key];
    }

    // Fallback to English if key not found in selected language
    return $translations['en'][$key] ?? $key;
}*/

function mls_plugin_translate($key, $nested_key) {
    $current_language = mls_plugin_get_current_language();
    $translations = mls_plugin_get_translations();

    // Check if nested key exists in the current language
    if (isset($translations[$current_language][$key][$nested_key])) {
        return $translations[$current_language][$key][$nested_key];
    }

    // Fallback to English if nested key doesn't exist in the current language
    return $translations['en'][$key][$nested_key] ?? '';
}