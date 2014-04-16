#
#	Auto generated Nginx configuration
#		provided by Hyn WM
#
#	Website: #{{ $website -> id }} {{ $website -> primary -> hostname }} 
#	On: {{ date("Y-m-d H:i:s" ) }} 
#
#
#
#	General configuration
#
#
@if ($website->pathCache)

proxy_cache_path {{ $website->pathCache }}/images levels=1:2 keys_zone=img_cache_{{ $website -> id }}:10m max_size=1G;

@endif
server {
	# ports to listen on; 80 is default
	listen   80;
	
	server_name {{ implode(" ",array_flatten($domains)) }};
	
	# hide nginx version
	server_tokens		off;
	
	# redirect any www domain to non-www
	if ( $host ~* ^www\.(.*) ) {
		set 		$host_nowww 	$1;
		rewrite 	^(.*)$ 		$scheme://$host_nowww$1 permanent;
	}
	# root path of website; serve files from here
	root 			{{ public_path() }}/;
	index 			index.php;

	access_log		{{ Hyn\Wm\Framework\Website\SiteManager::pathLog() }}/{{ $website->primary->hostname }}.access.log;
	error_log		{{ Hyn\Wm\Framework\Website\SiteManager::pathLog() }}/{{ $website->primary->hostname }}.error.log notice;
	
	# deny iframe calls from other domains
	add_header 		X-Frame-Options 	SAMEORIGIN;
	add_header		X-Hyn-Version		{{ System::getVersion() }};
	add_header 		X-Hyn-Website 		{{ $website->id }}-{{ $website->primary->hostname }};

	add_header		X-Spdy-Version		$spdy;
	# hide php version
	proxy_hide_header	X-Powered-By;
	
	# provide statuspage of Nginx for predefined IP's
	location /status/nginx {
		stub_status 	on;
		access_log 	off;
		allow 		96.126.107.213;
		allow		95.97.230.244;
		deny 		all;
	}
	location ~ ^favicon\.ico$ {
		access_log 	off;
		log_not_found 	off;
		expires		30d;
	}
	location /phpmyadmin {
		access_log 	off;
		log_not_found 	off;
	}
	location ~ \.(eot|woff|ttf|otf)$ {
		expires 	1y;
		log_not_found 	off;
		access_log 	off;
		add_header 	Cache-Control 		"public";
                add_header 	Access-Control-Allow-Origin *;
	}
	location ~ \.(yaml|txt|me|md|git|svn)$ {
		deny 		all;
	}
	@if ($website->pathMedia)
	
	# map public media folder to private media folder
	location /media/ {
		alias 		{{ $website->pathMedia }}/;
	}
	location ~* ^/(resize|crop)/ {
            proxy_pass 		http://image-cache.{{ $website->primary->hostname }}$request_uri;
            proxy_cache 	img_cache_{{ $website -> id }};
            proxy_cache_key 	"$host$document_uri";
            proxy_cache_valid 	200 1d;
            proxy_cache_valid 	any 1m;
            proxy_cache_use_stale error timeout invalid_header updating;
        }
	@endif
	
	@if ($website->pathCache)
	# map public cache folder to private domain folder
	location /cache/ {
		alias 		{{ $website->pathCache }};
	}
	@endif
	
	location / {
		index 		index.php;
		try_files 	$uri $uri/ $uri/index.php?$args /index.php?$args;
	}
	# pass the PHP scripts to FastCGI server from upstream phpfcgi
	location ~ \.php(/|$) {
		fastcgi_pass 	127.0.0.1:{{ 10000 + $website->id }};
		include 	fastcgi_params;
		
		fastcgi_split_path_info ^(.+\.php)(/.*)$;
		
		fastcgi_param  SCRIPT_FILENAME $document_root$fastcgi_script_name;
	}
}
@if ($website->pathMedia)
server {
	listen 80;
	server_name {{ implode(" ",array_flatten($imagedomains)) }};

	location ~* ^/resize/([\d\-]+)/([\d\-]+)/media/(.+)$ {
		alias 		{{ $website->pathMedia }}/$3;
		image_filter 	resize $1 $2;
		image_filter_buffer 10M;
		error_page 	415 = /empty;
	}

	location ~* ^/crop/([\d\-]+)/([\d\-]+)/media/(.+)$ {
		alias 		{{ $website->pathMedia }}/$3;
		image_filter crop $1 $2;
		image_filter_buffer 10M;
		error_page 	415 = /empty;
	}

	location ~* ^/rotate/([\d\-]+)/media/(.+)$ {
		alias 		{{ $website->pathMedia }}/$2;
		image_filter_buffer         10M;
		image_filter                rotate $1;
		error_page 	415 = /empty;
	}
	location ~* ^/media/(.+)$ {
		alias 		{{ $website->pathMedia }}/$1;
		error_page 	415 = /empty;
	}

	location = /empty {
		empty_gif;
	}
}
@endif