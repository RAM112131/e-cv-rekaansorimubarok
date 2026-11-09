## GitHub Copilot Chat

- Extension: 0.73.0 (prod)
- VS Code: 1.145.2 (c4453f8ea430795f941c1b2ff6c9321135a74a02)
- OS: win32 10.0.22000 x64
- GitHub Account: reka114141

## Network

User Settings:
```json
  "http.systemCertificatesNode": true,
  "telemetry.telemetryLevel": "all",
  "github.copilot.advanced.debug.useElectronFetcher": true,
  "github.copilot.advanced.debug.useNodeFetcher": false,
  "github.copilot.advanced.debug.useNodeFetchFetcher": true
```

Connecting to https://api.github.com:
- DNS ipv4 Lookup: 20.205.243.168 (28 ms)
- DNS ipv6 Lookup: Error (43 ms): getaddrinfo ENOTFOUND api.github.com
- Proxy URL: None (91 ms)
- Electron fetch (configured): HTTP 200 (41 ms)
- Node.js https: HTTP 200 (575 ms)
- Node.js fetch: HTTP 200 (196 ms)

Connecting to https://api.individual.githubcopilot.com/_ping:
- DNS ipv4 Lookup: 140.82.113.21 (9 ms)
- DNS ipv6 Lookup: Error (31 ms): getaddrinfo ENOTFOUND api.individual.githubcopilot.com
- Proxy URL: None (20 ms)
- Electron fetch (configured): HTTP 200 (297 ms)
- Node.js https: 