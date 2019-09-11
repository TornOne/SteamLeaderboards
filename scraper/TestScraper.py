import urllib, json, math, os, psycopg2, multiprocessing

#appid, name, rating, votes, score, windows, mac, linux, vr, release, price, tags
#  0  ,  1  ,   2   ,   3  ,   4  ,    5   ,  6 ,   7  , 8 ,    9   ,  10  ,  11

def listGames(pagenr):
    page = urllib.urlopen("http://store.steampowered.com/search/results?ignore_preferences=1&sort_by=Released_DESC&category1=998&page=" + str(pagenr))
    contents = page.read()
    contents = contents[contents.find("<!-- List Items -->"):contents.find("<!-- End List Items -->")]
    sections = contents.split("<a href=")[1:]
    games = []
    
    for game in sections:
        url = game[1:game.find("?")].split("/")
        
        #Skip entry if it's not a game
        if (url[3] != "app"):
            continue

        #Find (initial) scores
        score = game[game.find('<div class="col search_reviewscore responsive_secondrow">') + 57:]
        score = score[:score.find("</div>")].strip()
        if score == "":
            rating = 0
            votes = 0
            score = 0
        else:
            score = score[score.find("&lt;br&gt;") + 10:score.find(" user reviews")].split()
            rating = int(score[0][:-1])
            votes = int(score[-1].replace(",", ""))
            min_pos = rating * votes / 100
            max_pos = min(rating + 1, 100) * votes / 100
            if rating * votes % 100 != 0:
                min_pos += 1
            else:
                max_pos -= 1
            rating = float((min_pos + max_pos) / 2 if max_pos > min_pos else min_pos) / votes
            score = normalizedScore(rating, votes)
        
        #Find date
        date = game[game.find('<div class="col search_released responsive_secondrow">') + 54:]
        date = date[:date.find("</div>")].split()
        try:
            date[1] = date[1][:-1]
            months = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"]
            if date[0] < date[1]: #Europe
                date = int(date[2]) * 10000 + (months.index(date[1]) + 1) * 100 + int(date[0])
            else: #America
                date = int(date[2]) * 10000 + (months.index(date[0]) + 1) * 100 + int(date[1])
        except:
            date = -1 #Fix later with fixDates

        #Find appid
        appid = url[4]

        #Find price
        price = game.find('<div class="col search_price  responsive_secondrow">') + 52
        if price == 51: #Discounted
            price = game.find("</strike></span><br>") + 20
        price = game[price:]
        price = price[:price.find("</div>")].strip().replace("-", "0")
        if "Free" in price:
            price = 0
        else:
            if len(price) > 0 and price[0] == "$": #America
                price = price[1:]
            else: #Europe
                price = price[:-3].replace(",", ".")
            try:
                price = float(price)
            except:
                price = -1

        #Find platforms
        windows = game.find('<span class="platform_img win"></span>') != -1
        mac = game.find('<span class="platform_img mac"></span>') != -1
        linux = game.find('<span class="platform_img linux"></span>') != -1
        vr = game.find('class="platform_img htcvive') != -1 or game.find('class="platform_img oculusrift"') != -1 or game.find('class="platform_img windowsmr"') != -1
        
        #Find game name
        name = game[game.find('<span class="title">') + 20:]
        name = name[:name.find("</span>")]
        name = name.replace("\\", "\\\\") #Sanitize app names

        games.append([appid, name, rating, votes, score, windows, mac, linux, vr, date, price, []])
        
    return games

#Go through the page backwards, replacing any broken dates with the following game's date
def fixDates(games):
    for i in xrange(len(games)):
        if games[i][9] == -1:
            games[i][9] = games[i - 1][9]

#Get all the games, but with imprecise scores
def firstPass():
    #Find the total number of pages
    page = urllib.urlopen("http://store.steampowered.com/search/?sort_by=Released_DESC&category1=998&page=1")
    page = page.read()
    page = page[page.find('<div class="search_pagination_right">')+37:]
    page = page[:page.find('</div>')].split()[-14]
    page = int(page[page.rfind("=") + 1:-1])

    games = pool.map(listGames, range(1, page + 1))
    allGames = []
    for game in games:
        allGames += game
    allGames.reverse()
    fixDates(allGames)

    #Filter out duplicates
    i = 0
    appids = set()
    while i < len(allGames):
        appid = allGames[i][0]
        if appid in appids:
            allGames.pop(i)
        else:
            appids.add(appid)
            i += 1

    #Filter out low scores
    allGames.sort(key = lambda game: game[4])
    allGames = allGames[-9975:] #Database 10K row limit
    
    return allGames

