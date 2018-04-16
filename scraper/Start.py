import httplib, os
conn = httplib.HTTPSConnection("api.heroku.com")
conn.request("POST", "/apps/steamleaderboards/dynos", '{"type": "run", "time_to_live": 7500, "command": "scraper", "size": "free"}', {'Content-Type': 'application/json', 'Authorization': 'Bearer ' + os.environ['API_TOKEN'], 'Accept': 'application/vnd.heroku+json; version=3'})
response = conn.getresponse()
print response.status, response.reason
