Delta kite calculator
====================
Calculates a "Dan Leigh standard delta kite" from provided nose angle and center line length. PHP version is online: https://www.jesseo.com/kites/delta-calculator.php and golang version is a work in progress. 


Run in docker: 
-------------------
``` docker build -t delta-kite-calculator:latest .```

``` docker run -it -p8000:8000 --rm delta-kite-calculator:latest```

To Do: 
-------------------
 - add github action CI/CD pipeline
 - add unit tests
 - add metrics and logging
 - add a RESTful API
 - modernize html: convert tables to divs, etc
 - secure Dockerfile
 - adjust float64 formatting
