<?php
/**
 * Created by Instantech.
 * User: instantech
 * Date: 08/03/19
 * Time: 22:09
 */

/**
 * Créer une session avec la cle et la valeur pour la session
 * @example  make_session('username','instantech')
 * @param $name
 * @param $value
 * @throws Exception
 */
function make_session($name, $value){
    if (!isset($name,$value) || (empty($name) && empty($value)))
        throw new Exception("Vous devez fournir la cle et la valeur pour creer la session");
    $_SESSION[$name] = $value;
}

/**
 * Créer une session avec plusieurs paramètres et valeurs
 * @example ( make_sessions(array('username' => 'instantech', 'mail' => 'instantech@gmail.com')
 * @param array $options
 * @throws Exception
 */
function make_sessions(array $options){
    if(!isset($options) || empty($options))
        throw new Exception("Vous devez fournir les valeurs cle => valeur pour creer la session ");
    foreach ($options as $name => $value){
        $_SESSION[$name] = $value;
    }
}

/**
 * Recoit une url vers laquelle serait rediriger l'utilisateur si besoin
 * @example destroy_session('connexion.php')
 *Detruit une session
 * @param null $redirectTo
 * @throws Exception
 */
function destroy_session($redirectTo = null){
    session_destroy();
    if (!is_null($redirectTo))
        redirect($redirectTo);
}

/**
 * @example redirect('accueil.php')
 * Redirige vers l'url recu en paramètre
 * @param $urlTo
 * @throws Exception
 */
function redirect($urlTo){
    if (!file_exists($urlTo))
        throw new Exception("L'url que vous avez fourni n'est pas valide ");
    header('Location:'.$urlTo);
}

/**
 * Deconnecte un utilisateur et le redirige vers la page passée en argument
 * @example logout('connexion.php')
 * @param $urlTo
 * @throws Exception
 */
function logout($urlTo){
    destroy_session($urlTo);
}

/**
 * Connecte un utilisateur en le redirigeants vers la page spécifier en argument
 * @example login('home.php or index.php or accueil.php')
 * @param $urlTo
 * @param array $options
 * @param $name
 * @param $value
 * @throws Exception
 */
function login( $urlTo, array  $options = []){
    if (!empty($options)){
        make_sessions($options);
        redirect($urlTo);
    }
    else
        throw new Exception("Vous devez fournir une liste de cle => valeur pour la création de la session");
}
/**
 * Selectionne et renvoie toutes les données d'une table
 * @example $resultat = findByWhere('user',
 *     array('user.name','user.mail','user.password')
 *     array('name' => 'instantech',
 *           'email' => 'instantech@mail.com),
 *     array('='),
 *           'and')
 * @param $table_name
 * @param array $dataFields
 * @param array $criterias
 * @param $operators
 * @param $conditions
 * @return array
 * @throws Exception
 */
function userSelect($table_name, array $dataFields, array $criterias, $operators, $conditions = null){
    global $bdd;
    $query = 'SELECT COUNT(id) as exist,'.implode(',',$dataFields).' FROM '.$table_name.' WHERE '.create_querySelect($criterias,$operators,$conditions);
    $requette = $bdd->prepare($query);
    $requette->execute(getTableValues($criterias));
    $resultats = $requette->fetch();
    return $resultats;

}

/**
 * @param $name
 * @return mixed
 */
function get_session($name){
    if (isset($_SESSION) && isset($_SESSION[$name]))
        return $_SESSION[$name];
    return null;
}

/**
 * Genere un mot de passe de 256 bit
 * @example  $password = creat_hashed_password('instantech@123!@')
 * @param $password
 * @return string
 */
function creat_hashed_password($password){
    return hash('sha256','instantech'.purge($password).'instantech');
}

/**
 * @example insertInTo('clients',
 *    array('name' => 'instantech',
 *          'mail' => 'instantech@mail.com',
 *          'username' => 'instantech28'))
 * @param $table_name
 * @param array $data
 * @return string
 * @throws Exception
 */
