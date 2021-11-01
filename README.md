# Dashboard
_!Dies Projekt ist nicht gedacht zur Eigeninstallation!_<br>
Das Dashboard dient zur Darstellung & Verlinkung von selbst ausgewähkten Seiten.
Entwickelt um eine Übersicht über alle installieren und über das Web verfügbaren Services meines Servers zu haben.

![](.github/dashboard.png)
![](.github/config.png)

## Trotzdem installieren
1. `git clone https://github.com/cyl3x/dashboard`
2. `cd dashboard`
3. `docker-compose up -d`
   <br>or<br>
   `docker build -t dashboard . && docker run -dp 5600:80 dashboard`

