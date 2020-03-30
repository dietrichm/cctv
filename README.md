# CCTV

Web based snapshot viewer for rudimentary CCTV cameras.

## Goal of the project

Having a couple of cheap and pretty rudimentary CCTV cameras with awkward (and likely not very secure) web interfaces, we wanted a simple way of seeing a snapshot picture of both cameras at the same time. Since the web interfaces of the cameras we owned could show a snapshot picture, figuring out the URL to take a screen capture and have the JPEG picture returned to the browser was easy. This allows us to create our own snapshot overview page.

This project sets up such a public HTML page offering the current snapshot picture of all configured CCTV cameras, easily accessible using a desktop or mobile browser.

Additionally, due to the nature of the quirky cameras and the fact the application was running on a Raspberry Pi, some additional features were added.

## Features

* Show snapshot pictures of an arbitrary amount of CCTV cameras in a single row/column on a HTML page
* Configure snapshot request time-out per camera
* Reboot all cameras having a reboot trigger URL using a console command
* Log application errors to a [Sentry](https://sentry.io) project
* Maintain public IP address on a [Neostrada DNS record](https://help.neostrada.nl/) (**incomplete**)

## Installation

## Technical details