function insertInTo($table_name, array $data){
    global $bdd;
    $query = 'INSERT INTO '.$table_name.' ('.implode(',',getTableColums($data)).') VALUES ('.implode(',',getPrepareNumbersFields($data)).')';
    $requette = $bdd->prepare($query);
    $nbline_affeted = $requette->execute(getTableValues($data));
  return $nbline_affeted;
}

/**
 * Selectionne et renvoie toutes les données d'une table
 * @example $resultat = findAll('user')
 * @param $table_name
 * @return array
 */
function selectAll($table_name){
    global $bdd;
    $query =  $query = 'SELECT * FROM '.$table_name;
    $results = $bdd->query($query)->fetchAll();
    return $results;
}

/**
 * Selectionne et renvoie toutes les données d'une table
 * @example $resultat = findByWhere('user',
 *     array('user.name','user.mail','user.password')
 *     array('name' => 'instantech',
 *           'email' => 'instantech@mail.com),
 *     array('='),
 *           'and')
 * @param $table_name
 * @param array $dataFields
 * @param array $criterias
 * @param $operators
 * @param $conditions
 * @return array
 * @throws Exception
 */
function selectWhere($table_name, array $dataFields, array $criterias, $operators, $conditions = null){
    global $bdd;
    $query = 'SELECT '.implode(',',$dataFields).' FROM '.$table_name.' WHERE '.create_querySelect($criterias,$operators,$conditions);
    $requette = $bdd->prepare($query);
    $requette->execute(getTableValues($criterias));
    $resultats = $requette->fetchAll();
    return $resultats;

}

/**
 * Met a jour les donnees d'une base de donnnée
 * @example update('user',array(
    'name' => 'instantech',
    'email' => 'instantech@mail.com',
    'password' => creat_hashed_password("instantech")),
 * array('name','email','password'),'and'), vous pouvez utilez l'option or aussi;
 * @param $table_name
 * @param array $fields
 * @param array $criterias
 * @param $operators ['=','<', '>', '<=', '>=', '!='] comme valeur autorisée
 * @param $condition AND ou OR
 * @return string
 * @throws Exception
 */
function update($table_name, array $fields, array $criterias, array $operators, $condition = null){
    global $bdd;
    $query = 'UPDATE '.$table_name.' SET '.create_queryUpdate($fields,$operators).' WHERE '.create_querySelect($criterias,$operators,$condition);
    $requette = $bdd->prepare($query);
    $nblignes = $requette->execute(array_merge(getTableValues($fields), getTableValues($criterias)));
    return $nblignes;
}

/**
 * Supprime une donnée ou des données dans la base de données suivant les conditions
 * @example delete('user',array(
    'age => 10,
 *  'sexe' => 'masculin'
    ),'
    array('<','='),
 * or');
 * @param $table_name
 * @param $criterias
 * @param $operators
 * @param null $condition
 * @return bool
 * @throws Exception
 */
function delete($table_name, $criterias, $operators, $condition = null){
    global $bdd;
    $query = 'DELETE FROM '.$table_name.' WHERE '.create_querySelect($criterias,$operators,$condition);
    echo $query;
    $requette = $bdd->prepare($query);
    $nblignes = $requette->execute(getTableValues($criterias));
    return $nblignes;
}
/**
 * Recherche suivant l'id du champ de la table
 * $result = findById('user',3);
 * @param $table_name
 * @param $id
 * @return mixed
 */
function findById($table_name, $id){
    global $bdd;
    $query = 'SELECT * FROM '.$table_name.' WHERE id = ?';
    $requette = $bdd->prepare($query);
    $requette->execute(array($id));
    $resultats = $requette->fetch();
    return $resultats;
}
/**
 * @param array $data
 * @return array
 * @throws Exception
 */
