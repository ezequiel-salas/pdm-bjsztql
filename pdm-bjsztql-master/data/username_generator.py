import random

MAX_NUMBER = 100

names = []
with open('names.txt') as f:
    for name in f:
        name = name.strip().replace(',','')
        names.append(name.replace(" ",""))

with open("spanish-first-names.txt") as f:
    for name in f:
        names.append(name.strip())

with open("spanish-last-names.txt") as f:
    for name in f:
        names.append(name.strip())
print('UID,username')
for i in range(MAX_NUMBER):
    name = random.choice(names).lower()
    d = random.randint(0,len(name))-1
    if d == 0:
        name = name[0].upper() + name[d+1:]
    else:
        name = name[0:d-1] + name[d].upper() + name[d+1:]
    a = random.randint(1,5)
    for b in range(a):
        name = name + random.randint(0,9).__str__()
    print(i.__str__()+","+name)