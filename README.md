# Simple share Wordpress plugin

## Description

A really lightweight plugin, that helps you add fixed social share links on every page to share it. 

## Benefits

- Native Wordpress APIs without external libraries;
- No resources fetched from external servers;
- Transpilled Javascript and CSS with prefixes;
- Compressed SVG icons;
- Each link has `_wsp_t` URL parameter with its own value (e.g. `https://twitter.com/share?url=https://example.com/?_wsp_t=twi`);
- Separate and scoped CSS classes for everything;
- Customizable shares counter;

## Supported social networks/messengers

- Facebook;
- Twitter;
- WhatsApp;
- Telegram;
- Viber;
- Email.

## Disadvantages

- You have only six social links without build-in possibility to change icon/position/something else or remove it;

## TODO

- Social links management (adding new, removing, etc.);
- Moving WP Settings methods from the main class to a separate one;
- Add creating own social media link possibility;
- Add "Custom CSS" and "Custom JS" fields;