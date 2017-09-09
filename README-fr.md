
<h1>Créer un système de compte utilisateur dans PHP partie 2</h1>

<br>
<br>
<br>


<p>
  Création en php orienté objet d'un projet de base regroupant les principales fonctionnalitées d'un système d'espace membre. Ce système permet d'avoir la base d'un projet similaire beaucoup plus complexe.
</p>

<br>

<p>
  Ce code fait suite au même projet réalisé en php procédural qui j'ai adapté pour la POO.
</p>

<br>

<p>
  Je n'ai pas utilisé les fonctionnalitées comme les namespace ou le système MVC pour que le code soit le plus simple possible, mais évidemment pour un projet plus complexe il sera indispensable de le modifier en conséquence.<br>Ce projet sera prochainement réorganisé en MVC.
</p>

<br>
<br>

<h2>Structure de la base de données</h2>

<p>
  La structure de la table de la base de données est représentée ci-dessous mais il est possible de renommer la table et les champs en fonction des conventions de chacuns.
</p>

<br>

<ul>
  <li>members</li>
  <ul>
    <li>id</li>
    <li>username</li>
    <li>email</li>
    <li>password</li>
    <li>confirmation_token</li>
    <li>confirmed_at</li>
    <li>reset_token</li>
    <li>reset_at</li>
    <li>remember_token</li>
  </ul>
</ul>
