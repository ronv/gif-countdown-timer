# GIF Countdown Timer
Countdown Timer for your email campaigns.

Fixed the curly braces issues caused by newer php versions.

# Usage

1. Set timezone

<code>date_default_timezone_set('Europe/Riga');</code>

2. Change background

<code>$image = imagecreatefrompng('background/1.png');</code>

3. Font size

<code>'size'=>65</code>

4. Angle of the text

<code>'angle'=>0</code>

5. Offset on x asis

<code>'x-offset'=>70</code>

6. Offset on y asis

<code>'x-offset'=>10</code>

7. Define the font

<code>'file'=>'fonts/PT_Sans-Web-Regular.ttf'</code>

8. RGB color of the text

<code>'color'=>imagecolorallocate($image, 255, 255, 255)</code>

# Adding to an email

<code>YOUR HOST/GIFcountdownTimer/img.gif?dt=2015-03-07/16:22:20</code>

where dt is date & time.

