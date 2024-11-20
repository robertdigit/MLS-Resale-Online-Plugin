<?php

// Add submenu for documentation.
function mls_plugin_add_documentation_submenu() {
    add_submenu_page(
        'mls_plugin_settings',    // Parent slug (the main menu slug)
        'MLS Plugin Documentation', // Page title
        'Documentation',          // Menu title
        'manage_options',         // Capability required
        'mls_plugin_documentation', // Menu slug for the submenu
        'mls_plugin_documentation_page' // Function to display the page content
    );
}

add_action('admin_menu', 'mls_plugin_add_documentation_submenu');


function mls_plugin_documentation_page() {
    ?>
    <div class="wrap">
		<h1>MLS Plugin Documentation</h1>
        <h2>Available Shortcodes</h2>
        <p class="description">Below are the shortcodes you can use in the MLS Plugin:</p>
<!--    1st shortcode - search form     -->
        <h2>1. Shortcode: <code>[mls_property_search]</code></h2>
        <p>This shortcode allows you to display a property search form on any page of your site. You can customize the form using the attributes provided below.</p>
       <div class="mls-table-reponsive">
        <table class="widefat fixed striped" cellspacing="0">
            <thead>
                <tr>
                    <th>Attribute</th>
                    <th>Description</th>
                    <th>Default Value</th>
                    <th>Example Usage</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><code>filtertype</code></td>
                    <td>Defines the type of filter for the search. Common values are <code>sales,long_rentals, short_rentals, featured </code>.</td>
                    <td><code>sales</code></td>
                    <td><code>[mls_property_search filtertype="sales"]</code></td>
                </tr>
                <tr>
                    <td><code>ownpageresult</code></td>
                    <td>Determines whether search results are displayed on a separate page. Use <code>true</code> or <code>false</code>.</td>
                    <td><code>false</code></td>
                    <td><code>[mls_property_search ownpageresult="true"]</code></td>
                </tr>
                <tr>
                    <td><code>searchtitle</code></td>
                    <td>The title text displayed at the top of the search form.</td>
                    <td><code>Discover your ideal home today!</code></td>
                    <td><code>[mls_property_search searchtitle="Find Your Dream Home"]</code></td>
                </tr>
                <tr>
                    <td><code>maxthumbnail</code></td>
                    <td>Specifies the maximum number of property thumbnails to display.</td>
                    <td><code>''</code> (empty)</td>
                    <td><code>[mls_property_search maxthumbnail="5"]</code></td>
                </tr>
                <tr>
                    <td><code>bedsfilter</code></td>
                    <td>Sets the maximum number of beds that can be selected in the filter.</td>
                    <td><code>5</code></td>
                    <td><code>[mls_property_search bedsfilter="3"]</code></td>
                </tr>
                <tr>
                    <td><code>bathsfilter</code></td>
                    <td>Sets the maximum number of baths that can be selected in the filter.</td>
                    <td><code>5</code></td>
                    <td><code>[mls_property_search bathsfilter="2"]</code></td>
                </tr>
                <tr>
                    <td><code>min_pricefilter</code></td>
                    <td>Defines a comma-separated list of minimum price values available in the filter.</td>
                    <td><code>100000,250000</code></td>
                    <td><code>[mls_property_search min_pricefilter="150000,300000"]</code></td>
                </tr>
                <tr>
                    <td><code>max_pricefilter</code></td>
                    <td>Defines a comma-separated list of maximum price values available in the filter.</td>
                    <td><code>250000,1000000</code></td>
                    <td><code>[mls_property_search max_pricefilter="500000,2000000"]</code></td>
                </tr>
                <tr>
                    <td><code>includesorttype</code></td>
                    <td>Enables the inclusion of sorting options in the search form.</td>
                    <td><code>1</code> (enabled)</td>
                    <td><code>[mls_property_search includesorttype="0"]</code></td>
                </tr>
				<tr>
                    <td><code>formbackgroundcolor</code></td>
                    <td>Set specific color to Submit Button in the search form. You can enter in words or HEX value. Eg: <code>formbuttoncolor="red" , formbuttoncolor="#f7f7f7" </code></td>
                    <td><code>''</code> (empty)</td>
                    <td><code>[mls_property_search formbackgroundcolor="#f7f7f7"]</code></td>
                </tr>
				<tr>
                    <td><code>formbuttoncolor</code></td>
                    <td>Set specific color to Submit Button in the search form. You can enter in words or HEX value. Eg: <code>formbuttoncolor="red" , formbuttoncolor="#0073e1" </code></td>
                    <td><code>''</code> (empty)</td>
                    <td><code>[mls_property_search formbuttoncolor="red"]</code></td>
                </tr>
                <tr>
                    <td><code>p_sorttype</code></td>
                    <td>Pre-defines a sorting type for the properties (e.g., by price or by date). Values are <code>0 => Order By price (ascending), 1 => Order By price (descending), 2 => Order By location,
3 => Order By last updated date (most recent first), 4 => Order By last updated date (oldest first)</code></td>
                    <td><code>''</code> (empty)</td>
                    <td><code>[mls_property_search p_sorttype="0"]</code></td>
                </tr>
            </tbody>
        </table>
        </div>

        <h3>Example Usage</h3>
        <p>To display the search form for properties available for sales with a custom title and sorting options:</p>
        <pre><code>[mls_property_search filtertype="sales" searchtitle="Find Your Dream Home" maxthumbnail="5" includesorttype="1"]</code></pre>

        <h3>Notes</h3>
        <ul>
            <li>If no attributes are specified, the shortcode will use default values.</li>
            <li>The <code>filtertype</code> attribute determines the context of the search (e.g., sales, rentals).</li>
            <li>The form uses dynamic location and property type options fetched from the plugin settings.</li>
        </ul>
<!-- 	2nd shortcode - property listing	 -->
		<h2>2. Shortcode: <code>[mls_property_list]</code></h2>
        <p>This shortcode allows you to display a property list on any page of your site. You can also include a property search form as part of the listing by using the <code>includesearch</code> attribute. The table below explains each attribute.</p>
<div class="mls-table-reponsive">
        <table class="widefat fixed striped" cellspacing="0">
            <thead>
                <tr>
                    <th>Attribute</th>
                    <th>Description</th>
                    <th>Default Value</th>
                    <th>Example Usage</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><code>includesearch</code></td>
                    <td>Determines whether the search form should be included above the property list. Use <code>true</code> to show it.</td>
                    <td><code>true</code></td>
                    <td><code>[mls_property_list includesearch="false"]</code></td>
                </tr>
                <tr>
                    <td><code>filtertype</code></td>
                    <td>Defines the type of filter for the property list. Common values are <code>sales,long_rentals, short_rentals, featured </code>.</td>
                    <td><code>sales</code></td>
                    <td><code>[mls_property_list filtertype="sales"]</code></td>
                </tr>
                <tr>
                    <td><code>ownpageresult</code></td>
                    <td>Determines whether search results are displayed on a separate page. Use <code>true</code> or <code>false</code>.</td>
                    <td><code>true</code></td>
                    <td><code>[mls_property_list ownpageresult="false"]</code></td>
                </tr>
                <tr>
                    <td><code>searchtitle</code></td>
                    <td>The title text displayed at the top of the search form, if included.</td>
                    <td><code>Discover your ideal home today!</code></td>
                    <td><code>[mls_property_list searchtitle="Find Your Dream Home"]</code></td>
                </tr>
                <tr>
                    <td><code>maxthumbnail</code></td>
                    <td>Specifies the maximum number of property thumbnails to display in the property list.</td>
                    <td><code>''</code> (empty)</td>
                    <td><code>[mls_property_list maxthumbnail="5"]</code></td>
                </tr>
                <tr>
                    <td><code>bedsfilter</code></td>
                    <td>Sets the maximum number of bedrooms in the filter for the property search form.</td>
                    <td><code>5</code></td>
                    <td><code>[mls_property_list bedsfilter="3"]</code></td>
                </tr>
                <tr>
                    <td><code>bathsfilter</code></td>
                    <td>Sets the maximum number of bathrooms in the filter for the property search form.</td>
                    <td><code>5</code></td>
                    <td><code>[mls_property_list bathsfilter="2"]</code></td>
                </tr>
                <tr>
                    <td><code>min_pricefilter</code></td>
                    <td>Defines a comma-separated list of minimum price values available in the search filter.</td>
                    <td><code>100000,250000</code></td>
                    <td><code>[mls_property_list min_pricefilter="150000,300000"]</code></td>
                </tr>
                <tr>
                    <td><code>max_pricefilter</code></td>
                    <td>Defines a comma-separated list of maximum price values available in the search filter.</td>
                    <td><code>250000,1000000</code></td>
                    <td><code>[mls_property_list max_pricefilter="500000,2000000"]</code></td>
                </tr>
                <tr>
                    <td><code>includesorttype</code></td>
                    <td>Enables the inclusion of sorting options in the search form. Use <code>1</code> to include it.</td>
                    <td><code>1</code></td>
                    <td><code>[mls_property_list includesorttype="0"]</code></td>
                </tr>
				<tr>
                    <td><code>formbackgroundcolor</code></td>
                    <td>Set specific color to Submit Button in the search form. You can enter in words or HEX value. Eg: <code>formbuttoncolor="red" , formbuttoncolor="#f7f7f7" </code></td>
                    <td><code>''</code> (empty)</td>
                    <td><code>[mls_property_list formbackgroundcolor="#f7f7f7"]</code></td>
                </tr>
				<tr>
                    <td><code>formbuttoncolor</code></td>
                    <td>Set specific color to Submit Button in the search form. You can enter in words or HEX value. Eg: <code>formbuttoncolor="red" , formbuttoncolor="#0073e1" </code></td>
                    <td><code>''</code> (empty)</td>
                    <td><code>[mls_property_list formbuttoncolor="red"]</code></td>
                </tr>
                <tr>
                    <td><code>p_sorttype</code></td>
                    <td>Pre-defines a sorting type for the properties (e.g., by price or by date). Values are <code>0 => Order By price (ascending), 1 => Order By price (descending), 2 => Order By location,
3 => Order By last updated date (most recent first), 4 => Order By last updated date (oldest first)</code></td>
                    <td><code>''</code> (empty)</td>
                    <td><code>[mls_property_search p_sorttype="0"]</code></td>
                </tr>
            </tbody>
        </table>
</div>
        <h3>Example Usage</h3>
        <p>To display a property list with an integrated search form for sales properties and custom filters:</p>
        <pre><code>[mls_property_list includesearch="true" filtertype="sales" searchtitle="Find Your Dream Home" maxthumbnail="5" bedsfilter="4" bathsfilter="2" includesorttype="1"]</code></pre>

        <h3>Notes</h3>
        <ul>
            <li>The <code>includesearch</code> attribute allows you to include a search form above the property list.</li>
            <li>If no attributes are provided, default values will be used for the shortcode.</li>
            <li>The <code>filtertype</code> attribute determines the context of the property search, whether for sales or rentals.</li>
        </ul>
		
<!-- 	3rd shortcode - Search Results	 -->
		<h2>3. Shortcode: <code>[mls_search_results]</code></h2>
<p>This shortcode is used to display the results from the property search form. It will only show results if a search has been performed and works in conjunction with the <code>[mls_property_search]</code> shortcode.</p>
<div class="mls-table-reponsive">
    <table class="widefat fixed striped" cellspacing="0">
        <thead>
            <tr>
                <th>Attribute / Parameter</th>
                <th>Description</th>
                <th>Default Value</th>
                <th>Example</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><code>includesearch</code> (attribute)</td>
                <td>Optional. If set to <code>true</code>, includes the search form above the search results.</td>
                <td><code>true</code></td>
                <td><code>[mls_search_results includesearch="false"]</code></td>
            </tr>
            <tr>
                    <td><code>filtertype</code></td>
                    <td>Defines the type of filter for the property list. Common values are <code>sales,long_rentals, short_rentals, featured </code>.</td>
                    <td><code>sales</code></td>
                    <td><code>[mls_property_list filtertype="sales"]</code></td>
            </tr>
            <tr>
                <td><code>searchtitle</code> (attribute)</td>
                <td>Sets a title for the search section displayed above the results.</td>
                <td><code>Discover your ideal home today!</code></td>
                <td><code>[mls_search_results searchtitle="Find your dream property!"]</code></td>
            </tr>
            <tr>
                <td><code>maxthumbnail</code> (attribute)</td>
                <td>Sets the maximum number of thumbnails to display per listing.</td>
                <td><code>''</code></td>
                <td><code>[mls_search_results maxthumbnail="5"]</code></td>
            </tr>
            <tr>
                <td><code>bedsfilter</code> (attribute)</td>
                <td>Filters results to show properties with up to this many bedrooms.</td>
                <td><code>5</code></td>
                <td><code>[mls_search_results bedsfilter="3"]</code></td>
            </tr>
            <tr>
                <td><code>bathsfilter</code> (attribute)</td>
                <td>Filters results to show properties with up to this many bathrooms.</td>
                <td><code>5</code></td>
                <td><code>[mls_search_results bathsfilter="2"]</code></td>
            </tr>
            <tr>
                <td><code>min_pricefilter</code> (attribute)</td>
                <td>Sets the minimum price range for property listings.</td>
                <td><code>100000,250000</code></td>
                <td><code>[mls_search_results min_pricefilter="50000,150000"]</code></td>
            </tr>
            <tr>
                <td><code>max_pricefilter</code> (attribute)</td>
                <td>Sets the maximum price range for property listings.</td>
                <td><code>250000,1000000</code></td>
                <td><code>[mls_search_results max_pricefilter="500000,2000000"]</code></td>
            </tr>
            <tr>
                <td><code>includesorttype</code> (attribute)</td>
                <td>Determines if sorting options should be displayed with results.</td>
                <td><code>1</code></td>
                <td><code>[mls_search_results includesorttype="0"]</code></td>
            </tr>
            <tr>
                    <td><code>formbackgroundcolor</code></td>
                    <td>Set specific color to Submit Button in the search form. You can enter in words or HEX value. Eg: <code>formbuttoncolor="red" , formbuttoncolor="#f7f7f7" </code></td>
                    <td><code>''</code> (empty)</td>
                    <td><code>[mls_property_list formbackgroundcolor="#f7f7f7"]</code></td>
                </tr>
				<tr>
                    <td><code>formbuttoncolor</code></td>
                    <td>Set specific color to Submit Button in the search form. You can enter in words or HEX value. Eg: <code>formbuttoncolor="red" , formbuttoncolor="#0073e1" </code></td>
                    <td><code>''</code> (empty)</td>
                    <td><code>[mls_property_list formbuttoncolor="red"]</code></td>
                </tr>
                <tr>
                    <td><code>p_sorttype</code></td>
                    <td>Pre-defines a sorting type for the properties (e.g., by price or by date). Values are <code>0 => Order By price (ascending), 1 => Order By price (descending), 2 => Order By location,
3 => Order By last updated date (most recent first), 4 => Order By last updated date (oldest first)</code></td>
                    <td><code>''</code> (empty)</td>
                    <td><code>[mls_property_search p_sorttype="0"]</code></td>
                </tr>
            <tr>
                <td><code>mls_search_performed</code> (URL parameter)</td>
                <td>Must be set to <code>1</code> in the URL to indicate a search was performed.</td>
                <td>-</td>
                <td><code>site.com/page?mls_search_performed=1</code></td>
            </tr>
            <tr>
                <td><code>query_id</code> (URL parameter)</td>
                <td>Unique query ID used to fetch the correct set of properties.</td>
                <td>-</td>
                <td><code>site.com/page?query_id=12345</code></td>
            </tr>
            <tr>
                <td><code>page_num</code> (URL parameter)</td>
                <td>The page number for paginated results.</td>
                <td>-</td>
                <td><code>site.com/page?page_num=2</code></td>
            </tr>
            <tr>
                <td><code>p_sorttype</code> (URL parameter)</td>
                <td>The sorting type applied to the search results.</td>
                <td>-</td>
                <td><code>site.com/page?p_sorttype=0</code></td>
            </tr>
        </tbody>
    </table>
</div>

<h3>Example Usage</h3>
<p>This shortcode works in conjunction with a search form shortcode. After performing a search, the results can be displayed using:</p>
<pre><code>[mls_search_results]</code></pre>

<p>Ensure that your URL contains the necessary parameters for this shortcode to work, such as <code>mls_search_performed=1</code>, <code>query_id</code>, and other filters passed from the search form.</p>

<h3>Notes</h3>
<ul>
    <li>This shortcode will only display results if the <code>mls_search_performed</code> parameter is set in the URL.</li>
    <li>The search form must store the relevant filter values in the session or pass them via the URL.</li>
    <li>The parameters such as <code>query_id</code>, <code>page_num</code>, and <code>filter_type</code> are automatically handled by the search form submission.</li>
    <li>If no search has been performed, the shortcode will return an empty result.</li>
</ul>


<!-- 	4th shortcode - Property By reference id	 -->
		 <h2>4. Shortcode: <code>[mls_property_byrefs]</code></h2>
        <p>This shortcode is used to display properties by their reference IDs. The shortcode allows you to specify property references to fetch and display specific properties. If no reference IDs are provided, it will return a message prompting the user to add references.</p>
<div class="mls-table-reponsive">
        <table class="widefat fixed striped" cellspacing="0">
            <thead>
                <tr>
                    <th>Attribute</th>
                    <th>Description</th>
                    <th>Default Value</th>
                    <th>Example Usage</th>
                </tr>
            </thead>
            <tbody>
                
				<tr>
                    <td><code>filtertype</code></td>
                    <td>Defines the type of filter for the property list. Common values are <code>sales,long_rentals, short_rentals, featured </code>.</td>
                    <td><code>sales</code></td>
                    <td><code>[mls_property_byrefs filtertype="sales"]</code></td>
                </tr>
				<tr>
                    <td><code>maxthumbnail</code></td>
                    <td>Specifies the maximum number of property thumbnails to display in the results.</td>
                    <td><code>''</code> (empty)</td>
                    <td><code>[mls_property_byrefs maxthumbnail="5"]</code></td>
                </tr>
                <tr>
                    <td><code>references</code></td>
                    <td>A comma-separated list of property reference IDs that you want to display. This attribute is mandatory to fetch properties.</td>
                    <td><code>''</code> (empty)</td>
                    <td><code>[mls_property_byrefs references="R4586365,R1234567"]</code></td>
                </tr>
            </tbody>
        </table>
</div>
        <h3>Example Usage</h3>
        <p>To display specific properties by their reference IDs, use the following shortcode:</p>
        <pre><code>[mls_property_byrefs references="R4586365,R1234567" filtertype="sales" maxthumbnail="5"]</code></pre>

        <h3>Notes</h3>
        <ul>
            <li>The <code>references</code> attribute is required. If it's not provided, the shortcode will display a message asking for property references.</li>
            <li>You can use the <code>filtertype</code> attribute to control whether the properties shown are for sales or rentals.</li>
            <li>The <code>maxthumbnail</code> attribute controls the number of the property thumbnails. It can be left empty to use the default numbers or set to values like <code>4</code>, <code>6</code>, etc.</li>
            <li>If no properties match the provided reference IDs, a message indicating no properties were found will be displayed.</li>
        </ul>
		
<!--    5th shortcode - Banner Search Form    -->
<h2>5. Shortcode: <code>[mls_banner_searchform]</code></h2>
<p>This shortcode allows you to display a property banner search form with customizable options. You can modify the form using the attributes provided below. You can change the Form background grey color and button color in plugin setting page</p>
<div class="mls-table-reponsive">
    <table class="widefat fixed striped" cellspacing="0">
        <thead>
            <tr>
                <th>Attribute</th>
                <th>Description</th>
                <th>Default Value</th>
                <th>Example Usage</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><code>filtertype</code></td>
                <td>Defines the type of filter for the search. Common values are <code>sales, long_rentals, short_rentals, featured</code>.</td>
                <td><code>sales</code></td>
                <td><code>[mls_banner_searchform filtertype="sales"]</code></td>
            </tr>
            <tr>
                <td><code>searchtitle</code></td>
                <td>The title text displayed at the top of the search form.</td>
                <td><code>Discover your ideal home today!</code></td>
                <td><code>[mls_banner_searchform searchtitle="Find Your Dream Rental!"]</code></td>
            </tr>
            <tr>
                <td><code>maxthumbnail</code></td>
                <td>Specifies the maximum number of property thumbnails to display.</td>
                <td><code>''</code> (empty)</td>
                <td><code>[mls_banner_searchform maxthumbnail="5"]</code></td>
            </tr>
            <tr>
                <td><code>bedsfilter</code></td>
                <td>Sets the maximum number of beds that can be selected in the filter.</td>
                <td><code>5</code></td>
                <td><code>[mls_banner_searchform bedsfilter="3"]</code></td>
            </tr>
            <tr>
                <td><code>bathsfilter</code></td>
                <td>Sets the maximum number of baths that can be selected in the filter.</td>
                <td><code>5</code></td>
                <td><code>[mls_banner_searchform bathsfilter="2"]</code></td>
            </tr>
            <tr>
                <td><code>min_pricefilter</code></td>
                <td>Defines a comma-separated list of minimum price values available in the filter.</td>
                <td><code>100000,250000</code></td>
                <td><code>[mls_banner_searchform min_pricefilter="150000,300000"]</code></td>
            </tr>
            <tr>
                <td><code>max_pricefilter</code></td>
                <td>Defines a comma-separated list of maximum price values available in the filter.</td>
                <td><code>250000,1000000</code></td>
                <td><code>[mls_banner_searchform max_pricefilter="500000,2000000"]</code></td>
            </tr>
        </tbody>
    </table>
</div>

<h3>Example Usage</h3>
<p>To display the banner search form for properties available for rent with a custom title and thumbnail limit:</p>
<pre><code>[mls_banner_searchform filtertype="long_rentals" searchtitle="Find Your Dream Rental!" maxthumbnail="5"]</code></pre>

<h3>Notes</h3>
<ul>
    <li>If no attributes are specified, the shortcode will use default values.</li>
    <li>The <code>filtertype</code> attribute determines the context of the search (e.g., sales, rentals, featured properties).</li>
    <li>The form uses dynamic location and property type options fetched from the plugin settings.</li>
	<li>The form result will show in the Search result page.</li>
</ul>

		
    </div>
    <?php
}