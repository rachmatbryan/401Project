from Game1 import game1
from Game2 import game2
from Game3 import game3

import time

game1b = Pin(35, Pin.IN,Pin.PULL_UP)
game2b  = Pin(34, Pin.IN,Pin.PULL_UP)
game3b  = Pin(22, Pin.IN,Pin.PULL_UP)

try:
    while True:
        time.sleep_ms(100)
        if not game1b.value():
            game1()
        if not game2b.value():
            game2()
        if not game3b.value():
            game3()
except:
    pass