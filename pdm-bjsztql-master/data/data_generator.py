import random

# Number of artists to generate
NUM_ARTISTS = 300

# Max number of albums to generate per artist
MAX_ALBUMS = 5

# Maximum number to songs to generate per album
MAX_SONGS = 9

genres = ['Blues', 'Jazz', 'Soul', 'Zouk and Tropical']

determiners = ['the', 'a', 'an', 'this', 'that', 'these', 'those', 'A Few', 'A Little', 'Much', 'Many', 'A Lot of', 'Most', 'Some', 'Any', 'Enough', 'My', 'Your', 'His', 'Her', 'All', 'Both', 'Half', 'Either', 'Neither', 'Each', 'Every', 'Other', 'Another']

# get names from name file
names = []
with open('names.txt') as f:
    for name in f:
        names.append(name.strip().replace(',',''))

# get verbs
verbs = []
with open('verbs.txt') as f:
    for verb in f:
        verbs.append(verb.strip().replace(',',''))

# get nouns
nouns = []
with open('nouns.txt') as f:
    for noun in f:
        nouns.append(noun.strip().replace(',',''))

# get actions
actions = []
with open('actions.txt') as f:
    for action in f:
        actions.append(action.strip().replace(',',''))

used_data = set()

print('artist,album,release_date,track_number,song,genre,duration')

# generate the data
for i in range(NUM_ARTISTS):
    if i == 0:
        artist_name = 'Big Chungus'
    elif i == 1:
        artist_name = 'Josh Yoder'
    elif i == 2:
        artist_name = 'Brock Dyer'
    elif i == 3:
        artist_name = 'Ezequiel Salas'
    elif i == 4:
        artist_name = 'Sam Ford'
    else:
        artist_name = random.choice(names)
    num_albums = random.randint(1, MAX_ALBUMS)
    year_begin = random.randint(1900,2020);
    year_end = random.randint(year_begin,min(2020, year_begin + 30));
    for j in range(num_albums):
        release_date = str(random.randint(year_begin, year_end)) + "-" + str(random.randint(1,12)) + "-" + str(random.randint(1,28))
        ra = random.randint(0,3)
        if ra == 0:
            word1 = random.choice(verbs)
            word1 = word1[0].upper() + word1[1:]
            word2 = random.choice(nouns)
            word2 = word2[0].upper() + word2[1:]
            album_name = word1 + ' ' + random.choice(determiners) + ' ' + word2
        elif ra == 1:
            word1 = random.choice(nouns)
            word1 = word1[0].upper() + word1[1:]
            album_name = word1
        elif ra == 2:
            word1 = random.choice(actions)
            word1 = word1[0].upper() + word1[1:]
            album_name = word1
        elif ra == 3:
            word1 = random.choice(verbs)
            word1 = word1[0].upper() + word1[1:]
            album_name = word1
        if album_name not in used_data:
            used_data.add(album_name)
        else:
            num_albums = num_albums + 1
            continue
        num_songs = random.randint(1, MAX_SONGS)
        album_genres = random.sample(genres, 2)
        j = 1
        for k in range(num_songs):
            rs = random.randint(0,4)
            if rs == 0:
                word1 = random.choice(actions)
                word1 = word1[0].upper() + word1[1:]
                word2 = random.choice(nouns)
                word2 = word2[0].upper() + word2[1:]
                song_name = word1 + ' ' + word2
            elif rs == 1:
                word1 = random.choice(nouns)
                word1 = word1[0].upper() + word1[1:]
                word2 = random.choice(nouns)
                word2 = word2[0].upper() + word2[1:]
                song_name = word1 + ' ' + word2
            elif rs == 2:
                word1 = random.choice(actions)
                word1 = word1[0].upper() + word1[1:]
                word2 = random.choice(actions)
                word2 = word2[0].upper() + word2[1:]
                song_name = word1 + ' ' + word2
            elif rs == 3:
                song_name = random.choice(actions)
                song_name = song_name[0].upper() + song_name[1:]
            elif rs == 4:
                song_name = random.choice(nouns)
                song_name = song_name[0].upper() + song_name[1:]
            song_genre = random.choice(album_genres)
            song_duration = str(random.randint(0,5)) + ':' + str(random.randint(0, 5)) + str(random.randint(0, 9))
            print(artist_name + ',' + album_name + ',' + release_date + ',' + str(j) + ',' + song_name + ',' + song_genre + ',00:' + song_duration)
            j = j + 1

