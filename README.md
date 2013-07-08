RESS Toolbox for ExpressionEngine
---
*My take on RESS (Responsive Design + Server Side Components). Based on the 
[RESS-ee addon by John Faulds](https://github.com/tyssen/RESS-ee), which again is based on 
the [Simple-RESS implementation by Matt Stauffer](https://github.com/mattstauffer/simple-ress).*

I've added a plugin function "calc" with some helpful parameters for calculating max sizes, i.e for resizing 
images. I've also opted to use screen.width/screen.height for measuring the screen, as this IMHO is the most correct 
way of making sure the end user receives the correct response from the server. I've also added a debug mode that can be
turned on in the extension settings, which overrides the actual device size with whatever value you want. This is
very useful for debugging RESS when working on a computer.


Example Usage
---
*IMPORTANT! Make sure you also read the "Caching and RESS" section below!*

First, add the cookie javascript somewhere in your html document head: 

    {exp:ress_toolbox:cookie}

The following global variables will be available in your templates: 

    {ress}             {!-- Device screensize --}
    {ress_raw}         {!-- Raw value of the cookie (i.e "960,1") --}
    {ress_screensize}  {!-- Device screensize --}
    {ress_density}     {!-- Device pixel density --}
      
Intended to work something like this:
      
    <p>if screensize > 500 = {if {ress_screensize}>500}yes{if:else}no{/if}</p>
    <p>if screensize > 2000 = {if {ress_screensize}>2000}yes{if:else}no{/if}</p>

The calc function works as follows:

    {exp:ress_toolbox:calc size='800' subtract='40' retina='yes'}

Parameters:

**size:** The maximum base size to be returned.  
**subtract:** Size to be subtracted from the maximum size (think margins).  
**retina:** Wether to take into account if the device has a high-dpi screen (yes/no).  

So, the example above would return...

On a non-retina laptop with, say 1440px screen width: 800-40 = 760.  
On a phone with a non-retina screen and a screensize of 320x480: 480-40 = 440.  
On a phone with retina screen (2/1 pixel density) and a screensize of 320x480: 480*2 - 40 = 920.  

You can use this with for instance CE Image to optimize the size of generated images:

		{exp:ce_img:pair src="/images/lorem.jpg" width="{exp:ress_toolbox:calc size='800'}" parse="inward"}
			<img src="{made}" />
			<div class="">width = {width}, height = {height}</div>
		{/exp:ce_img:pair}

On a laptop, this image would be 800px wide, while on a phone, it would be smaller.


Caching and RESS
---
If you're using any kind of caching together with RESS, it's really important to know what you're doing or the 
results can be devastating (ok, exaggeration intended :)). 

First, the RESS cookie must **never** be cached. When first executed, a cookie is set with the appropriate values 
for the user, and the page is reloaded. If this request is cached, the javascript reload will be cached, resulting 
in an infinite number of page reloads. For every user that visits your page! So, never, ever cache the cookie, ok?
 
Second, if the code that contains conditionals or calculations based on the addons values is cached, all users will 
get the size of the user that requested the page when it was cached. So, either make sure you don't cache these values,
or also use the values as parameters to your cached content. 

I'm aware that it sound like RESS and caching is impossible, but it really isn't, it's just... hard. I think I'll 
have to write a blog post about it some time.


Changelog
---
### Version 1.0
 - Initial Public Release

