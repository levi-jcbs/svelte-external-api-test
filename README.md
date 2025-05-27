# svelte-external-api-test

## Note about privileged ports 80 & 443
My firewall is configured to forward the ports 80 to 8001 and 443 to 4001. I'm using the following commands to achieve that under firewalld:

```bash
firewall-cmd --permanent --zone=public --add-forward-port=port=80:proto=tcp:toport=8001
firewall-cmd --permanent --direct --add-rule ipv4 nat OUTPUT 0 -o lo -p tcp --dport 80 -j REDIRECT --to-port 8001
firewall-cmd --permanent --direct --add-rule ipv6 nat OUTPUT 0 -o lo -p tcp --dport 80 -j REDIRECT --to-port 8001

firewall-cmd --permanent --zone=public --add-forward-port=port=443:proto=tcp:toport=4001
firewall-cmd --permanent --direct --add-rule ipv4 nat OUTPUT 0 -o lo -p tcp --dport 443 -j REDIRECT --to-port 4001
firewall-cmd --permanent --direct --add-rule ipv6 nat OUTPUT 0 -o lo -p tcp --dport 443 -j REDIRECT --to-port 4001
firewall-cmd --reload
```

## Install
1. Build backend image `docker build -t svelte-external-api-test_backend:latest backend/`
2. Start backend & reverse proxy `docker compose up -d`
3. Add root certificate system: `sudo cp reverse-proxy/data/caddy/pki/authorities/local/root.crt /usr/local/share/ca-certificates/svelte-external-api-test.crt` and `sudo update-ca-certificates`

(You can add this root cert (`reverse-proxy/data/caddy/pki/authorities/local/root.crt`) also to Google Chrome: Settings > Privacy and security > Security > Manage certificates > Installed by you > Import

## Run
- Backend & reverse proxy: `docker compose up -d`
- Frontend: `cd frontend` and `npm run dev`

## Expected behavior
No matter the URL, Svelte inherits cookies from "parent" request and does not do a second fetch request on client hydration.

## Actual behavior
|                    | relative URLs (`/api/`) | absolute URLs (`https://localhost/api/`) |
| ------------------ | ----------------------- | ---------------------------------------- |
| Cookie inheritance | :x:                     | :white_check_mark:                       |
| Runs once          | :white_check_mark:      | :x:                                      |

## Workaround

I guess the problem is, that vite confuses the host it runs on when using it behind a reverse proxy. I would call it a missing feature. I'm just getting rid of the reverse proxy in my dev environment, then it works. When deploying with node (in production) it works anyway.

### Dev

Vite is configured to proxy the api and listens directly on 8001 (forwarded from 80). No reverse proxy needed. https is not necessary because to localhost cookies with secure flag are also sent over http.

### Prod

1. Build `npm run build`
2. Run `PROTOCOL_HEADER=x-forwarded-proto HOST_HEADER=x-forwarded-host node build`