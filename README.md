# IT2-Librairies-PHP
Une petite librairie en PHP pour vous aider à mettre en place un site web qui nécessite une mise en place de session, de validation de formulaire, de redirection, de vérification de l’existence d'un utilisateur dans la base de donnée, de selection, de mise à jour, de suppression simple dans une base de donnée
#Utilisation
  ##Initialisation
Dans le repertoire, puis le fichier Connexion/getBdd.php, renseigner les identifiants de connexion à votre base de donnée par : 
  <?php
/**
 * Created by Instantech.
 * User: instantech
 * Date: 09/03/19
 * Time: 10:43
 */
require_once 'Connexion.php';
$bdd = Connexion::create_connexion('your hostname','your dbname','username','your password');

vous pouvez ne pas ajouter le mot de passe si par defaut votre mot de passe est vide

Ensuite, vous pouvez utiliser les methodes suivantes
  <?php
/**
 * Created by Instantech.
 * User: instantech
 * Date: 08/03/19
 * Time: 22:09
 */


/**
 * Créer une session avec plusieurs paramètres et valeurs
 * @example ( make_sessions(array('username' => 'instantech', 'mail' => 'instantech@gmail.com')
 * @param array $options
 * @throws Exception
 */
function make_sessions(array $options);
/**
 * Recoit une url vers laquelle serait rediriger l'utilisateur si besoin
 * @example destroy_session('connexion.php')
 *Detruit une session
 * @param null $redirectTo
 * @throws Exception
 */
function destroy_session($redirectTo = null);

/**
 * @example redirect('accueil.php')
 * Redirige vers l'url recu en paramètre
 * @param $urlTo
 * @throws Exception
 */
function redirect($urlTo);
/**
 * Deconnecte un utilisateur et le redirige vers la page passée en argument
 * @example logout('connexion.php')
 * @param $urlTo
 * @throws Exception
 */
function logout($urlTo);
/**
 * Connecte un utilisateur en le redirigeants vers la page spécifier en argument
 * @example login('home.php or index.php or accueil.php')
 * @param $urlTo
 * @param array $options
 * @param $name
 * @param $value
 * @throws Exception
 */
function login( $urlTo, array  $options = []);
/**
 * Verifie qu'un utulisateur existe dans la base de donnée
 * @example $resultat = findByWhere('user',
 *     array('name','mail','password')
 *     array('name' => 'instantech',
 *           'email' => 'instantech@mail.com),
 *     array('='),
 *           'and')
 Ici sa sera "SELECT COUNT(id),name, mail, password FROM $table_name WHERE name = instantech AND email = instantech@mail.com;
 * @param $table_name
 * @param array $dataFields
 * @param array $criterias
 * @param $operators
 * @param $conditions
 * @return array
 * @throws Exception
 */
function userSelect($table_name, array $dataFields, array $criterias, $operators, $conditions = null);
/**
 * Retourne la valeur de la clé $name de la variable $_SESSION
 * @example ( $username = get_session('user_name');
 * @param $name
 * @return mixed
 */
function get_session($name);
/**
 * Genere un mot de passe de 256 bit hashé
 * @example  $password = creat_hashed_password('instantech@123!@')
 * @param $password
 * @return string
 */
function creat_hashed_password($password);

/**
 * @example insertInTo('clients',
 *    array('name' => 'instantech',
 *          'mail' => 'instantech@mail.com',
 *          'username' => 'instantech28'))
 Ici sa sera INSERT INTO clients (name,mail,username) VALUES (instantech,instantech@mail.com,instantech28)
 * @param $table_name
 * @param array $data
 * @return string
 * @throws Exception
 */
function insertInTo($table_name, array $data);
/**
 * Selectionne et renvoie toutes les données d'une table
 * @example $resultat = findAll('user')
 * @param $table_name
 * @return array
 */
function selectAll($table_name);

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
function selectWhere($table_name, array $dataFields, array $criterias, $operators, $conditions = null);

/**
 * Met a jour les donnees d'une base de donnnée : 
 * @example update('user',array(
    'name' => 'instantech',
    'email' => 'instantech@mail.com',
    'password' => creat_hashed_password("instantech")),
     array('email' => 'instantech@mail.com'),
    array('=')), l'avant dernier tableau porte sur les condition, le dernier sur l'operateur de comparaison :  ici sa sere WHERE email = instantech@mail.com
    Vous pouvez utiliser un dernier parametre qui est soit le and ou le ou
 * @param $table_name
 * @param array $fields
 * @param array $criterias
 * @param $operators ['=','<', '>', '<=', '>=', '!='] comme valeur autorisée
 * @param $condition AND ou OR
 * @return string
 * @throws Exception
 */
function update($table_name, array $fields, array $criterias, array $operators, $condition = null);
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
function delete($table_name, $criterias, $operators, $condition = null);
/**
 * Recherche suivant l'id du champ de la table
 * $result = findById('user',3);
 * @param $table_name
 * @param $id
 * @return mixed
 */
function findById($table_name, $id);

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
function form_validate($min_length, $max_length, $submit_name);
/**
 * @param $form_field
 * @param int $min_length
 * @return bool
 */
function min_length($form_field, $min_length = 5);

/**
 * @param $form_field
 * @param int $max_length
 * @return bool
 */
function max_length($form_field, $max_length = 10);

/**
 * Valide un mot de passe de plus ou egal de 8 caractères
 * @param $form_field
 * @param int $max_length
 * @return bool
 */
function password_validate($form_field, $max_length = 8);

/**
 * Verifie que le mail n'est pas vide et est valide
 * @example $is_valid_mail = mail_validate($_POST['email'])
 * @param $form_field
 * @return bool
 */
function mail_validate($form_field);

/**
 *  Verifie si un mail est valide
 * @example $is_valid_mail = is_mail($_POST['email'])
 * @param $form_field
 * @return mixed
 */
function is_mail($form_field);

/**
 * Verifie si un numero de telephone est valide
 * @example $is_valid_number_phone = tel_validate($_POST['tel'])
 * @param $form_field
 * @return bool
 */
function tel_validate($form_field);

/**
 * Verifie bien qu'il s'agit d'un vrai nombre
 * @param $number_field
 * @return bool
 */
function number_validate($number_field);
/**
 * Convertie une date de type="datetime-local" en par exemple 2019-03-09 10:53:20
 * @example $date = get_date_time($_POST['date_time'])
 * @param $date_field
 * @return false|string
 */
function get_date_time($date_field);
/**
 * Convertie une date de type="date" en par example 2019-03-09
 * @example $date = get_date($_POST['date'])
 * @param $date_field
 * @return false|string
 */
function get_date($date_field);

/**
 * Verifier si la longueur d'un champ vaut au minimun 5 caractères
 * @param $form_field
 * @param int $min_length
 * @param int $max_length
 * @return bool
 */
function fieldname_validate($form_field, $min_length = 5, $max_length = 10);
/**
 * @example ( $name = purge($_GET['name'])
 * Echappe la donnée des balises <></>
 * @param $form_field
 * @return string
 */
function purge($form_field);
