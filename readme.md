# Purge bot

Example bot to delete messages

## Installation

Clone the repository
```bash
git clone https://github.com/Mateodioev/...
cd ...
```

Install dependencies
```bash
composer install
```

## Usage

### Webhook mode

For this you will need a public endpoint with https
In the file `webhook.php` you can find an example of a simple server with _amphp/http-server_

```bash
php webhook.php
```

You can also set the webhook with the file setwebhook.php

```bash
php setwebhook.php
```

### Longpolling

For this you dont need a public server, only execute the script

```bash
php longpolling.php
```