function getTableColums(array $data){
    if(empty($data))
        throw new Exception("Vous n'avez pas fournis les noms des champs de la table");
    $colums = [];
    foreach ($data as $colum => $value){
        $colums[] = $colum.' ';
    }
    return $colums;
}

/**
 * @param array $data
 * @return array
 * @throws Exception
 */
function getTableValues(array $data){
    if(empty($data))
        throw new Exception("Vous n'avez pas fournis les valeus des champs de la table");
    $values = [];
    foreach ($data as $colum => $value){
        $values[] = $value.' ';
    }
    return $values;
}

/**
 * @param $data
 * @return array
 */
function getPrepareNumbersFields($data){
     $numberFields = count($data);
     return explode(',',str_repeat("?,",$numberFields-1)."?");
}

/**
 * @param $criterias
 * @param $operators
 * @param $conditions
 * @return string
 * @throws Exception
 */
function create_querySelect($criterias, $operators, $conditions = null){
    $query = '';
    $fields_conditions_keys = getTableColums($criterias);
    $fields_conditions_question = getPrepareNumbersFields($criterias);
    $operators_count = count($operators);
    $operators_value = getTableValues($operators);
    $length = count($criterias);
    if (!is_null($conditions)){
        if (in_array(strtoupper($conditions),array('OR','AND',))){
            for($i=0;$i<$length-1;$i++){
                if ($operators_count == 1)
                    $query .=$fields_conditions_keys[$i].' '.$operators_value[0].' '.$fields_conditions_question[$i].' '.strtoupper($conditions).' ';
                else
                    $query .=$fields_conditions_keys[$i].' '.$operators_value[$i].' '.$fields_conditions_question[$i].' '.strtoupper($conditions).' ';
            }
            $query .=$fields_conditions_keys[$length-1].' '.$operators_value[$operators_count-1].' '.$fields_conditions_question[$length-1];
        }else
            throw new Exception("Argument invalid : accepted paramaters => and, or");
    }else
        $query .=$fields_conditions_keys[$length-1].' '.$operators_value[0].' '.$fields_conditions_question[$length-1];
    return $query;
}

/**
 * @param $criterias
 * @param $operators
 * @return string
 * @throws Exception
 */
function create_queryUpdate($criterias,$operators){
    $query = '';
    $fields_updated_keys = getTableColums($criterias);
    $fields_conditions_question = getPrepareNumbersFields($criterias);
    $operators_count = count($operators);
    $operators_value = getTableValues($operators);
    $length = count($criterias);
    for($i=0;$i<$length-1;$i++){
        if ($operators_count == 1)
            $query .=$fields_updated_keys[$i].' '.$operators_value[0].' '.$fields_conditions_question[$i]. ' , ';
        else
            $query .=$fields_updated_keys[$i].' '.$operators_value[$i].' '.$fields_conditions_question[$i]. ' , ';
    }
    $query .=$fields_updated_keys[$length-1].' '.$operators_value[ $operators_count-1].' '.$fields_conditions_question[$length-1];

    return $query;

}

/**Valide un formulaire en fonction des types des champs en specifiant la taille minimale et maximale autorisées pour les champs ordinaires
 * @example if (isset($_POST['ok'])){
                $is_valid_form = form_validate(5,10);
                if ($is_valid_form['is_ok']){
                    echo "Ok";
                redirect('../index.php');
            }else{
                echo $is_valid_form['error'];
            }
        }
 * @param $min_length
 * @param $max_length
 * @return array
 */
