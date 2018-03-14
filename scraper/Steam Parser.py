import urllib, json, math, os

#TODO: Multithreading?
#TODO: First go through all the games, then gather the little details
#TODO: Tags

def getGames(pagenr):
    page = urllib.urlopen("http://store.steampowered.com/search/?sort_by=Released_DESC&category1=998&page=" + str(pagenr))
    contents = page.read()
    contents = contents[contents.find("<!-- List Items -->"):contents.find("<!-- End List Items -->")]
    return contents.split("<a href=")[1:]

def listGames(pagenr):
    games = getGames(pagenr)
    names, dates, appids, pos_scores, tot_scores, scores, prices, platforms = [], [], [], [], [], [], [], []
    skipped = 0
    
    for i in range(len(games)):
        game = games[i]
        i -= skipped
        url = game[1:game.find("?")].split("/")
        
        #Skip entry if it's not a game
        if (url[3] != "app"):
            skipped += 1
            continue

        #Optionally also skip if game has below n reviews
        score = game[game.find('<div class="col search_reviewscore responsive_secondrow">') + 57:]
        score = score[:score.find("</div>")].strip()
        if score == "": #Skip games with less than 10 reviews
            skipped += 1
            continue
        #elif int(score[score.find("% of the ") + 9:].split(" ", 1)[0]) < 20: #n
        #    skipped += 1
        #    continue
        
        #Find date
        date = game[game.find('<div class="col search_released responsive_secondrow">') + 54:]
        date = date[:date.find("</div>")].split()
        try:
            dates.append(int(date[2]) * 10000 + (["Jan,", "Feb,", "Mar,", "Apr,", "May,", "Jun,", "Jul,", "Aug,", "Sep,", "Oct,", "Nov,", "Dec,"].index(date[1]) + 1) * 100 + int(date[0]))
        except:
            dates.append(-1) #Fix later with fixDates

        #Find appid
        appids.append(url[4])

        #Find price
        score = game.find('<div class="col search_price  responsive_secondrow">') + 52
        if score == 51: #Discounted
            score = game.find("</strike></span><br>") + 20
        price = game[score:]
        price = price[:price.find("</div>")].strip()
        if "Free" in price:
            price = 0
        else:
            price = price[:-3].replace(",", ".")
            try:
                price = float(price)
            except:
                price = -1
        prices.append(price)

        #Find platforms
        platform = 0 #Binary encoded, can add more if needed
        if game.find('<span class="platform_img win"></span>') != -1:
            platform += 1
        if game.find('<span class="platform_img mac"></span>') != -1:
            platform += 2
        if game.find('<span class="platform_img linux"></span>') != -1:
            platform += 4
        platforms.append(platform)

        #Find scores
        page = urllib.urlopen("http://store.steampowered.com/appreviews/" + appids[i] + "?json=1&filter=all&language=all&review_type=all&purchase_type=" + ("all" if price == 0 else "steam"))
        contents = json.loads(page.read())
        pos_scores.append(contents["query_summary"]["total_positive"])
        tot_scores.append(contents["query_summary"]["total_reviews"])
        rating = float(pos_scores[i]) / tot_scores[i]
        scores.append(rating - (rating - 0.5) * math.pow(2, -math.log10(tot_scores[i] + 1)))
        
        #Find game name
        name = game[game.find('<span class="title">') + 20:]
        names.append(name[:name.find("</span>")])
        
    return names, pos_scores, tot_scores, scores, appids, platforms, dates, prices

#To not run listGames just to get dates
def listDates(pagenr):
    games = getGames(pagenr)
    dates = []
    
    for i in range(len(games)):
        date = games[i][games[i].find('<div class="col search_released responsive_secondrow">') + 54:]
        date = date[:date.find("</div>")].split()
        try:
            dates.append(int(date[2]) * 10000 + (["Jan,", "Feb,", "Mar,", "Apr,", "May,", "Jun,", "Jul,", "Aug,", "Sep,", "Oct,", "Nov,", "Dec,"].index(date[1]) + 1) * 100 + int(date[0]))
        except:
            dates.append(-1)

    return dates

#Go through the page backwards, replacing any broken dates with the following game's date
def fixDates(dates):
    for i in range(1, len(dates) + 1):
        if dates[-i] == -1:
            if i == 1: #For the last game on a page, find the first game with a date on the next page
                exdates = listDates(pagenr + 1)
                for date in exdates:
                    if date != -1:
                        dates[-i] = date
                        break
            else:
                dates[-i] = dates[-i + 1]

fail = open("games.txt", "w")
#Find the total number of pages
page = urllib.urlopen("http://store.steampowered.com/search/?sort_by=Released_DESC&category1=998&page=1")
contents = page.read()
contents = contents[contents.find('<div class="search_pagination_right">')+37:]
contents = contents[:contents.find('</div>')].split()[-14]

for pagenr in xrange(1, int(contents[contents.rfind("=") + 1:-1]) + 1):
    print pagenr,
    names, pos_scores, tot_scores, scores, appids, platforms, dates, prices = listGames(pagenr)
    fixDates(dates)
    for i in range(len(names)):
        fail.write(appids[i] + "\t" + names[i] + "\t" + str(pos_scores[i]) + "\t" + str(tot_scores[i]) + "\t" + str(scores[i]) + "\t" + str(platforms[i]) + "\t" + str(dates[i] / 10000) + "-" + str((dates[i] % 10000) / 100) + "-" + str(dates[i] % 100) + "\t" + str(prices[i]) + "\n")
        
fail.close()

os.startfile("DBsaver.py")
