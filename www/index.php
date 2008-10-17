
<!-- This is the project specific website template -->
<!-- It can be changed as liked or replaced by other content -->

<?php

$domain=ereg_replace('[^\.]*\.(.*)$','\1',$_SERVER['HTTP_HOST']);
$group_name=ereg_replace('([^\.]*)\..*$','\1',$_SERVER['HTTP_HOST']);
$themeroot='http://r-forge.r-project.org/themes/rforge/';

echo '<?xml version="1.0" encoding="UTF-8"?>';
?>
<!DOCTYPE html
	PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en   ">

  <head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title><?php echo $group_name; ?></title>
	<link href="<?php echo $themeroot; ?>styles/estilo1.css" rel="stylesheet" type="text/css" />
	<link href="style.css" rel="stylesheet" type="text/css" />
  </head>

<body>

<! --- R-Forge Logo --- >
<table border="0" width="100%" cellspacing="0" cellpadding="0">
<tr><td>
<a href="/"><img src="<?php echo $themeroot; ?>/images/logo.png" border="0" alt="R-Forge Logo" /> </a> </td> </tr>
</table>


<!-- get project title  -->
<!-- own website starts here, the following may be changed as you like -->

<?php if ($handle=fopen('http://'.$domain.'/export/projtitl.php?group_name='.$group_name,'r')){
$contents = '';
while (!feof($handle)) {
	$contents .= fread($handle, 8192);
}
fclose($handle);
echo $contents; } ?>

<!-- end of project description -->

<h2>Outline</h2>
<p> 
With the geonames package you can get easy access to the geographic names query service on <a href="http://www.geonames.org">GeoNames</a>.
</p>

<h2>Requirements</h2>
<p>
All you need is a modern R and the <code>rjson</code> package, which is available from CRAN. You also 
need a network connection, and you need to make sure R can access web sites. Local network policy
and firewalls may affect this.
</p>

<h2>Usage</h2>
<p>
Functions defined here closely follow the names and parameters of WebServices defined in 
<a href="http://www.geonames.org/export/ws-overview.html">GeoNames' WebServices Overview</a>. 
The aim is to implement all JSON-enabled web services defined there.
</p>
<p>
Select the web service you want to call, and check its documentation. To translate into an
R geonames package function, just prefix 'GN' to the name, remove the 'JSON' part, and put named parameters in the 
function call. 
</p>
<p>
For example, let's find the top-level administrative areas of Italy. First you need
the geoname id code for Italy. The <a href="http://www.geonames.org/export/ws-overview.html">Web Service Overview</a>
gives us the <code>countryInfo</code> service. The description of that service is given as:

<pre class="geonames">
Country Info (Bounding Box, Capital, Area in square km, Population)
Webservice Type : REST
Url : ws.geonames.org/countryInfo?
Parameters : country (default = all countries)
lang : ISO-639-1 language code (en,de,fr,it,es,...) (default = english)
Result : Country information : Capital, Population, Area in square km, Bounding Box of mainland
Example : http://ws.geonames.org/countryInfo?
</pre> 

<p>
We can then call this from R with <code>GNcountryInfo(country="IT")</code>. Note that the function name has <code>GN</code>
prefixed to the web service name, and that the parameter is named the same as the parameter description above.
<pre class="r">
> GNcountryInfo(country="IT")
  countryName bBoxWest currencyCode fipsCode countryCode isoNumeric capital areaInSqKm            languages
1       Italy 6.614888          EUR       IT          IT        380    Rome   301230.0 it-IT,de-IT,fr-IT,sl
  bBoxEast isoAlpha3 continent bBoxNorth geonameId bBoxSouth population
1 18.51345       ITA        EU   47.0952   3175395  36.65277   58145000
</pre>
</p>

<p>
This gives us the <code>geonameId</code> of 3175395 for Italy. We want to find the administrative areas, and this
  is done with the <code>children</code> web service:
<pre class="geonames">
Children

Returns the children for a given geonameId. The children are the
administrative divisions within an other administrative division. Like
the counties (ADM2) in a state (ADM1) or also the countries in a
continent.

Webservice Type : XML or JSON
Url : ws.geonames.org/children?
ws.geonames.org/childrenJSON?
Parameters :
geonameId : the geonameId of the parent
Result : returns a list of GeoName records

Example, regions of Italy:
http://ws.geonames.org/children?geonameId=3175395 
</pre>
</p>
<p>
So in R this becomes <code>itadm1=GNchildren(geonameId=3175395)</code>. Now we can investigate the returned value:
<pre class="r">
> itadm1$name
 [1] "Abruzzo"                "Aosta Valley"           "Apulia"                 "Basilicate"            
 [5] "Calabria"               "Campania"               "Friuli"                 "Latium"                
 [9] "Liguria"                "Lombardy"               "Piedmont"               "Regione Emilia-Romagna"
