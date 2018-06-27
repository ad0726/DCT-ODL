<section>
    <form action="odldc.php" method="post">
    <label for="titre">Titre de l'arc</label><br />
    <input type="text" id="titre"><br />
    <label for="cover">URL de la cover</label><br />
    <input type="url" id="cover"><br />
    <img src="http://img110.xooimage.com/files/9/6/3/01-54b6b56.jpg"><br />
    <label for="contenu">Contenu</label><br />
    <textarea name="contenu" id="contenu"></textarea><br />
    <label for="publication">Publié chez :</label><br />
    <input type="checkbox" name="publication" value="urban"> Urban<br />
    <input type="checkbox" name="publication" value="dctrad"> DCTrad<br />
    <label for="topic">URL du topic</label><br />
    <input type="url" id="topic"><br />
    <input type="submit" value="Envoyer">
    </form>
</section>