def getPreciseScore(game):
    if game[3] < 100: #Our initial guess is already exact
        return (game[2], game[3], game[4])
    page = urllib.urlopen("http://store.steampowered.com/appreviews/" + game[0] + "?json=1&filter=all&language=all&review_type=all&purchase_type=" + ("all" if game[10] <= 0 else "steam"))
    contents = json.loads(page.read())
    if contents["success"] != 1: #Keep the current scores in case of unsuccess
        return (game[2], game[3], game[4])
    game[3] = contents["query_summary"]["total_reviews"]
    if game[3] != 0:
        game[2] = float(contents["query_summary"]["total_positive"]) / game[3]
    else:
        game[2] = 0.5
    game[4] = normalizedScore(game[2], game[3])
    #Multiprocessing pools create new instances or something, so list values don't get updated and have to be assigned outside
    return (game[2], game[3], game[4])

def getTags(game):
    page = urllib.urlopen("http://store.steampowered.com/apphoverpublic/" + game[0])
    contents = page.read()
    tags = contents.split('<div class="app_tag">')

    #Alternative method if the main one breaks down again
    #conn = httplib.HTTPSConnection("store.steampowered.com")
    #conn.request("GET", "/app/" + game[0] + "/", "", {"Content-Type": "application/x-www-form-urlencoded", "Cookie": "lastagecheckage=3-April-1997; mature_content=1; birthtime=860014801"})
    #response = conn.getresponse()
    #contents = response.read()
    #conn.close()
    #tags = contents.split('class="app_tag" style="display: none;">')
    
    #Only the top 75% of the tags, which is an approximation of what Steam does
    for tag in tags[1:int(0.75 * (len(tags) + 1))]:
        game[11].append(tag[:tag.find("<")])
    #Multiprocessing pools create new instances or something, so list values don't get updated and have to be assigned outside
    return game[11]

def normalizedScore(rating, reviews):
    weighted = rating - (rating - 0.5) * math.pow(reviews + 1, -0.25)
    return weighted - math.sin(4 * math.pi * weighted) / (6 * math.pi)

#Multiprocessing, 15 processes, should take about 20 minutes for the foreseeable future
if __name__ == "__main__":
    pool = multiprocessing.Pool(15)

    games = firstPass()
    newScores = pool.map(getPreciseScore, games)
    tags = pool.map(getTags, games)
    for i in range(len(games)):
        for j in range(3):
            games[i][j + 2] = newScores[i][j]
        games[i][11] = tags[i]
    games.sort(key = lambda game: game[4])
    games.reverse()

    #Filter out blacklisted tags and count all other tags
    blacklist = []
    fail = open("scraper/Blacklist.txt", "r")
    for line in fail:
        blacklist.append(line[:-1])
    fail.close()
    tagCounts = {}
    for game in games:
        i = 0
        while i < len(game[11]):
            if (game[11][i] in blacklist):
                del game[11][i]
            else:
                try:
                    tagCounts[game[11][i]] += 1
                except:
                    tagCounts[game[11][i]] = 1
                i += 1

    #Make an ordered list of the other tags
    allTags = []
    for tag in tagCounts:
        allTags.append([tagCounts[tag], tag])
    allTags.sort()
    allTags.reverse()
    tagString = ""
    for tag in allTags:
        tagString += tag[1].replace("'", "''").replace("&amp;", "&") + ","
    tagString = tagString[:-1]

    #Save results to file
    fail = open("games.txt", "w")
    for game in games:
        fail.write(game[0] + "\t" + game[1] + "\t")
        for i in range(2, 9):
            fail.write(str(game[i]) + "\t")
        fail.write(str(game[9] / 10000) + "-" + str((game[9] % 10000) / 100) + "-" + str(game[9] % 100) + "\t")
        fail.write(("\\N" if game[10] < 0 else str(game[10])) + "\t{")
        for tag in game[11][:-1]:
            fail.write(tag + ", ")
        fail.write((game[11][-1] if len(game[11]) else "") + "}\n")
    fail.close()

    #Save results to database
    conn = psycopg2.connect(os.environ['DATABASE_URL'], sslmode='require')
    with conn:
        with conn.cursor() as curs:
            curs.execute("DELETE FROM games;")
            curs.copy_from(open("games.txt", "r"), "games")
            curs.execute("UPDATE config_strings SET value = '" + tagString + "' WHERE key = 'tags';")
            curs.execute("SELECT update_completed_refresh_time();")
    conn.close()
