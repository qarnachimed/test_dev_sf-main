test-dev
========

Un stagiaire à créer le code contenu dans le fichier src/Controller/Home.php

Celui permet de récupérer des urls via un flux RSS ou un appel à l’API NewsApi. 
Celles ci sont filtrées (si contient une image) et dé doublonnées. 
Enfin, il faut récupérer une image sur chacune de ces pages.

Le lead dev n'est pas très satisfait du résultat, il va falloir améliorer le code.

Pratique : 
1. Revoir complètement la conception du code (découper le code afin de pouvoir ajouter de nouveaux flux simplement) 

Questions théoriques : 
1. Que mettriez-vous en place afin d'améliorer les temps de réponses du script
2. Comment aborderiez-vous le fait de rendre scalable le script (plusieurs milliers de sources et images)


- Réponse pour les questions théoriques : 

1- Pour améliorer les temps de réponse du script, on peut mettre en place les actions suivantes :

      A - Caching : On peut utiliser un système de mise en cache pour les données fréquemment utilisées, comme les réponses des requêtes HTTP. Cela évite de refaire les mêmes   requêtes à chaque exécution du script.
      B - Utilisation des requêtes HTTP parallèles : Si nous récupérons des données à partir de plusieurs sources, effectuez les requêtes HTTP en parallèle plutôt que séquentiellement. Cela permet d'économiser du temps en récupérant simultanément les données à partir de différentes sources.
      C - Optimisation les opérations de traitement des données : On peut analyser le code pour identifier les boucles ou les opérations coûteuses et cherchez des moyens de les optimiser. On peut utiliser des algorithmes plus efficaces ou réduisez les opérations redondantes pour améliorer les performances globales du script.

2- Pour rendre le script scalable pour gérer plusieurs milliers de sources et d'images, on peut envisager les actions suivantes :

      A - Utilisation une architecture distribuée : Décomposez le traitement en tâches indépendantes et distribuez-les sur plusieurs machines ou serveurs. on peut utiliser des technologies telles que les systèmes de file d'attente ou les frameworks de calcul distribué pour répartir la charge de manière équilibrée.
      B - Mettez en cache les données fréquemment utilisées : On peut utiliser un système de mise en cache pour stocker les données qui sont souvent consultées. Cela permet d'éviter les requêtes répétées et d'améliorer les temps de réponse en récupérant les données directement à partir du cache.
      C - Utilisation des services cloud évolutifs : Si la charge est importante, envisagez d'utiliser des services cloud évolutifs tels que AWS, Google Cloud ou Microsoft Azure. Ces services offrent des capacités de mise à l'échelle automatique en fonction de la demande, nous permettant ainsi de gérer facilement un grand nombre de sources et d'images.
      D - Optimisation l'infrastructure sous-jacente : On peut assurer que notre infrastructure dispose des ressources adéquates pour supporter la charge prévue. Cela peut inclure l'utilisation de machines puissantes, la mise en place de clusters ou l'utilisation de technologies de conteneurisation pour faciliter la gestion et l'évolutivité du système.
