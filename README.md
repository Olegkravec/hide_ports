# Hide_Ports - Утилита для скрытия портов

## Для чего

Часто кибер специалисты сканируя вашу сеть/сервер делают выводы о функциональной составляющей хоста только благодаря открытым портам. Например, у вашего сервера открыты порты 80 и 443, что значит что сервер используется в качастве вебсервера. Утилита Hide_Ports создаст дополнительное количество портов в случайном порядке для введения атакующего в замешательство. Каждая попытка сканирования портов хоста может быть записана, и оповещена администратору сервера, ещё до того как атакующий доберётся до нужного порта. 
Банеры для каждого порта генерируются случайно, так чтобы порт-скенер каждый раз показывал разную информацию. 

## Как использовать

Запуск командой:
```
php hideme.php 10
```
В следствии чего будет открыто 10 портов.

Лучше всего будет если скрипт будет работать в качестве сервиса:
```
[Unit]
Description=Hide Ports
After=network.target

[Service]
PIDFile = /run/hideme.pid
WorkingDirectory=/srv/
ExecStart=/usr/bin/php /{{ $PATH }}/hideme.php 2000
Restart = always

[Install]
WantedBy=multi-user.target
```
