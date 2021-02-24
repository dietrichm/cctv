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

This feature keeps a DNS record on Neostrada in sync with a dynamically allocated IP address. This is the case when hosting the service on a local computer, such as on a Raspberry Pi.

Some parts of the domain code is present, but the (console) commands and cron job to trigger the sync is missing. Hence this feature is not functional. Anyway, the following environment variables are taken into account:

* `NEOSTRADA_API_TOKEN`: API token.
* `NEOSTRADA_DNS_ID`: ID of the DNS entry.
* `NEOSTRADA_RECORD_ID`: ID of the DNS record in the entry.

## Technical details

Even though the goal of the project is relatively simple and the feature set is limited, the application was built using some industry best practices, including:

* Test-driven development: unit and integration tests were written in tandem with the business/application code.
* Domain-driven design: the codebase is layered with an infrastructural layer, a domain layer and an application layer. Business scenarios in the problem domain are implemented in the domain layer using commands and handlers, while the application layer implements repositories, factories, HTTP request handlers, etc.
* Use of a micro framework ([Slim](https://www.slimframework.com/) 3) in combination with a dependency injection container ([The PHP League's Container](https://container.thephpleague.com/)), and generic dependencies [Guzzle](http://guzzlephp.org/), [Flysystem](https://flysystem.thephpleague.com/), [Twig](https://twig.symfony.com/), [Tactician](https://tactician.thephpleague.com/), etc.
* Configuration through the environment, by defining environment variables.
* Logging and maintaining errors and exceptions to/in an external Sentry project.

The use case for this project has disappeared over time, so the code is not being maintained. If this was the case, I would probably replace Twig with a React based front-end in JavaScript or TypeScript.

## License

Copyright 2015, Dietrich Moerman.

Released under terms of the [GNU General Public License v3.0](LICENSE).
