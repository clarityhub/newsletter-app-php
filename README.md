# Clarity Hub Newsletter

Open-source newsletter that Clarity Hub would send out.

1. Make sure you have Docker installed and running
2. Add `127.0.0.1       admin.clarityhub.app` to `/etc/hosts`.
3. In `./app`, `cp .env.example .env` and fill in the MAILCHIMP_APIKEY and MAILCHIMP_LIST_ID
  - On mailchimp, Account Settings > Extras > API Keys > Generate API Key | Lists > Settings respectively
4. You will need to generate ssl certs using `./scripts/create-ssl`. Add the cert.crt to your keychain.
  - Make sure the cert is always trusted (right click - Get Info - Trust - Always Trust)
5. Run `./start`
6. Go to https://admin.clarityhub.app.

You may need to generate an App Key using Laravel: `php artisan key:generate`.
(SSH into the php docker container and run key generation. You may need to restart the container)

You may want to install PHP7+ on your machine so that you can run `php artisan` commands on your machine without SSHing into docker.

## SSHing into Laravel Docker

```
docker exec -it admin_laravel-app_1 /bin/bash
```
