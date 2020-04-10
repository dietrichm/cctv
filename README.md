# CCTV

Web based snapshot viewer for rudimentary CCTV cameras.

## Goal of the project

Having a couple of cheap and pretty rudimentary CCTV cameras with awkward (and likely not very secure) web interfaces, we wanted some additional remotely accessible features such as the possibility of seeing a snapshot of all cameras at once.

This project primarily provides a public HTML page offering such an overview of all snapshots, easily accessible using a desktop or mobile browser.

## Features

* Show snapshot pictures of an arbitrary amount of CCTV cameras in a single row/column on a HTML page
* Configurable snapshot request time-out per camera
* Reboot all cameras having a reboot trigger URL using a console command
* Log application errors to a [Sentry](https://sentry.io) project
* Maintain public IP address on a [Neostrada DNS record](https://help.neostrada.nl/) (**incomplete**)

## Installation

Install the app by executing in the root of the repository:

```
make
make vendor
```

A copy of `.env.example` will be installed as `.env`. The application can be configured by changing and adding environment variables in this file.

When the Docker container is running, access the snapshots page at [http://localhost](http://localhost).

### Configuration

The following environment variables can be defined in the `.env` file.

* `TMP_DIR`: Path to the temporary directory for saving the router and Twig cache.
* `LOG_FILE`: Path to a log file where application errors are collected.
* `RAVEN_DSN`: DSN for the Sentry project you want to use for collecting application errors.
* `CAMERA_x_NAME`: Name of camera number `x`.
* `CAMERA_x_SNAPSHOT_URI`: Snapshot URI to obtain a picture of camera `x`.
* `CAMERA_x_REQUEST_TIMEOUT`: Request timeout value in seconds of camera `x` (_optional_).
* `CAMERA_x_REBOOT_URI`: Reboot URI for triggering a reboot of camera `x` (_optional_).

Multiple cameras are configured by defining variables with an increasing `x` index. Cameras have to be defined sequentially - an undefined name or snapshot URI variable finishes the configuration process.

__Note:__ after updating the environment variable file, a restart of the Docker container may be necessary.

#### Neostrada IP address

These are the environment variables for configuring the **incomplete** Neostrada IP address updater:

* `NEOSTRADA_API_TOKEN`: API token.
* `NEOSTRADA_DNS_ID`: ID of the DNS entry.
* `NEOSTRADA_RECORD_ID`: ID of the DNS record in the entry.

## Technical details
