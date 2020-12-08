### Инструкция к Install.ini
Данный файл полностью обратно совместим с install.ini для консольной установки.  
install.ini необходимо класть в корень репозитория.

Минимальное содержание install.ini. 
```
[LICENSE]
domain = "tester.test.ru"
ip = "127.0.0.1"
key = "testkey-testkey-testkey"
[DB]
host = "localhost"
user = "user"
password = "password"
dbname = "testdb"
[DEMOSITE]
name = "demomarket"
[SUPERVISOR]
login = "sv"
password = "1"
email = "igor.nikitin@umisoft.ru"
```

###Секция [LICENSE]
#####Domain
Имя домена, на которое устанавливается umi.cms2

####ip
Ip адрес, на который будет установлена umi.cms2. Если не знаете, что указывать, оставьте 127.0.0.1

####key
Лицензионный ключ umi.cms

###Секция [DB]
#####host
Адрес БД

#####user
Имя пользователя для БД

#####password
Пароль для пользователя в БД

#####dbname
Имя используемой БД

###Секция [DEMOSITE]

#####name
Название устанавливаемого шаблона. Поддерживаются все шаблоны, доступные для umi.cms2

###Секция [SUPERVISOR]

#####login
Логин для учетной записи администратора

#####password
Пароль для учетной записи администратора

#####email
Адрес электронной почты для администратора.