

# define unique group with WebsiteID
[Hyn-WM-{{ $website -> id }}]
listen                  = 127.0.0.1:{{ 10000 + $website -> id }}

# [todo] create seperate users per site
user                    = www-data
group                   = www-data

pm                      = dynamic
pm.max_children         = 20
pm.start_servers        = 5
pm.min_spare_servers    = 5
pm.max_spare_servers    = 10
pm.max_requests         = 20

chdir                   = {{ base_path() }} 
