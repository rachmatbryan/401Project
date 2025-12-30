# Cognitive Games Project

Aims to develop a series of cognitive games designed to help dementia patients exercise their memory and cognitive skills. The project involves the integration of hardware and software components to create a seamless user experience. 

## Installation

- Thonny Python IDE 
- Micropython Firmware
- ESP-32 microcontroller (setup shown in images below)
- Required sensors and actuators 
- Web server with PHP and MySQL support
- Internet connection for the ESP-32

<img src="images/breadboard.png" alt="ESP-32 Breadboard" width="400" />
<img src="images/circuit.png" alt="ESP-32 Circuit" width="400" />

## Overview

* **Number Memorization Game:** Focuses on memorization, the user memorizes a sequence of numbers [Watch here](https://youtu.be/0-Y1nkv3sm8)
* **LED Color Sequence Memorization Game:** Focuses on visuals and memorization, the user memorizes a color sequence [Watch here](https://youtu.be/rCcM60VJ71o)
* **Color and Sound Memorization Game:** Focuses on hearing and memorization, the user matches the audio and color.  [Watch here](https://youtube.com/shorts/b1Pay0B4rWc?si=Pg0U3IrycSMOKnu9)

Each game records the player's performance, which is then sent to a remote server for storage and analysis. The web application component allows caregivers to monitor the progress of the players.

## Features

- **Real-time Data Recording:** Players' scores and time taken are recorded and sent to a remote database.
- **Web Application:** A web interface to record, visualize, and analyze players' performance data.
- **ESP-32 Integration:** Hardware components are controlled using MicroPython on an ESP-32 microcontroller.
- **User-friendly Interface:** The web application is built with a focus on simplicity and ease of use. 


## Usage

1. **Playing the Games:**
    * ESP-32 connected to a power source and configured to Wifi.
    * Follow the instructions on the LCD screen to play the games.
2. **Viewing Results:**
    * Access the web application to view the scores and performance data of the players.

## Technologies Used

* **MicroPython:** For programming the ESP-32 microcontroller.
* **PHP:** For server-side scripting.
* **MySQL:** For database management.
* **HTML/CSS/JavaScript:** For the web application front-end.

### Libraries

* **Header/HTTP Requests:** Interaction between MicroPython and database (by connecting with urequests)
* **MySQL:** Functions for insert SQL query, fetching associative array
* **JSON:** Decode JSON string into PHP variable (connects with ujson on MicroPython)
* **php://input:** Reads post data sent from MicroPython

### Contributors

- Andrew Daniel Singari
- Bryan Rachmat
- Dr. Andrew Park 




