<img src = "icons/dont_Trip.png"> </img>
# Dont Trip
<p>An itinerary planner utilizing the Google Maps API to give you customized places along a route!</p>

<p>Written primarily in PHP7, MySQL, Vanilla Javascript, J-Query 3.6.0, and Bootstrap 4.5.2/Vanilla CSS3.</p>  

<p><a href="https://donttrip.technologists.cloud/donttrip/"> Visit us</a> today at and let us know of any issues you may face!<p>

<h3>Features:</h3>
<ul>
	<p>Client Side/UI/UX/Frontend</p>
	<li>Find places along a route and add as many as you want along the way.</li>
	<li>Fully featured automatic or manual light/dark mode based upon time of day/user setting.</li>
	<li>Regex for sorting places response list by distance or name.</li>
	<li>Place info page with place name, distance, image, open/closed status/hours, website, address, and phone number.</li>
	<li>Search history/clear search history options (both in settings and per history item).</li>
	<li>Popular places in your state with geolocation REST API.</li>
	<li>Place/keyword autofill reccomendations.</li>
	<li>Responsive directions with click events.</li>
	<li>Popular in your state, search history/keywords, and places along the route all feature hyperlinks to easily use them as search queries.</p>
	<p>Security/Backend</p>
	<li>Hashed/salted passwords.</li>
	<li>Two factor authentication that works with any authenticator app of choice.</li>
	<li>Account deletion with confirm password.</li>
	<li>Confirm email verification/forgot password recovery email form with material design email templates.</li>
	<li>Expiring password reset forms with custom hashed tokens.</li>
	<li>Realtime form (password/username/email/other) input strength/requirement meters.</li>
	<li>SQL injection proof, prepare/parameterized statments utilized for any query in entire backend.</li>
	<li>Brute force/XSRF/CSRF protection/IDS setup to keep a log of visits and login attempts.</li>
	<li>Rate limiting on all actions, including email reset forms.</li>
	<li>Inbound/Outbound traffic protection setup with AWS firewall/UFW with recovery/redeploy scripts on Ubuntu server (repository for these scripts coming soon).</li>
	<li>RESTful requests/responses used for backend.</li>
	<li>Google Auth login integration.</li>
	<li>.htaccess file setup to redirect HTTP(Port 80) to HTTPS(Port 443), as well as hiding .php file extensions in URL.</li>
	<li>PHP-Dot-Env used to safely store API keys, database credentials, email server information, etc.</li>
</ul>