# Warsztaty DDD
Mariusz Gil, 2017-11-06

## Conceptual CQRS

![Conceptual CQRS](http://engineering.skybettingandgaming.com/images/alberto-cqrs.jpg)

Przykład z audit log ze zdarzeniami, które potem można było odtworzyć i uzyskać dane historyczne. Usuwanie z koszyka.

## Sposób migracji z legacy system
![EventSourcing Legacy](https://adaptechsolutions.net/wp-content/uploads/EventSourcingWithLegacy.png)

Uwaga na transakcje w bazie mysql - 4 poziomy

- Szukajmy interfejsów. 
- GRASP
    - Protected voliation

# Modelowanie systemu do pożyczek
Wykorzystamy proof'a 
Znajdowanie agregatów, event sourcing, modele do odczytu
[https://www.dropbox.com/s/40d03kzc1tgg63m/Impl%20DDD%20p1.pdf?dl=0]

## Narzędzia:
phpmoney/php
http://getprooph.org/
 - event store - eventy przechowywać w tabelach append only 
 - prooph daje implementację interfejsów do różnych bibliotek


- commandy powinny mieć typy proste dzięki czemu łatwiej przepchnąć przez rabbita
- maszyna stanowa 
    - symfony workflow [https://symfony.com/doc/current/components/workflow.html]
    - yohang [https://github.com/yohang/Finite]
    - operowanie na warunkach przez dopuszczalne stany [https://github.com/sebastianbergmann/state/tree/master/example/src]
- [https://github.com/prooph/humus-amqp-producer]
- posortowane indeksy na kilka wymiarów z użyciem TokuDB [https://www.percona.com/blog/2014/08/06/an-updated-description-of-clustering-keys-for-tokudb/]
- narzędzie do specyfikacji [https://github.com/K-Phoen/rulerz]
## phpmetrics
Lack of cohesion metrics - ile w danej klasie jest osobnych grup metod używających 

## inne polecane narzędzia
Domain STory telling [http://www.domainstorytelling.org/]
Bernard - system podpinania dowolnego systemu kolejkowania


# agregaty
jeżeli coś zwracają to ma to być niezmienne
alternatywne podejście: agregaty zasadniczo powinny być niewielkie, komunikacja za pomocą sagi. 

# Cennik
- kalkuluje
    - co kalkuluje? wykorzysanie usług
    - co zwraca? pieniądz
- jakie mamy cenniki (wzorzec Strategy)
    - free tier
    - liniowe: 0.01 pLN / 100maili
    - progresywne: 
        0 PLN / pierwsze 1k maili
        10 PLN / pierwsze 10k maili
        100 PLN / pierwsze 100k maili
        ...
    - cenniki kompozytowe czyli łączące kilka cenników w jeden (wzorzec Composite)
    
- jak zmodyfikować (wzorzec Decorator)
    5% zniżki na wszystko (RatioDiscount)
    100 PLN zniżki (FixedDiscount)
- kiedy zniżka jest nadawana

## jak znaleźć Interface?
- wyczulić się na zgłaszanych kilka wariantów danej 

## core domain
Co powoduje, że biznes zyskuje przewagę na rynku.

## Event Sourcing 
[https://www.slideshare.net/dennisdoomen/the-good-the-bad-and-the-ugly-of-event-sourcing] 

##Pytania:
 - sposoby kwalifikowania aplikacji do CQRS, kiedy używać domen także do odczytu.
 - przykłady dostępnych polecanych aplikacji z CQRS / 
 - UUID przechowywanie binarne czy inne? 