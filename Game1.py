from machine import Pin,PWM,I2C
from random import randint
import time
from ww import I2cLcd
pins=[15,2,21]

pwm0=PWM(Pin(pins[0]),10000)
pwm1=PWM(Pin(pins[1]),10000)
pwm2=PWM(Pin(pins[2]),10000)
yellow_button = Pin(27, Pin.IN,Pin.PULL_UP)
blue_button  = Pin(13, Pin.IN,Pin.PULL_UP)
red_button  = Pin(14, Pin.IN,Pin.PULL_UP)
green_button  = Pin(12, Pin.IN,Pin.PULL_UP)
buzzer1 = Pin(26, Pin.OUT)
buzzer1.value(0)
i2c = I2C(scl=Pin(5), sda=Pin(18), freq=400000)
devices = i2c.scan()
if len(devices) == 0:
    print("No i2c device !")
else:
    for device in devices:
        print("I2C addr: "+hex(device))
        lcd = I2cLcd(i2c, device, 2, 16)


def play(pin, note, delays):
    pwm = PWM(pin)
    pwm.freq(note)
    pwm.duty(2)
    print(note)
    time.sleep_ms(delays)
    pwm.deinit()
    
def setColor(r,g,b):
    pwm0.duty(1023-b)
    pwm1.duty(1023-g)
    pwm2.duty(1023-r)
    
def red():
    setColor(255,0,0)
    print("red")
    time.sleep_ms(1000)
    
def green():
    setColor(0,255,0)
    print("green")
    time.sleep_ms(1000)
    
def blue():
    setColor(0,0,255)
    print("blue")
    time.sleep_ms(1000)
    
def yellow():
    setColor(195,195,11)
    print("yellow")
    time.sleep_ms(1000)
    
def generate_sequence():
    array=[[0, 1, 2, 3], [0, 1, 3, 2], [0, 2, 1, 3], [0, 2, 3, 1], [0, 3, 1, 2], [0, 3, 2, 1],
         [1, 0, 2, 3], [1, 0, 3, 2], [1, 2, 0, 3], [1, 2, 3, 0], [1, 3, 0, 2], [1, 3, 2, 0],
         [2, 0, 1, 3], [2, 0, 3, 1], [2, 1, 0, 3], [2, 1, 3, 0], [2, 3, 0, 1], [2, 3, 1, 0],
         [3, 0, 1, 2], [3, 0, 2, 1], [3, 1, 0, 2], [3, 1, 2, 0], [3, 2, 0, 1], [3, 2, 1, 0]]
    return array[randint(0, 23)]
 
def pair(array1,array2):
    array=[]
    for i in range(0,4):
        array.append([array1[i],array2[i]])
    return array
        
def a():
    play(buzzer1, 32, 2000)
    
def b():
    play(buzzer1, 262, 2000)
    
def c():
    play(buzzer1, 523, 2000)
    
def d():
    play(buzzer1, 494, 2000)
    
def score(array1, array2):
    n = 0
    for i in array1:
        if i in array2:
            n += 1
    return n
    
colors=[red,green,blue,yellow]
sounds=[a,b,c,d]

Leds=generate_sequence()
Sounds=generate_sequence()
pairq=pair(Leds,Sounds)
TestOrder=generate_sequence()
n=0
answers=[]
try:
    lcd.move_to(0, 0)
    lcd.putstr("Sound and Color Matching")
    for i in range(0,4):
        colors[pairq[i][0]]()
        sounds[pairq[i][1]]()
    while n<4:
        lcd.clear()
        sounds[TestOrder[n]]()
        time.sleep_ms(1000)
        lcd.putstr("hold the button for your answer")
        time.sleep_ms(2000)
        if not yellow_button.value():     
            print("yellow")
            answers.append(3)
        if not blue_button.value():     
            print("blue")
            answers.append(2)
        if not red_button.value():     
            print("red")
            answers.append(0)
        if not green_button.value():     
            print("green")
            answers.append(1)
        n+=1
    lcd.clear()
    key=pair(answers,TestOrder)
    points=score(key,pairq)
    lcd.clear()
    lcd.putstr("Your Score is:")
    lcd.move_to(0,1)
    lcd.putstr("%d" %(points))
    lcd.putstr("/4")
except:
    pwm0.deinit()
    pwm1.deinit()
    pwm2.deinit()
    pass

