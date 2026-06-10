Dla schroniska stworzono stronę internetową, na której użytkownicy mogą wybrać zwierzę, uzyskać informacje o nim
i o samym schronisku, a także wesprzeć je.
http://localhost/LAPKA-NADIYI/index.php

środowisko:
    -HTML5;
    -CSS3 & Bootstrap;
    -JavaScript;
    -PHP;
    -MySQL;
    -XAMPP.

Projekt zawiera:
1)Co najmniej 3 strony oprócz głównej(z nawigacją):
    -Strona logowania i rejestracji;
    -Strona kontaktowa;
    -Z informacjami o schronisku;
    -Możliwość pomocy finansowej schronisku;
    -Strona dla administratora i jej pochodne (zmiana, usuwanie, dodawanie danych).

2)Style CSS/Bootstrap:
    -Utworzono plik style.css, w którym podano style kart ze zwierzętami;
    -W większości przypadków używany jest Bootstrap.

3)Baza danych:
    -Tabela Animals zawierająca wszystkie dane o każdym zwierzęciu, tam też można modyfikować listy;
    -Tabela Authors z pracownikami i wolontariuszami;
    -Tabela Posts z contentem;
    -Tabela Users z zarejestrowanymi użytkownikami i danymi o nich.

4)Operacje CRUD(Create, Read, update, delete):
    -Aby zademonstrować te operacje, stworzono osobną stronę dla administratora(http://localhost/lapka-nadiyi/admin/index1.php).
    -Administrator może dodawać zwierzęta, zmieniać informacje na ich temat (np. ich wiek) i usuwać je z listy.

5)Odbieranie danych od użytkownika
    -formularz logowania i podstawowa walidacja wprowadzonych danych

6)Do walidacji użyto JS

7)2 powiązane tabele: authors, posts.

8)przetwarzanie sesji.

9)System logowania i rejestracji użytkowników.

10)Mechanizm wyświetlania i wyszukiwania danych:
    -Wyszukiwanie danych. Użytkownik może znaleźć zwierzę po nazwie.
    -Filtrowanie. Zarejestrowany użytkownik może dawać lajki zwierzętom. Wtedy możesz patrzeć tylko na te zwierzęta, które mają najwięcej polubień.

11)Podzielenie aplikacji na katalogi i pliki.

12)Dołączanie plików za pomocą include.

13)Hashowanie haseł.

14)Niedopuszczalność pustych pól.