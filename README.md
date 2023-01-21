# Dashboard
Das Dashboard ist eine in Vue.js und Laravel Lumen geschriebene Website, die das Anzeigen & Verlinken von Webseiten ermöglicht.<br>
Ursprünglich geschrieben um mir eine bessere Übersicht über alle installieren & über das Web verfügbaren Services meines Servers zu haben.

## Screenshots
![](.github/dashboard.png)
![](.github/config.png)

## Warum?
Einseits für eine schönere Lesezeichenseite speziell nur für meine Serivces, aber auch als Lernprojekt für
eine _JSON Web Token Authentifizierung_ mit einer bearbeitbaren Konfigurationsdatei

## Installieren
<ol>
  <li><code>wget https://raw.githubusercontent.com/cyl3x/dashboard/master/docker-compose.yml</code></li>
  <li><code>docker-compose up -d</code></li>
</ol>
or
<ol>
  <li><code>docker volume create dashboard && docker run -dp 5600:80 -v dashboard:/app/var ghcr.io/cyl3x/dashboard:latest</code></li>
</ol>