[13] "Regione Molise"         "Sardinia"               "Sicily"                 "The Marches"           
[17] "Trentino-Alto Adige"    "Tuscany"                "Umbria"                 "Veneto"                
> 
</pre>
</p>
<p>
We can also get these names in Italian, by adding <code>lang="IT"</code> to the call. Note that this isn't
documented on the geonames website - I tried it and it worked. 
<pre class="r">
> GNchildren(geonameId=3175396,lang="IT")$name
 [1] "Regione Abruzzo"                        "Regione Autonoma Friuli Venezia Giulia"
 [3] "Regione Autonoma Siciliana"             "Regione Autonoma Trentino-Alto Adige"  
 [5] "Regione Autonoma Valle d'Aosta"         "Regione Autonoma della Sardegna"       
 [7] "Regione Basilicata"                     "Regione Calabria"                      
 [9] "Regione Campania"                       "Regione Emilia-Romagna"                
[11] "Regione Lazio"                          "Regione Liguria"                       
[13] "Regione Lombardia"                      "Regione Marche"                        
[15] "Regione Molise"                         "Regione Piemonte"                      
[17] "Regione Puglia"                         "Regione Umbria"                        
[19] "Regione del Veneto"                     "Tuscany"             
</pre>
</p>


<h3>Examples</h3>
<ul>

<li>Timezone query:
<pre class="r">
> GNtimezone(lat=-34.576,lng=-58.40881)
              time countryName rawOffset dstOffset countryCode gmtOffset
1 2008-10-15 12:39   Argentina        -3        -3          AR        -2
        lng                     timezoneId     lat
1 -58.40881 America/Argentina/Buenos_Aires -34.576
</pre>
</li>
<li>Cities query:
<pre class="r">
> GNcities(north=-44.1,south=-9.9,east=-22,4,west=55.2,lang="de")
                      fcodeName countrycode fcl           fclName         name
1 capital of a political entity          AR   P city, village,... Buenos Aires
2 capital of a political entity          PE   P city, village,...         Lima
3 capital of a political entity          CL   P city, village,...     Santiago
4 capital of a political entity          BR   P city, village,...     Brasília
                                wikipedia       lng fcode geonameId       lat
1      de.wikipedia.org/wiki/Buenos_Aires -58.40881  PPLC   3435910 -34.57613
2              de.wikipedia.org/wiki/Lima -77.05000  PPLC   3936456 -12.05000
3 de.wikipedia.org/wiki/Santiago_de_Chile -70.66667  PPLC   3871336 -33.45000
4     de.wikipedia.org/wiki/Bras%C3%ADlia -47.92972  PPLC   3469058 -15.77972
  population
1   13076300
2    7737002
3    4837295
4    2207718
</pre>
</li>

<li>Weather query:
<pre class="r">
> GNweatherIcao(ICAO="LSZH")
      clouds weatherCondition
1 few clouds              n/a
                                                 observation windDirection ICAO
1 LSZH 151320Z 25004KT 230V290 9999 FEW030 20/13 Q1019 NOSIG           250 LSZH
  elevation countryCode      lng temperature dewPoint windSpeed humidity
1       432          CH 8.516667          20       13        04       64
    stationName            datetime      lat hectoPascAltimeter
1 Zurich-Kloten 2008-10-15 13:20:00 47.46667               1019
</pre>
</li>
</ul>

<h2>Parameter Names</h2>
<p>
Note that for the simplest functions there are defaults and named formal parameters. For more complex
web service calls the function just has a <code>...</code> parameter and the user has to supply a set
of <code>name=value</code> pairs that make sense for the web service. I recommend you always use named
parameters when you call these functions, even when calling something like <code>GNtimezone</code>
which is defined with names so you can at the moment do <code>GNtimezone(54,23)</code>. I might get
rid of this and enforce calls to be of the form <code>GNtimezone(lat=54,lng=23)</code> in the future.
</p>

<h2>Returned Values</h2>
<p>
The functions generally return data frames with a row for each entry and columns named as in the web service specification. Sometimes each returned value in a query has a different set of properties, and in this case the data frame will have all the property names for columns, but NA values in the inappropriate entries.
</p>

<h2>Errors</h2>
<p>
If the web service call fails then an error message is returned and reported. The text for these
messages is coded into the package. If the Geonames people change or extende these codes then you
will probably be better off looking them up on their web site.
</p>


<p> The <strong>project summary page</strong> you can find <a href="http://<?php echo $domain; ?>/projects/<?php echo $group_name; ?>/"><strong>here</strong></a>. </p>

</body>
</html>
