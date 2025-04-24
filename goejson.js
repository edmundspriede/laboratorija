<script>
  (g=>{var h,a,k,p="The Google Maps JavaScript API",c="google",l="importLibrary",q="__ib__",m=document,b=window;b=b[c]||(b[c]={});var d=b.maps||(b.maps={}),r=new Set,e=new URLSearchParams,u=()=>h||(h=new Promise(async(f,n)=>{await (a=m.createElement("script"));e.set("libraries",[...r]+"");for(k in g)e.set(k.replace(/[A-Z]/g,t=>"_"+t[0].toLowerCase()),g[k]);e.set("callback",c+".maps."+q);a.src=`https://maps.${c}apis.com/maps/api/js?`+e;d[q]=f;a.onerror=()=>h=n(Error(p+" could not load."));a.nonce=m.querySelector("script[nonce]")?.nonce||"";m.head.append(a)}));d[l]?console.warn(p+" only loads once. Ignoring:",g):d[l]=(f,...n)=>r.add(f)&&u().then(()=>d[l](f,...n))})({
    key: "AIzaSyAmTuMHRxHgA89saUEQG0Lfqn3CBzGRSoY",
    v: "weekly",
    // Use the 'v' parameter to indicate the version to use (weekly, beta, alpha, etc.).
    // Add other bootstrap parameters as needed, using camel case.
  });
</script>

<script>

jQuery( document ).ready( function($) {  

	async function initMap() {
		
		const { Map } = await google.maps.importLibrary("maps");
	
		var map = new Map(document.getElementById("map"), {
			zoom: 15,
			center: { lat: 0, lng: 0 }, // Adjust center as needed
			mapTypeId: "terrain",
		});

    
	  map.data.loadGeoJson("https://n8n.m50.lv:5678/webhook/geojson-darbs?post_id="+darbiId, {}, function (features) {
          if (features.length > 0) {
                // Get the center of the first polygon
                var center = getPolygonCenter(features[0]);
                    
                // Center the map to the polygon's center
                map.setCenter(center);
					
		  					const buttonContainer = document.getElementById("laukibuttons");  

					  		features.forEach((feature, index) => {
							    		
			
									index++; // Increment index by 1
									let button_ = `button-${index}`;
									const button = document.getElementById(button_);
									button.onmouseover = () => {
											map.data.overrideStyle(feature, { fillColor: "#00FF00" });
									};
									button.onmouseout = () => {
										map.data.revertStyle(feature);
									};
					  		  feature.button_ =    button_;
					
									});
					};	
     }); 

        // Set the default polygon styles
        map.data.setStyle({
                fillColor: 'blue',
                strokeColor: 'black',
                strokeWeight: 2
            });

            // Add event listeners for hover effect
        map.data.addListener('mouseover', function (event) {
                map.data.overrideStyle(event.feature, {
                    fillColor: 'red',  // Highlight color
                    strokeWeight: 3
                });
					
					      //let buttonr_ = document.getElementById(event.feature.button_ );
				      	document.getElementById(event.feature.button_).style.setProperty("background-color", "#8ac744");

				      			
					       
            });

        map.data.addListener('mouseout', function (event) {
                map.data.revertStyle(event.feature);  // Reset to default
					    	document.getElementById(event.feature.button_).style.setProperty("background-color", "#2588F3"); 
					     
            });
 
    }  //init map
	
	function getPolygonCenter(feature) {
            var bounds = new google.maps.LatLngBounds();

            feature.getGeometry().forEachLatLng(function (latlng) {
                bounds.extend(latlng);
            });

            return bounds.getCenter();
    }

	initMap()
	
});

</script>



