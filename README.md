# svelte-external-api-test

## Install
1. Build backend image `docker build -t svelte-external-api-test_backend:latest backend/`
2. Start backend & reverse proxy `docker compose up -d`
3. Add root certificate system: `sudo cp reverse-proxy/data/caddy/pki/authorities/local/root.crt /usr/local/share/ca-certificates/svelte-external-api-test.crt` and `sudo update-ca-certificates`

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
|                    |                         |                                          |
