import urllib, json, math, os, sys, psycopg2

#appid, name, rating, votes, score, platforms, release, price [, tags]
#  0  ,  1  ,   2   ,   3  ,   4  ,     5    ,    6   ,   7   [,  8  ]
#TODO: Tags

def listGames(pagenr):
    page = urllib.urlopen("http://store.steampowered.com/search/?sort_by=Released_DESC&category1=998&page=" + str(pagenr))
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
            rating = float(score[0][:-1]) / 100
            votes = int(score[-1].replace(",", ""))
            score = rating - (rating - 0.5) * math.pow(2, -math.log10(votes + 1))
        
        #Find date
        date = game[game.find('<div class="col search_released responsive_secondrow">') + 54:]
        date = date[:date.find("</div>")].split()
        try:
            date = int(date[2]) * 10000 + (["Jan,", "Feb,", "Mar,", "Apr,", "May,", "Jun,", "Jul,", "Aug,", "Sep,", "Oct,", "Nov,", "Dec,"].index(date[1]) + 1) * 100 + int(date[0])
        except:
            date = -1 #Fix later with fixDates

        #Find appid
        appid = url[4]

        #Find price
        price = game.find('<div class="col search_price  responsive_secondrow">') + 52
        if price == 51: #Discounted
            price = game.find("</strike></span><br>") + 20
        price = game[price:]
        price = price[:price.find("</div>")].strip()
        if "Free" in price:
            price = 0
        else:
            price = price[:-3].replace(",", ".")
            try:
                price = float(price)
            except:
                price = -1

        #Find platforms
        platforms = 0 #Binary encoded, can add more if needed
        if game.find('<span class="platform_img win"></span>') != -1:
            platforms += 1
        if game.find('<span class="platform_img mac"></span>') != -1:
            platforms += 2
        if game.find('<span class="platform_img linux"></span>') != -1:
            platforms += 4
        
        #Find game name
        name = game[game.find('<span class="title">') + 20:]
        name = name[:name.find("</span>")]

        games.append([appid, name, rating, votes, score, platforms, date, price])
        
    return games

#Go through the page backwards, replacing any broken dates with the following game's date
def fixDates(games):
    for i in xrange(len(games)):
        if games[i][6] == -1:
            games[i][6] = games[i - 1][6]

#Get all the games, but with imprecise scores
def firstPass():
    allGames = []
    #Find the total number of pages
    page = urllib.urlopen("http://store.steampowered.com/search/?sort_by=Released_DESC&category1=998&page=1")
    page = page.read()
    page = page[page.find('<div class="search_pagination_right">')+37:]
    page = page[:page.find('</div>')].split()[-14]
    page = int(page[page.rfind("=") + 1:-1])
    page = 2 #TEMPORARY

    for pagenr in xrange(1, page + 1):
        print pagenr,
        games = listGames(pagenr)
        games.reverse()
        allGames = games + allGames

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
    allGames = allGames[-10:]
    #allGames = allGames[-9001:] #Database 10K row limit
    
    return allGames

def getPreciseScores(games):
    for game in games:
        page = urllib.urlopen("http://store.steampowered.com/appreviews/" + game[0] + "?json=1&filter=all&language=all&review_type=all&purchase_type=" + ("all" if game[7] <= 0 else "steam"))
        contents = json.loads(page.read())
        game[3] = contents["query_summary"]["total_reviews"]
        if game[3] != 0:
            game[2] = float(contents["query_summary"]["total_positive"]) / game[3]
        else:
            game[2] = 0.5
        game[4] = game[2] - (game[2] - 0.5) * math.pow(2, -math.log10(game[3] + 1))

#try:
print os.getcwd()
print sys.path[0]
#os.chdir(sys.path[0])
#print os.getcwd()

games = firstPass()
getPreciseScores(games)
games.sort(key = lambda game: game[4])
games.reverse()

#Save results to file
fail = open("games.txt", "w")
for game in games:
    fail.write(game[0] + "\t" + game[1] + "\t" + str(game[2]) + "\t" + str(game[3]) + "\t" + str(game[4]) + "\t" + str(game[5]) + "\t" + str(game[6] / 10000) + "-" + str((game[6] % 10000) / 100) + "-" + str(game[6] % 100) + "\t" + str(game[7]) + "\n")
fail.close()

print "Done!"

#Save results to database
conn = psycopg2.connect(os.environ['DATABASE_URL'], sslmode='require')
with conn:
    with conn.cursor() as curs:
        curs.execute("DELETE FROM games;")
        curs.copy_from(open("games.txt", "r"), "games")
conn.close()
##except:
##    print "ERROR!"
##    conn = psycopg2.connect(os.environ['DATABASE_URL'], sslmode='require')
##    with conn:
##        with conn.cursor() as curs:
##            curs.execute("SELECT error_refresh_time()")
##    conn.close()
