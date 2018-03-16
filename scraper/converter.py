fail = open("games.txt", "r")
games = []
for line in fail:
    games.append(line.split("\t"))
fail.close()
for i in range(len(games)):
    if games[i][3] == "0":
        score = 0.5
    else:
        score = float(games[i][2]) / float(games[i][3])
    games[i] = games[i][:2] + [str(score)] + games[i][3:]
games.sort(key=lambda game: game[4])
games.reverse()
games = games[:9001]
fail = open("games.txt", "w")
for game in games:
    for field in game[:-1]:
        fail.write(field + "\t")
    fail.write(game[-1])
fail.close()