function form_validate($min_length, $max_length, $submit_name){
    $error = '';
        foreach ($_POST as $name => $value){
            if ($name == 'password'){
                if (!password_validate($value)){
                    $error = "Le mot de passe doit au minimum faire 8 caractères";
                    break;
                }
            }elseif ($name == 'email'){
                if (!mail_validate($value)){
                    $error = "Votre mail est incorrecte";
                    break;
                }
            }elseif ($name == 'phone_number'){
                if (!tel_validate($value)){
                    $error = "Le numero que vous avez saisi est incorrecte ";
                    break;
                }

            }elseif($name == 'number_field') {
                if (!number_validate($value)){
                    $error = "Vous devrez saisir un nombre valid";
                    break;
                }
            }else if ($name != $submit_name){
                    if (!fieldname_validate($value,$min_length,$max_length)){
                        $error = "Tous les champs textes doivent au minimum faire $min_length de caractères et ne doit pas depasser $max_length";
                        break;
                    }
                }
        }

    return array('is_ok' => empty($error), 'error' => $error);
}

/**
 * @param $form_field
 * @param int $min_length
 * @return bool
 */
function min_length($form_field, $min_length = 5){
    return strlen(purge($form_field)) >= $min_length;
}

/**
 * @param $form_field
 * @param int $max_length
 * @return bool
 */
function max_length($form_field, $max_length = 10){
    return strlen(purge($form_field)) <= $max_length;
}

/**
 * Valide un mot de passe de plus ou egal de 8 caractères
 * @param $form_field
 * @param int $max_length
 * @return bool
 */
function password_validate($form_field, $max_length = 8){
    return min_length($form_field,$max_length) && !empty(purge($form_field));
}

/**
 * Verifie que le mail n'est pas vide et est valide
 * @example $is_valid_mail = mail_validate($_POST['email'])
 * @param $form_field
 * @return bool
 */
function mail_validate($form_field){
    return !empty(purge($form_field)) && is_mail($form_field);
}

/**
 *  Verifie si un mail est valide
 * @example $is_valid_mail = is_mail($_POST['email'])
 * @param $form_field
 * @return mixed
 */
function is_mail($form_field){
    return filter_var(purge($form_field),FILTER_VALIDATE_EMAIL);
}

/**
 * Verifie si un numero de telephone est valide
 * @example $is_valid_number_phone = tel_validate($_POST['tel'])
 * @param $form_field
 * @return bool
 */
function tel_validate($form_field){
    return ( preg_match( '/\d?(\s?|-?|\+?|\.?)((\(\d{1,4}\))|(\d{1,3})|\s?)(\s?|-?|\.?)((\(\d{1,3}\))|(\d{1,3})|\s?)(\s?|-?|\.?)((\(\d{1,3}\))|(\d{1,3})|\s?)(\s?|-?|\.?)\d{3}(-|\.|\s)\d{4}/', $form_field )
        ||
        preg_match('/([0-9]{8,13})/', str_replace(' ', '', $form_field))
        ||
        ( preg_match('/^\+?\d+$/', $form_field) && strlen($form_field) >= 8 && strlen($form_field) <= 13 ) );
}

/**
 * Verifie bien qu'il s'agit d'un vrai nombre
 * @param $number_field
 * @return bool
 */
function number_validate($number_field){
    return (int)purge($number_field) > 0;
}

/**
 * Convertie une date de type="datetime-local" en par exemple 2019-03-09 10:53:20
 * @example $date = get_date_time($_POST['date_time'])
 * @param $date_field
 * @return false|string
 */
function get_date_time($date_field){
    return date_format(date(purge($date_field)),'Y-m-d H:i:s');
}

/**
 * Convertie une date de type="date" en par example 2019-03-09
 * @example $date = get_date($_POST['date'])
 * @param $date_field
 * @return false|string
 */
function get_date($date_field){
    return date_format(date(purge($date_field)),'Y-m-d');
}

/**
 * Verifier si la longueur d'un champ vaut au minimun 5 caractères
 * @param $form_field
 * @param int $min_length
 * @param int $max_length
 * @return bool
 */
function fieldname_validate($form_field, $min_length = 5, $max_length = 10){
    return min_length($form_field,$min_length) && max_length($form_field,$max_length);
}

/**
 * Echappe la donnée des balises <></>
 * @param $form_field
 * @return string
 */
function purge($form_field){
    return htmlspecialchars($form_field);
}
