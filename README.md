# SmogBuster Prestashop 1.7 module

For synchronization air quality data from **api.gios.gov.pl**

Api documentation [http://powietrze.gios.gov.pl/pjp/content/api](http://powietrze.gios.gov.pl/pjp/content/api)

## Instalation process
1. Install module in presta
1. Add sync script into corne (**important one per hour!**)

## Synchronization air quality data

Url for synchronization database
```
https://yourdomain.com/modules/smogbuster/sync.php
```

## Getting air quality data from database
Url for get data from database

* response: JSON
* metohd: GET

```
https://yourdomain.com/modules/smogbuster/api.php
```

Example response:

```javascript
[
  {
    "id": 1,
    "station_id": 114,
    "name": "Wrocław - Bartnicza",
    "latitude": "51.1159330",
    "longitude": "17.1411250",
    "city": "Wrocław",
    "address": "ul. Bartnicza",
    "st": 0,
    "so2": -1,
    "no2": 0,
    "co": -1,
    "pm10": -1,
    "pm25": -1,
    "o3": 0,
    "c6h6": -1,
    "created_at": "2019-01-08 15:33:33",
    "updated_at": "2019-01-09 08:31:02"
  },
  ...
]
```
