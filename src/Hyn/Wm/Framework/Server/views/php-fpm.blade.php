

# define unique group with WebsiteID
[Hyn-WM-{{ $website->websiteID }}]
listen                  = 127.0.0.1:{{ 10000 + $website->websiteID }}

# [todo] create seperate users per site
user                    = luceos
group                   = luceos

pm                      = dynamic
pm.max_children         = 20
pm.start_servers        = 5
pm.min_spare_servers    = 5
pm.max_spare_servers    = 10
pm.max_requests         = 20

chdir                   = {{ base_path() }} 
