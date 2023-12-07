"""
    Bryan Rachmat and Andrew Singari
    CMPT 401 Project

    Game3 Number Memorization

"""
from machine import ADC,Pin,I2C
import time
from ww import I2cLcd
from irrecvdata import irGetCMD
from random import randint
import time
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
    # Here you would put your existing code that sends the score
    # For example:
else:
    print('Failed to connect to WiFi')



# Record the start time
start_time = time.time()

#Initializing hardware connections
i2c = I2C(scl=Pin(5), sda=Pin(18), freq=400000)
devices = i2c.scan()
if len(devices) == 0:
    print("No i2c device !")
else:
    for device in devices:
        print("I2C addr: "+hex(device))
        lcd = I2cLcd(i2c, device, 2, 16)

recvPin = irGetCMD(33)

#Hex to number dictionary
hex_dict = {
            "0xff6897":"0",
            "0xff30cf": "1",
            "0xff18e7": "2",
            "0xff7a85": "3",
            "0xff10ef": "4",
            "0xff38c7": "5",
            "0xff5aa5": "6",
            "0xff42bd": "7",
            "0xff4ab5": "8",
            "0xff52ad": "9"
        }

#Level
level=5
sequence=3+level

def generate_sequence(n):
    """
    Returns a unique sequence from 0 to 9  used for LED and Buzzer
    """
    array=[]
    for i in range (0,n):
        array.append(randint(0,9))
    
    return array
  
#creates sequence
values=generate_sequence(sequence)
print(values)
lcd.move_to(0, 0)
lcd.clear()
lcd.putstr("Number Game Starting")
time.sleep_ms(2000)
lcd.clear()
clicks=0
answers=[]
score=0


try:
    #Initializes game

    #prints the values on LCD
    for i in range(0,3):
        lcd.clear()
        for j in range(0,sequence):
            lcd.putstr("%d" %(values[j]))
            time.sleep_ms(600)
    lcd.clear()
    lcd.putstr("Input the numbers")
    time.sleep_ms(2000)
    lcd.clear()
    
    #records the values that the user memorized
    while clicks<sequence:
        irValue = recvPin.ir_read()
    
        if irValue:
            lcd.putstr(hex_dict[irValue])
            answers.append(int(hex_dict[irValue]))
            clicks+=1

    end_time = time.time()

    #Calculate the elapsed time
    elapsed_time = end_time - start_time
    lcd.clear()
    lcd.putstr("Checking...")
    time.sleep_ms(2000)
    lcd.clear()
    for i in range(0,sequence):
        if values[i]==answers[i]:
            score+=1
    lcd.clear()
    lcd.move_to(0,0)
    lcd.putstr("Score:")
    lcd.putstr("%d" %(score))
    lcd.putstr("/%d" %(sequence))
    lcd.move_to(0,1)
    lcd.putstr("%d" %(elapsed_time))
    lcd.putstr(" seconds")


    url = 'https://coastspas.com/randomdeveloperenv/game.php'
    data = {'score': score}
    json_data=ujson.dumps(data)
    
    headers = {
    'Content-Type': 'application/json',
    'User-Agent': 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.110 Safari/537.3'
    }
              
    try:
        response = urequests.post(url, data=bytes(json_data, 'utf-8'), headers=headers)
        print(response.text)
        print(json_data)
        

    except Exception as e:
        print("Error:", e)

except:
    pass

