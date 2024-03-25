- 👋 Hi, I’m @russanomarco and @anconaguido

You can use this instruction for use a Raspberry Pi 4 (Model B) to use it in mode "KIOSK"

open CMD 

INSTRUCTIONS

installing SSH packages


ssh connection username@IP


installing "apt-get install" openssh-server packages


sudo systemctl enable ssh


after this open CMD and write this:


$ sudo nano .config/wayfire.ini


Take a look at the section titled [autostart]. At the moment, it reads like this:


[autostart]
panel = wfrespawn wf-panel-pi
background = wfrespawn pcmanfm --desktop --profile LXDE-pi
xdg-autostart = lxsession-xdg-autostart
chromium = chromium-browser "https://www.google.it" --kiosk --noerrdialogs --disable-infobars --no-first-run --ozone-platform=wayland --enable-features=OverlayScrollbar --start-maximized
switchtab = bash ~/switchtab.sh
screensaver = false
dpms = false


Press Ctrl+X, then Y, and finally Enter to save the edited file with nano. Next, we’ll write that bash script that switches viewing between the two tabs. Usually, the keyboard shortcut Ctrl+Tab cycles through the open browser tabs. Our script will use the program we installed, wtype, to simulate and automate keystrokes. 

To create the script and open it in nano, type:


nano ~/switchtab.sh


Add the following to the file:


#!/bin/bash

chromium_pid=$(pgrep chromium | head -1)

while
[
[-z $chromium_pid]]; do
  echo "Chromium browser is not running yet.
  
  sleep 5

  
  chromium_pid=$(pgrep chromium | head -1)

done

echo "Chromium browser process ID: $chromium_pid"

export XDG_RUNTIME_DIR=/run/user/1000

while true; do
  wtype -M ctrl -P Tab

  wtype -m ctrl -p Tab

  sleep 10
done


This script first checks that the Chromium browser is running. 

If not, it waits five seconds before trying again (this gives Chromium enough time to launch before moving on). To toggle between the two tabs, the script uses wtype to simulate Ctrl+Tab every ten seconds.
Press Ctrl+X, then Y, and finally Enter to save the new file with nano. Finally, reboot your Raspberry Pi:


$ sudo reboot

<!---
russanomarco/russanomarco is a special repository because its `README.md` (this file) appears on your GitHub profile.
You can click the Preview link to take a look at your changes.
--->
