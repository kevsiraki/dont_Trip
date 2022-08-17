<img src = "icons/dont_Trip.png"> </img>
# Dont Trip
<p>An itinerary planner utilizing the Google Maps API to give you customized places along a route!</p>

<p>Written primarily in LEMP Stack (PHP 7.4/Nginx, MariaDB), Modern Vanilla Javascript, J-Query 3.6.0, and Bootstrap 4.5.2/Vanilla CSS3.</p>  

<p><a href="https://donttrip.org/donttrip/"> Visit us</a> today at and let us know of any issues you may face!<p>

<h3>Features:</h3>
<ul>
	<p>Frontend/Client</p>
	<li>Find places along a route and add as many as you want along the way.</li>
	<li>Fully featured automatic or manual light/dark mode based upon time of day/user setting.</li>
	<li>Regex for sorting places response list by distance or name.</li>
	<li>Place info page with place name, distance, image, open/closed status/hours, website, address, and phone number.</li>
	<li>Search history/count/clear search history options (both in settings and per history item).</li>
	<li>Popular places in your state with a popularity count.</li>
	<li>Place/keyword autofill reccomendations.</li>
	<li>Responsive directions with click events.</li>
	<li>Popular in your state, search history/keywords, and places along the route all feature hyperlinks to easily use them as search queries.</p>
	<p>Backend/Server</p>
	<li>Proper SSL Encryption with HTTP->HTTPS traffic redirects.</li>
	<li>Hashed/salted passwords and modern encryption of any secret keys in the DB.</li>
	<li>Two factor authentication that works with any authenticator app of choice.</li>
	<li>Account deletion with confirm password.</li>
	<li>Confirm email verification/forgot password recovery email form with material design email templates.</li>
	<li>Expiring password reset forms with custom hashed tokens.</li>
	<li>Realtime form (password/username/email/other) input strength/requirement meters.</li>
	<li>SQL injection proof, prepare/parameterized statments utilized for any query in entire backend.</li>
	<li>Brute force/XSS/CSRF protection with custom IDS setup to keep a log of visits and login attempt.</li>
	<li>Session regeneration and expiry system to prevent session hijacking/fixation</li>
	<li>Alert users on brute force attempts with an email and a temporary code to reset their password.</li>
	<li>Rate limiting/throttling on all front-end components and server requests, including email reset forms.</li>
	<li>RESTful architecture used for backend (not fully, however, as Sessions are used a bit).</li>
	<li>Google/Discord login integration.</li>
	<li>Proxies/VPNs/TOR nodes as well as non-existent/fraudulent e-mail addresses filtered/prevented/redirected if suspicious activity is detected.</li>
	<li>PHP-Dot-Env used to safely store API keys, database credentials, email server information, etc.</li>
	<li>Legible code/file structure with seperation of API/Frontend/Requests/Stylesheets.</li>
</ul>