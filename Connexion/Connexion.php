<?php
/**
 * Created by PhpStorm.
 * User: instantech
 * Date: 09/03/19
 * Time: 00:08
 */

class Connexion{
    private static $pdo;

    /**
     * Creer une connexion à la base de donnée
     * Vous pouvez appeler la function sans preciser de mot de passe, si celui ci est vide
     * @example $bdd = create_connexiion('localhost','users','root') ou si celui ci est défini
     * @example $bdd = create_connexiion('localhost','users','root','1234567');
     * @param string $host
     * @param string $dbname
     * @param string $username
     * @param string $password
     * @return PDO|string
     */
    public static function create_connexion(string $host, string $dbname, string $username, string  $password = ""){
        try{
            self::$pdo = new PDO('mysql:host='.$host.';dbname='.$dbname.';charset=utf8',$username,$password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
            return self::$pdo;
        }catch (PDOException $exception){
            throw new PDOException("Connexion failed : ".$exception->getMessage());
        }

    }

}