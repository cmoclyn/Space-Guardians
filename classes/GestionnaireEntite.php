<?php

  /**
   * @var array|null $entites   Liste de toutes les entités ayant déjà été récupérées
   */
  private $entites;

  /**
   * Permet de récupérer la liste des requetes executées
   *
   * @return array
   */
  public function getRequetes(){

  /**
   * Permet de récupérer une ou plusieurs entités dans la base de données
   *
   * @param string  $classe   Nom de l'entité voulu
   * @param array   $where    Tableau de correspondance pour la recherche (spécifier les attributs de classe et nom les colonnes de la base)
   *
   * @return array
   */
  public function select($entite, $where = array(), $autre = self::PARENTS, $alea = false, $limit = 0){
        $var = $infos['variables'][$attribut]['colonne'];

    // La requête
    $requete = "SELECT * FROM $table $where";

    if($alea){
      $requete .= " ORDER BY RAND()";
    }
    if($limit){
      $requete .= "LIMIT $limit";
    }

    $sql = $this->pdo->prepare($requete);

   * Permet de faire persister (ajout ou modification) d'une entité dans la base de données
   *
   * @param Objet  $obj   Entité
   *
   * @return boolean
   */
  public function persist($obj){
      $champs   = array();
      $valeurs  = array();
      foreach($infos['variables'] as $variable => $base){
        $var = $obj->$variable;
        if(isset($base['colonne'])){
          $champs[] = $base['colonne'];
          switch($base['type']){
            case 'PK':
              if(isset($var)){
                $valeurs[] = $var;
              }
              else{
                array_pop($champs);
              }
              break;

            case 'string':
              $valeurs[] = "'$var'";
              break;

            case 'datetime':
              $valeurs[] = "'$var'";
              break;

            case 'objet':
              if(is_object($obj->$variable)){
                $valeurs[] = $var->id;
              }
              else{
                $valeurs[] = $var; // L'id
              }
              break;

            default:
              $valeurs[] = $var;
              break;
          }
        }
      }

      $update = array();
      for($i = 0; $i < count($champs); $i++){
        $update[] = $champs[$i].' = '.$valeurs[$i];
      }


      $champs   = '('.implode(', ', $champs).')';
      $valeurs  = '('.implode(', ', $valeurs).')';
      $update  = implode(', ', $update);

      // La requête
      $requete = "INSERT INTO $table $champs VALUES $valeurs ON DUPLICATE KEY UPDATE $update";
      $sql = $this->pdo->prepare($requete);

      $succes = $sql->execute();

      // On mémorise la requête et si elle a réussi ou échoué
      $this->requetes[] = array(
        'succes' => $succes,
        'requete' => $requete
      );

      // Si on a déjà un ID, c'est que c'était un update, sinon un insert
      if(isset($obj->id) && !empty($obj->id)){
        $methode = 'postUpdate';
      }
      else{
        $methode = 'postInsert';
      }

      $obj->id = $this->pdo->lastInsertId();

      if(method_exists($obj, $methode)){
        $obj->$methode();
      }

      return $succes;
    }
    else{
      die("La classe $classe n'a définie aucune correspondances");
    }
  }
          case 'array':
            break;
          default:
        if(substr($attribut, -2) == '!='){
          $attribut = substr($attribut, 0, -2);
        else{
        }

?>