"""
    Bryan Rachmat and Andrew Singari
    CMPT 401 Project

    Game2 Color Memorization

"""
from machine import Pin,PWM,I2C
from random import randint
import time
from ww import I2cLcd
import time
from irrecvdata import irGetCMD
import network
import urequests
import ujson


# Set up Wi-Fi connection
wifi = network.WLAN(network.STA_IF)
wifi.active(True)
wifi.connect('TWUGuest','')

# Wait for connection with a timeout
timeout = 10
while not wifi.isconnected() and timeout > 0:
    time.sleep(1)
    timeout -= 1

# Check if connected
if wifi.isconnected():
    print('Connected to WiFi')
else:
    print('Failed to connect to WiFi')


# Record the start time
start_time = time.time()

#Initializing hardware connections
pins=[15,2,21]

pwm0=PWM(Pin(pins[0]),10000)
pwm1=PWM(Pin(pins[1]),10000)
pwm2=PWM(Pin(pins[2]),10000)
yellow_button = Pin(27, Pin.IN,Pin.PULL_UP)
blue_button  = Pin(13, Pin.IN,Pin.PULL_UP)
red_button  = Pin(14, Pin.IN,Pin.PULL_UP)
green_button  = Pin(12, Pin.IN,Pin.PULL_UP)
i2c = I2C(scl=Pin(5), sda=Pin(18), freq=400000)
devices = i2c.scan()
if len(devices) == 0:
    print("No i2c device !")
else:
    for device in devices:
        print("I2C addr: "+hex(device))
        lcd = I2cLcd(i2c, device, 2, 16)

level=4
sequence=3+level

def setColor(r,g,b):
    """
    Function to change the color of the RGB LED
    """
    pwm0.duty(1023-b)
    pwm1.duty(1023-g)
    pwm2.duty(1023-r)
    
def red():
    """
    setColor to red
    """
    setColor(255,0,0)
    print("red")
    time.sleep_ms(1000)
    
def green():
    """
    setColor to green
    """
    setColor(0,255,0)
    print("green")
    time.sleep_ms(1000)
    
def blue():
    """
    setColor to blue
    """
    setColor(0,0,255)
    print("blue")
    time.sleep_ms(1000)
    
def yellow():
    """
    setColor to yellow
    """
    setColor(195,195,11)
    print("yellow")
    time.sleep_ms(1000)

def none():
    """
    setColor to none
    """
    setColor(0,0,0)
    time.sleep_ms(200)
    
def generate_sequence(n):
    """
    Returns a sequence from 0 to 3 for random index, used for LED 
    """
    array=[]
    for i in range (0,n):
        array.append(randint(0,3))
    
    return array
    

#array of color, test order, and answers
answers=[]
colors=[red,green,blue,yellow]
n=0
score=0
TestOrder=generate_sequence(sequence) 


try:
    #LED Blink according to sequence
    for i in TestOrder:
        colors[i]()
        none()
    lcd.move_to(0, 0)
    lcd.clear()
    lcd.putstr("Click the buttons in order")

    #User clicks button according to what they memorized.
    while n<sequence:
        if not yellow_button.value():     
            print("yellow")
            n+=1
            answers.append(3)
            time.sleep_ms(500)
        if not blue_button.value():     
            print("blue")
            n+=1
            answers.append(2)
            time.sleep_ms(500)
        if not red_button.value():     
            print("red")
            n+=1
            answers.append(0)
            time.sleep_ms(500)
        if not green_button.value():     
            print("green")
            n+=1
            answers.append(1)
            time.sleep_ms(500)
    for i in range(0,sequence):
        if TestOrder[i]==answers[i]:
            score+=1
    end_time = time.time()
    #Calculate the elapsed time 
    elapsed_time = end_time - start_time  
    #Calculates and prints score.    
    lcd.clear()
    lcd.move_to(0,0)
    lcd.putstr("Score: ")
    lcd.putstr("%d" %(score))
    lcd.putstr("/%d" %(sequence))
    lcd.move_to(0,1)
    lcd.putstr("%d" %(elapsed_time))
    lcd.putstr(" seconds")

    url = 'https://coastspas.com/randomdeveloperenv/game.php'
    data = {'score': score, 'name':'LED Sequence'}
    json_data=ujson.dumps(data)
    
    headers = {
    'Content-Type': 'application/json',
    'User-Agent': 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.110 Safari/537.3'
    }

              
    try:
        response = urequests.post(url, data=bytes(json_data, 'utf-8'), headers=headers)
        print(response.text)

    
    except Exception as e:
            print("Error:", e)         
except:
    pass
